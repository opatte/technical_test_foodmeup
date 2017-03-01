<?php

namespace Foodmeup\NutritionBundle\Repository;

/**
 * Interface RepositorySlugifyInterface.
 */
interface RepositorySlugifyInterface
{
    /**
     * @param $slug
     *
     * @return mixed
     */
    public function getOneBySlug($slug);
}
