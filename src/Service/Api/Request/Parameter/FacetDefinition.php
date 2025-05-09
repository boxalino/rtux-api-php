<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterDefinition;

/**
 * Class FacetDefinition
 * Set facets to the request (dependable on the context and custom configurations)
 * A facet is defined by: field, maxCount, minPopulation, sort, sortAscending, andSelectedValues
 *
 * for sorting to be used: 1 = by counter value (population / number of response hits) 2 = by alphabetical order
 *
 * Facets can be with selected values or without
 * They have different definitions
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
#[\AllowDynamicProperties]
class FacetDefinition extends ParameterDefinition
{

    CONST BOXALINO_REQUEST_FACET_SORT_COUNT = "1";
    CONST BOXALINO_REQUEST_FACET_SORT_ALPHABET = "2";
    CONST BOXALINO_REQUEST_FACET_VALUE_CORRELATION_EXTRAINFO = "facetValueExtraInfo";
    CONST BOXALINO_REQUEST_FACET_VALUE_CORRELATION_RTUX = "rtux-data-integration_facetValueExtraInfo";
    CONST BOXALINO_REQUEST_FACET_VALUEKEY = "id";

    /**
     * @param string $field
     * @param array $values
     * @param bool $urlField
     * @param string|null $valueKey
     * @param array $properties
     * @return FacetDefinition
     */
    public function addWithValues(string $field, array $values, bool $urlField = false,
                                  ?string $valueKey = null,
                                  ?string $facetValueCorrelation = self::BOXALINO_REQUEST_FACET_VALUE_CORRELATION_RTUX,
                                  array $properties = []
    ) : self
    {
        $this->field = $field;
        $this->urlField = $urlField;
        $this->values = $values;
        $this->valueKey = $valueKey;
        $this->_addProperties($properties);
        $this->addExtraInfo("facet-value-correlation", $facetValueCorrelation);

        return $this;
    }

    /**
     * @param string $field
     * @param float $from
     * @param float | null $to
     * @param bool $boundsOnly
     * @return FacetDefinition
     */
    public function addRange(string $field, float $from, float $to) : self
    {
        $this->field = $field;
        if($from > 0)
        {
            $this->from = $from;
        }
        if($to > 0)
        {
            $this->to = $to;
        }
        $this->boundsOnly = true;
        $this->numerical = true;
        $this->range = true;
        $this->maxCount = -1;
        $this->minPopulation = 1;

        return $this;
    }

    /**
     * @param string $field
     * @param int $maxCount
     * @param int $minPopulation
     * @param string|null $facetValueCorrelation
     * @param array $properties
     * @param string $sort
     * @param bool $sortAscending
     * @param bool $andSelectedValues
     * @return $this
     */
    public function add(string $field, int $maxCount = -1, int $minPopulation = 1,
                        ?string $facetValueCorrelation = self::BOXALINO_REQUEST_FACET_VALUE_CORRELATION_RTUX,
                        array $properties = [],
                        string $sort = self::BOXALINO_REQUEST_FACET_SORT_COUNT,
                        bool $sortAscending = false,
                        bool $andSelectedValues = false
    ) : self
    {
        $this->field = $field;
        $this->maxCount = $maxCount;
        $this->minPopulation = $minPopulation;
        $this->sort = $sort;
        if(!in_array($sort, [self::BOXALINO_REQUEST_FACET_SORT_ALPHABET, self::BOXALINO_REQUEST_FACET_SORT_COUNT]))
        {
            $this->sort = self::BOXALINO_REQUEST_FACET_SORT_COUNT;
        }
        $this->sortAscending = $sortAscending;
        $this->andSelectedValues = $andSelectedValues;
        $this->_addProperties($properties);
        $this->addExtraInfo("facet-value-correlation", $facetValueCorrelation);

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addExtraInfo(string $key, string $value) : void
    {
        $this->extraInfo[$key] = $value;
    }

    /**
     * @param array $properties
     * @return void
     */
    protected function _addProperties(array $properties) : void
    {
        foreach($properties as $fProp => $pValue)
        {
            if($fProp == 'extra-info')
            {
                foreach($pValue as $extraPropName => $extraPropValue)
                {
                    $this->addExtraInfo($extraPropName, (string)$extraPropValue);
                }

                continue;
            }

            $this->$fProp = (string)$pValue;
        }
    }


}
