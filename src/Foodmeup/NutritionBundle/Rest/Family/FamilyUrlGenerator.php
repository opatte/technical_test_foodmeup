<?php

namespace Foodmeup\NutritionBundle\Rest\Family;

use Doctrine\Common\Collections\ArrayCollection;

use Hateoas\Configuration\Exclusion;
use Hateoas\Configuration as Hateoas;
use Hateoas\UrlGenerator\UrlGeneratorInterface;
use Hateoas\Configuration\Provider\RelationProviderInterface;

use Symfony\Component\HttpFoundation\RequestStack;

use Foodmeup\NutritionBundle\Entity\Family\Family;
use Foodmeup\NutritionBundle\Entity\Family\FamilyContent;
use Foodmeup\NutritionBundle\Query\Family\ListAllFamiliesQuery;

/**
 * Class FamilyUrlGenerator.
 */
class FamilyUrlGenerator implements UrlGeneratorInterface, RelationProviderInterface
{
    /**
     * @var ListAllFamiliesQuery
     */
    private $query;

    /**
     * FamilyUrlGenerator constructor.
     *
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->requestToQuery($request);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return string
     */
    public function generate($name, array $parameters, $absolute = false)
    {
        return '';
    }

    /**
     * @param object $object
     *
     * @return array
     */
    public function getRelations($object)
    {
        $relations = [];
        if ($object instanceof Family) {
            $relations = [new Hateoas\Relation('self', null, ['uuid' => $object->getUuid()])];

            // Add the "contents" relation
            $relations[] = new Hateoas\Relation(
                'contents', null, $this->getContents($object), [], $this->getExclusionContent()
            );
        }

        return $relations;
    }

    /**
     * @return Exclusion
     */
    public function getExclusionContent()
    {
        $excluded = false;
        if (!array_key_exists('content', $this->query->getEmbeddeds())) {
            $excluded = true;
        }

        return  new Exclusion(null, null, null, null, $excluded);
    }

    /**
     * @param Family $object
     *
     * @return array|ArrayCollection
     */
    private function getContents(Family $object)
    {
        $contentsView = [];
        $embeddedQuery = $this->query->getEmbeddeds();
        if (!array_key_exists('content', $embeddedQuery)) {
            return [];
        }
        if (count(array_filter($embeddedQuery['content'])) < 1) {
            return $object->getContents();
        }
        foreach ($object->getContents() as $content) {
            $contentsView[] = $this->mapContentFromQuery($content);
        }

        return new ArrayCollection($contentsView);
    }

    /**
     * @param FamilyContent $content
     *
     * @return array|FamilyContent
     */
    private function mapContentFromQuery(FamilyContent $content)
    {
        $contentView = [];
        $embeddedQuery = $this->query->getEmbeddeds();
        if (array_key_exists('content', $embeddedQuery)) {
            if (in_array('uuid', $embeddedQuery['content'])) {
                $contentView['uuid'] = $content->getUuid();
            }
            if (in_array('lang', $embeddedQuery['content'])) {
                $contentView['lang'] = $content->getLang();
            }
            if (in_array('slug', $embeddedQuery['content'])) {
                $contentView['slug'] = $content->getSlug();
            }
            if (in_array('name', $embeddedQuery['content'])) {
                $contentView['name'] = $content->getName();
            }
        }

        return $contentView;
    }

    /**
     * @param RequestStack $request
     *
     * @return null|bool
     */
    private function requestToQuery(RequestStack $request)
    {
        $this->query = new ListAllFamiliesQuery();

        if ($request->getCurrentRequest() == null) {
            return false;
        }

        $this->query->setEmbeddedsFromGetRequest($request->getCurrentRequest()->query->get('embedded'));
    }
}
