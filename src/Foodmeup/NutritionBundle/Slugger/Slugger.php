<?php

namespace Foodmeup\NutritionBundle\Slugger;

use Cocur\Slugify\Slugify;
use Foodmeup\NutritionBundle\Repository\RepositorySlugifyInterface;

/**
 * Class Slugger.
 */
class Slugger
{
    /**
     * @var Slugify
     */
    private $slugger;

    /**
     * @var RepositorySlugifyInterface
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param Slugify                    $slugger
     * @param RepositorySlugifyInterface $repository
     */
    public function __construct(Slugify $slugger, RepositorySlugifyInterface $repository)
    {
        $this->slugger = $slugger;
        $this->repository = $repository;
    }

    /**
     * Returns the available slug for this repository.
     *
     * @param string $string    String to slugify
     * @param string $separator Separator
     *
     * @return string Slugified version of the string
     */
    public function slugify($string, $separator = '-')
    {
        $i = 0;
        $slug = $this->slugger->slugify($string, $separator);
        $getOneObjectBySlug = $this->repository->getOneBySlug($slug);

        $newSlug = $slug;
        while ($getOneObjectBySlug != null) {
            $newSlug = $slug.$separator.++$i;

            $getOneObjectBySlug = $this->repository->getOneBySlug($newSlug);
        }

        return $newSlug;
    }
}
