<?php

namespace Foodmeup\NutritionBundle\Controller;

use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BaseController.
 */
class BaseController
{
    /**
     * Get the paginated representation associated to the data.
     *
     * @param object $collectionObject resource collection to paginate
     * @param string $relationName     relation name for serializer
     * @param string $xmlElementName   xmlElement name for serializer
     * @param string $messageException
     *
     * @return PaginatedRepresentation
     */
    protected function getPaginatedRepresentation($collectionObject, $relationName, $xmlElementName, $messageException)
    {
        if (null === $collectionObject || empty($collectionObject)) {
            throw new NotFoundHttpException(404, $messageException);
        }

        $route = $collectionObject->getRoute();
        $params = $collectionObject->getParams();
        $page = $collectionObject->getPaginationData()['current'];
        $limit = $collectionObject->getPaginationData()['numItemsPerPage'];
        $nbrPages = $collectionObject->getPaginationData()['pageCount'];
        $totalItems = $collectionObject->getTotalItemCount();

        return new PaginatedRepresentation(new CollectionRepresentation($collectionObject, $relationName, $xmlElementName),
            $route, $params, $page, $limit, $nbrPages, null, null, false, $totalItems);
    }
}
