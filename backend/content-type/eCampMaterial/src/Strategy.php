<?php

namespace eCamp\ContentType\Material;

use Doctrine\ORM\ORMException;
use eCamp\Core\ContentType\ContentTypeStrategyBase;
use eCamp\Core\Entity\ContentNode;
use eCamp\Core\EntityService\MaterialItemService;
use eCamp\Lib\Acl\NoAccessException;
use eCamp\Lib\Service\ServiceUtils;

class Strategy extends ContentTypeStrategyBase {
    /** @var MaterialItemService */
    private $materialItemService;

    public function __construct(MaterialItemService $materialItemService, ServiceUtils $serviceUtils) {
        parent::__construct($serviceUtils);

        $this->materialItemService = $materialItemService;
    }

    public function contentNodeExtract(ContentNode $contentNode): array {
        return [];
    }

    /**
     * @throws NoAccessException
     * @throws ORMException
     */
    public function contentNodeCreated(ContentNode $contentNode, ?ContentNode $prototype = null): void {
        if (isset($prototype)) {
            // flush ContentNode, required for MaterialItemService
            $this->getServiceUtils()->emPersist($contentNode);
            $this->getServiceUtils()->emFlush();

            $materialItems = $this->materialItemService->fetchAll(['contentNodeId' => $prototype->getId()]);
            foreach ($materialItems as $materialItem) {
                $materialList = $materialItem->getMaterialList();
                $this->materialItemService->create((object) [
                    'contentNodeId' => $contentNode->getId(),
                    'materialListId' => $materialList->getId(),
                    'quantity' => $materialItem->getQuantity(),
                    'unit' => $materialItem->getUnit(),
                    'article' => $materialItem->getArticle(),
                ]);
            }
        }
    }
}
