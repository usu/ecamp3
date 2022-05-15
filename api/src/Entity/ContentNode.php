<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Util\ClassInfoTrait;
use App\Doctrine\Filter\ContentNodePeriodFilter;
use App\Repository\ContentNodeRepository;
use App\Util\EntityMap;
use App\Validator\AssertJsonSchema;
use App\Validator\ColumnLayout\AssertColumWidthsSumTo12;
use App\Validator\ColumnLayout\AssertNoOrphanChildren;
use App\Validator\ContentNode\AssertBelongsToSameRoot;
use App\Validator\ContentNode\AssertNoLoop;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A piece of information that is part of a programme. ContentNodes may store content such as
 * one or multiple free text fields, or any other necessary data. Content nodes may also be used
 * to define layouts. For this purpose, a content node may offer so-called slots, into which other
 * content nodes may be inserted. In return, a content node may be nested inside a slot in a parent
 * container content node. This way, a tree of content nodes makes up a complete programme.
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'security' => 'is_authenticated()',
        ],
        'post' => [
            'denormalization_context' => ['groups' => ['write', 'create']],
            'security_post_denormalize' => 'is_granted("CAMP_MEMBER", object) or is_granted("CAMP_MANAGER", object)',
            'validation_groups' => [ContentNode::class, 'validationGroupsPost'],
        ],
    ],
    itemOperations: [
        'get' => ['security' => 'is_granted("CAMP_COLLABORATOR", object) or is_granted("CAMP_IS_PROTOTYPE", object)'],
        'patch' => [
            'denormalization_context' => ['groups' => ['write', 'update']],
            'security' => 'is_granted("CAMP_MEMBER", object) or is_granted("CAMP_MANAGER", object)',
            'validation_groups' => [ContentNode::class, 'validationGroupsPatch'],
        ],
        'delete' => ['security' => '(is_granted("CAMP_MEMBER", object) or is_granted("CAMP_MANAGER", object)) and object.parent !== null'], // disallow delete when contentNode is a root node
    ],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['contentType', 'root'])]
#[ApiFilter(ContentNodePeriodFilter::class)]
#[ORM\Entity(repositoryClass: ContentNodeRepository::class)]
class ContentNode extends BaseEntity implements BelongsToContentNodeTreeInterface, CopyFromPrototypeInterface {
    use ClassInfoTrait;

