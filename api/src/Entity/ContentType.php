<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Defines a type of content that can be present in a content node tree. A content type
 * determines what data can be stored in content nodes of this type, as well as validation,
 * available slots and jsonConfig settings.
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['categories'])]
#[ORM\Entity]
class ContentType extends BaseEntity {
    /**
     * A name in UpperCamelCase of the content type. This value may be used as a technical
     * identifier of this content type, it is guaranteed to stay fixed.
     */
    #[ApiProperty(writable: false, example: 'SafetyConcept')]
    #[Groups(['read'])]
    #[ORM\Column(type: 'string', length: 32, unique: true)]
    public ?string $name = null;

    /**
     * Whether this content type is still maintained and recommended for use in new camps.
     */
    #[ApiProperty(writable: false, example: 'true')]
    #[Groups(['read'])]
    #[ORM\Column(type: 'boolean')]
    public bool $active = true;

    /**
     * Internal configuration of the content type.
     */
    #[ApiProperty(writable: false)]
    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $jsonConfig = [];

    /**
     * Backlink to category (only used for filtering contentTypes by category).
     * Internal: not published via API.
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'preferredContentTypes')]
    public Collection $categories;

    public function __construct() {
        parent::__construct();
        $this->categories = new ArrayCollection();
    }
}
