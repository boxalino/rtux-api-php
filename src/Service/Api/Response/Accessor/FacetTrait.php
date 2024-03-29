<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

/**
 * Trait FacetTrait
 *
 * Boxalino API facet model
 * The properties defined are a base on what can be configured in Boxalino Intelligence Admin
 * The rest of the properties (custom defined) can be accessed directly
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
trait FacetTrait
{

    /**
     * @param array $values
     * @return $this
     */
    protected function _setValues(array $values) : Facet
    {
        $this->values = new \ArrayIterator();
        foreach ($values as $index => $value) {
            /** @var FacetValue $facetValueEntity */
            $facetValueEntity = $this->toObject($value, $this->getAccessorHandler()->getAccessor("facetValue"));
            $facetValueEntity->setShow($this->_defineShow((int) $index, count($values)));

            $this->values->append($facetValueEntity);
        }

        return $this;
    }

    /**
     * Apply a natural sort for the facet
     *
     * @return void
     */
    protected function _natsortFacetValues() : void
    {
        $sortedValues = new \ArrayIterator();
        /** add a natsort for facet values */
        if($this->getValueorderEnums() === "natural")
        {
            $facetValuesByKey = [];
            array_map(function(AccessorInterface $facetValue) use (&$facetValuesByKey) {
                $facetValuesByKey[$facetValue->getValue()] = $facetValue;
            }, $this->getValues()->getArrayCopy());

            ksort($facetValuesByKey, SORT_NATURAL);
            $index=0;
            foreach($facetValuesByKey as $key => $facetValue)
            {
                $index++;
                $facetValue->setShow($this->_defineShow($index, $this->getValues()->count()));

                $sortedValues->append($facetValue);
            }

            $this->values = $sortedValues;
        }
    }

    /**
     * Sort facet options based on the configured sort_order key (if provided in the doc_attribute_value export)
     * NOTE: If there is no sort_order configured, the facet options will be added at the end of the list
     *
     * @return void
     */
    protected function _storeSortFacetValues() : void
    {
        $sortedValues = new \ArrayIterator();
        /** sort based on store_order attribute */
        if($this->getValueorderEnums() === "store")
        {
            $facetValuesByKey = []; $facetValuesNoKey = [];
            array_map(function(AccessorInterface $facetValue) use (&$facetValuesByKey, &$facetValuesNoKey) {
                if($facetValue->get("sort_order"))
                {
                    $sortOrder = $facetValue->get("sort_order")[0];
                    if(is_null($sortOrder))
                    {
                        $facetValuesNoKey[] = $facetValue;
                        return;
                    }
                    $facetValuesByKey[$sortOrder] = $facetValue;
                }
            }, $this->getValues()->getArrayCopy());

            if(count($facetValuesByKey))
            {
                ksort($facetValuesByKey, SORT_NUMERIC);
                $index = 0;
                foreach($facetValuesByKey as $key => $facetValue)
                {
                    $index++;
                    $facetValue->setShow($this->_defineShow($index, $this->getValues()->count()));

                    $sortedValues->append($facetValue);
                }

                foreach($facetValuesNoKey as $facetValue)
                {
                    $index++;
                    $facetValue->setShow($this->_defineShow($index, $this->getValues()->count()));

                    $sortedValues->append($facetValue);
                }

                $this->values = $sortedValues;
            }
        }
    }

    /**
     * @param int $index
     * @param int $totalFacetOptions
     * @return bool
     */
    protected function _defineShow(int $index, int $totalFacetOptions) : bool
    {
        if($this->_getFromData("enumDisplaySize") && $index > $this->_getFromData("enumDisplaySize"))
        {
            if($this->_getFromData("enumDisplayMaxSize"))
            {
                if ($totalFacetOptions > $this->_getFromData("enumDisplayMaxSize"))
                {
                    return false;
                }

                return true;
            }

            return false;
        }

        if($this->_getFromData("enumDisplayMaxSize") && $index > $this->_getFromData("enumDisplayMaxSize"))
        {
            return false;
        }

        return true;
    }


}