    public const COLUMNS_SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['columns'],
        'properties' => [
            'columns' => [
                'type' => 'array',
                'items' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => ['slot', 'width'],
                    'properties' => [
                        'slot' => [
                            'type' => 'string',
                            'pattern' => '^[1-9][0-9]*$',
                        ],
                        'width' => [
                            'type' => 'integer',
                            'minimum' => 3,
                            'maximum' => 12,
                        ],
                    ],
                ],
            ],
        ],
    ];

    public const SINGLETEXT_SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['text'],
        'properties' => [
            'text' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * The content node that is the root of the content node tree. Refers to itself in case this
     * content node is the root.
     */
    #[ApiProperty(writable: false, example: '/content_nodes/1a2b3c4d')]
    #[Gedmo\SortableGroup] // this is needed to avoid that all root nodes are in the same sort group (parent:null, slot: '')
    #[Groups(['read'])]
    #[ORM\ManyToOne(targetEntity: ContentNode::class, inversedBy: 'rootDescendants')]
    #[ORM\JoinColumn(nullable: true)] // TODO make not null in the DB using a migration, and get fixtures to run
    public ?ContentNode $root = null;

    /**
     * All content nodes that are part of this content node tree.
     */
    #[ApiProperty(readable: false, writable: false)]
    #[ORM\OneToMany(targetEntity: ContentNode::class, mappedBy: 'root')]
    public Collection $rootDescendants;

    /**
     * The parent to which this content node belongs. Is null in case this content node is the
     * root of a content node tree. For non-root content nodes, the parent can be changed, as long
     * as the new parent is in the same camp as the old one.
     */
    #[Assert\NotNull(groups: ['create'])] // Root nodes have parent:null, but manually creating root nodes is not allowed
    #[AssertBelongsToSameRoot(groups: ['update'])]
    #[AssertNoLoop(groups: ['update'])]
    #[ApiProperty(example: '/content_nodes/1a2b3c4d')]
    #[Gedmo\SortableGroup]
    #[Groups(['read', 'write'])]
    #[ORM\ManyToOne(targetEntity: ContentNode::class, inversedBy: 'children')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public ?ContentNode $parent = null;

    /**
     * All content nodes that are direct children of this content node.
     */
    #[ApiProperty(writable: false, example: '["/content_nodes/1a2b3c4d"]')]
    #[Groups(['read'])]
    #[ORM\OneToMany(targetEntity: ContentNode::class, mappedBy: 'parent', cascade: ['persist'])]
    public Collection $children;

    /**
     * Holds the actual data of the content node.
     */
    #[ApiProperty(example: ['text' => 'dummy text'])]
    #[Groups(['read', 'write'])]
    #[ORM\Column(type: 'json', nullable: true, options: ['jsonb' => true])]

    #[Assert\Sequentially(constraints: [
        new AssertJsonSchema(schema: self::COLUMNS_SCHEMA),
        new AssertColumWidthsSumTo12(),
        new AssertNoOrphanChildren(),
    ], groups: ['ColumnLayout'])]

    #[AssertJsonSchema(schema: self::SINGLETEXT_SCHEMA, groups: ['SingleText'])]

    #[Assert\IsNull(groups: ['create'])] // create with empty data; default value is populated by ContentNodeDataPersister

    public ?array $data = null;

    /**
     * The name of the slot in the parent in which this content node resides. The valid slot names
     * are defined by the content type of the parent.
     */
    #[ApiProperty(example: 'footer')]
    #[Gedmo\SortableGroup]
    #[Groups(['read', 'write'])]
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $slot = null;

    /**
     * A whole number used for ordering multiple content nodes that are in the same slot of the
     * same parent. The API does not guarantee the uniqueness of parent+slot+position.
     */
    #[ApiProperty(example: '0')]
    #[Gedmo\SortablePosition]
    #[Groups(['read', 'write'])]
    #[ORM\Column(type: 'integer', nullable: false)]
    public int $position = -1;

    /**
     * An optional name for this content node. This is useful when planning e.g. an alternative
     * version of the programme suited for bad weather, in addition to the normal version.
     */
    #[ApiProperty(example: 'Schlechtwetterprogramm')]
    #[Groups(['read', 'write'])]
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $instanceName = null;

    /**
     * Defines the type of this content node. There is a fixed list of types that are implemented
     * in eCamp. Depending on the type, different content data and different slots may be allowed
     * in a content node. The content type may not be changed once the content node is created.
     */
    #[ApiProperty(example: '/content_types/1a2b3c4d')]
    #[Groups(['read', 'create'])]
    #[ORM\ManyToOne(targetEntity: ContentType::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?ContentType $contentType = null;

    public function __construct() {
        parent::__construct();
        $this->children = new ArrayCollection();
        $this->rootDescendants = new ArrayCollection();
    }

    /**
     * Return dynamic validation groups.
     *
     * @return string[]
     */
    public static function validationGroupsPost(self $contentNode) {
        switch ($contentNode->getContentTypeName()) {
            default:
                return ['Default', 'create'];
        }
    }

    /**
     * Return dynamic validation groups.
     *
     * @return string[]
     */
    public static function validationGroupsPatch(self $contentNode) {
        switch ($contentNode->getContentTypeName()) {
            case 'ColumnLayout':
                return ['Default', 'update', 'ColumnLayout'];

            case 'Notes':
            case 'SafetyConcept':
            case 'Storycontext':
                return ['Default', 'update', 'SingleText'];

            default:
                return ['Default', 'update'];
        }
    }

    /**
     * The name of the content type of this content node. Read-only, for convenience.
     */
    #[ApiProperty(example: 'SafetyConcept')]
    #[Groups(['read'])]
    public function getContentTypeName(): string {
        return $this->contentType?->name;
    }

    /**
     * The entity that owns the content node tree that this content node resides in.
     */
    #[ApiProperty(readable: false)]
    public function getRoot(): ?ContentNode {
        // New created ContentNodes have root == this.
        // Therefore we use the root of the parent-node.
        if (null === $this->root && null !== $this->parent) {
            return $this->parent->root;
        }

        return $this->root;
    }

    public function getData(): ?array {
        return $this->data;
    }

    public function setData(?array $data) {
        $this->data = self::array_merge_recursive_distinct($this->data, $data);
    }

    /**
     * @return ContentNode[]
     */
    public function getRootDescendants(): array {
        return $this->rootDescendants->getValues();
    }

    public function addRootDescendant(ContentNode $rootDescendant): self {
        if (!$this->rootDescendants->contains($rootDescendant)) {
            $this->rootDescendants[] = $rootDescendant;
            $rootDescendant->root = $this;
        }

        return $this;
    }

    public function removeRootDescendant(ContentNode $rootDescendant): self {
        if ($this->rootDescendants->removeElement($rootDescendant)) {
            // reset the owning side (unless already changed)
            if ($rootDescendant->root === $this) {
                $rootDescendant->root = null;
            }
        }

        return $this;
    }

    /**
     * @return ContentNode[]
     */
    public function getChildren(): array {
        return $this->children->getValues();
    }

    public function addChild(self $child): self {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->parent = $this;
        }

        return $this;
    }

    public function removeChild(self $child): self {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->parent === $this) {
                $child->parent = null;
            }
        }

        return $this;
    }

    /**
     * @param ContentNode $prototype
     * @param EntityMap   $entityMap
     */
    public function copyFromPrototype($prototype, $entityMap): void {
        $entityMap->add($prototype, $this);

        // copy ContentNode base properties
        $this->contentType = $prototype->contentType;
        $this->instanceName = $prototype->instanceName;
        $this->slot = $prototype->slot;
        $this->position = $prototype->position;
        $this->data = $prototype->data;

        // deep copy children
        foreach ($prototype->getChildren() as $childPrototype) {
            $childClass = $this->getObjectClass($childPrototype);

            /** @var ContentNode $childContentNode */
            $childContentNode = new $childClass();

            $this->addChild($childContentNode);
            $this->root->addRootDescendant($childContentNode);

            $childContentNode->copyFromPrototype($childPrototype, $entityMap);
        }
    }

    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):.
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @return array
     *
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function array_merge_recursive_distinct(array &$array1, array &$array2) {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
