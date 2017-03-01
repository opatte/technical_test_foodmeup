<?php

namespace Foodmeup\NutritionBundle\Query;

/**
 * Class BaseQueryListAll.
 */
class BaseQueryListAll
{
    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var string
     */
    protected $sort = null;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $embeddeds = [];

    /**
     * @var array
     */
    protected $filtersAllowed = [];

    /**
     * @var array
     */
    protected $limitsAllowed = [];

    /**
     * @var array
     */
    protected $fieldsAllowed = [];

    /**
     * @var array
     */
    protected $embeddedsAllowed = [];

    /**
     * @var array
     */
    protected $sortsAllowed = [];

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        if ($limit != null) {
            $value = (int) $limit;

            if (!is_int($value) || $value <= 0 || !in_array($value, $this->limitsAllowed)) {
                throw new \DomainException('The value of this limit is not authorized', 400);
            }

            $this->limit = $value;
        }
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        if ($page != null) {
            $value = (int) $page;

            if (!is_int($value) || $value <= 0) {
                throw new \DomainException('The value of this page is not authorized', 400);
            }

            $this->page = $value;
        }
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set sort
     * > Format allowed: $sort = <FIELD>[(space)<ASC|DESC>]
     * > Example: updated ASC.
     *
     * @param string $sort
     */
    public function setSort($sort)
    {
        if ($sort != null) {
            $values = explode(' ', $sort);

            // Checks if the sort passed is a value authorized
            if (!in_array($values[0], $this->sortsAllowed)) {
                throw new \DomainException('The value of this sort is not authorized', 400);
            }

            // Checks if the direction passed is a value authorized
            if (isset($values[1]) === true) {
                if (!in_array($values[1], ['ASC', 'DESC'])) {
                    throw new \DomainException('The value of this direction is not authorized', 400);
                }
            }

            $this->sort = $sort;
        }
    }

    /**
     * @return array
     */
    public function getFiltersAllowed()
    {
        return $this->filtersAllowed;
    }

    /**
     * @return array
     */
    public function getLimitsAllowed()
    {
        return $this->limitsAllowed;
    }

    /**
     * @return array
     */
    public function getSortsAllowed()
    {
        return $this->limitsAllowed;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Add the filters.
     *
     * @param array $filters
     */
    public function addFilters(array $filters)
    {
        foreach ($filters as $filter => $value) {
            $this->addFilter($filter, $value);
        }
    }

    /**
     * Add the filter.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function addFilter($name, $value)
    {
        if (in_array($name, $this->filtersAllowed) === true) {
            $this->filters[$name] = $value;
        }
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     *
     * @return BaseQueryListAll
     */
    public function setFields($fields)
    {
        $result = [];
        if ($fields != null) {
            $fieldsList = explode(',', $fields);
            foreach ($fieldsList as $value) {
                if (in_array($value, $this->fieldsAllowed)) {
                    $result[] = $value;
                }
            }
        }
        $this->fields = $result;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmbeddeds()
    {
        return $this->embeddeds;
    }

    /**
     * Example embedded: ?embedded[content]=slug,lang,uuid.
     *
     * @param array $embeddeds
     *
     * @return $this
     */
    public function setEmbeddeds(array $embeddeds)
    {
        $this->embeddeds = $this->embeddedsToEmbeddedsAllowed($embeddeds);

        return $this;
    }

    /**
     * @param array|null $embeddeds
     *
     * @return $this
     */
    public function setEmbeddedsFromGetRequest($embeddeds)
    {
        $embeddeds = $this->convertEmbeddedToRealArray($embeddeds);
        $this->setEmbeddeds($embeddeds);

        return $this;
    }

    /**
     * @param array $embeddeds
     *
     * @return array
     */
    public function embeddedsToEmbeddedsAllowed(array $embeddeds)
    {
        $result = [];
        if ($embeddeds != null && is_array($embeddeds) && count($embeddeds) > 0) {
            foreach ($embeddeds as $key => $embedded) {
                if (!array_key_exists($key, $this->embeddedsAllowed)) {
                    continue;
                }
                foreach ($embedded as $property) {
                    if (!in_array($property, $this->embeddedsAllowed[$key])) {
                        continue;
                    }
                    $result[$key][] = $property;
                }
            }
        }

        return $result;
    }

    /**
     * Convert embedded from url to array multidimensional.
     * Example embedded: ?embedded[content]=slug,lang,uuid.
     *
     * @param array|null $embeddeds
     *
     * @return array
     */
    public function convertEmbeddedToRealArray($embeddeds)
    {
        $result = [];
        if ($embeddeds != null && is_array($embeddeds) && count($embeddeds) > 0) {
            foreach ($embeddeds as $key => $value) {
                if (!is_string($value)) {
                    continue;
                }
                $propertiesEmbedded = explode(',', str_replace(' ', '', $value));
                $result[$key] = $propertiesEmbedded;
            }
        }

        return $result;
    }
}
