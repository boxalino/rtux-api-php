<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

/**
 * Class Facet
 *
 * Boxalino API facet model
 * The properties defined are a base on what can be configured in Boxalino Intelligence Admin
 * The rest of the properties (custom defined) can be accessed directly
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
trait FacetHierarchicalTrait
{

    /**
     * @param array $values
     * @return Facet
     */
    protected function _setValuesHierarchical(array $values): Facet
    {
        $this->values = new \ArrayIterator();

        foreach ($values as $index => $value)
        {
            $value->path = [];
            $this->_buildTree($value, 0);
        }

        return $this;
    }


    /**
     * @param array $values
     * @param int $level
     * @return void
     */
    protected function _buildTree($value, int $level)
    {
        $value->label = [$value->label];
        if($level > 1)
        {
            $value->path[] = $value->label[0];
        }

        if(count($value->children))
        {
            $level++;
            foreach($value->children as $index => $child)
            {
                $child->path = $value->path;
                $this->_buildTree($child, $level);
            }

            return;
        }

        /** only create facetValueEntities for the last level of hierarchy */
        if($level > 1)
        {
            /** @var FacetValue $facetValueEntity */
            $facetValueEntity = $this->toObject($value, $this->getAccessorHandler()->getAccessor("facetValue"));
            $facetValueEntity->setId([$facetValueEntity->getValue()]);
            $facetValueEntity->set("level", $level);
            if ($this->getEnumDisplayMaxSize() || $this->getEnumDisplaySize()) {
                if ($index > $this->getEnumDisplaySize() || $index > $this->getEnumDisplayMaxSize()) {
                    $facetValueEntity->setShow(false);
                }
            }

            $this->values->append($facetValueEntity);
        }
    }

    /**
     * Apply a natural sort for the facet
     *
     * @return void
     */
    protected function _sortHierarchicalFacetValues() : void
    {
        $sortOption = $this->getValueorderEnums();
        $sortedValues = new \ArrayIterator();
        /** add a natsort for facet values */
        if(in_array($sortOption, ["natural","counter","alphabetical"]))
        {
            $facetValuesByKey = [];
            array_map(function(AccessorInterface $facetValue) use (&$facetValuesByKey, $sortOption) {
                $key = $facetValue->getLabel();
                if($sortOption === "counter")
                {
                    $key = $facetValue->getHitCount() . "_" . $facetValue->getLabel();
                }
                $facetValuesByKey[$key] = $facetValue;
            }, $this->getValues()->getArrayCopy());

            ksort($facetValuesByKey, SORT_NATURAL);
            foreach($facetValuesByKey as $key => $facetValue)
            {
                $sortedValues->append($facetValue);
            }

            $this->values = $sortedValues;
        }
    }


    /**
     * @param string $field
     * @return string
     */
    protected function _apiMapField(string $field) : string
    {
        if(in_array($field, array_keys($this->_getAlternativeFacetMapping())))
        {
            return $this->_getAlternativeFacetMapping()[$field];
        }

        return $field;
    }

    /**
     * A list of API properties that have alternatives
     * (ex: categories)
     *
     * @return string[]
     */
    protected function _getAlternativeFacetMapping() : array
    {
        return [Facet::RTUX_API_FACET_CATEGORIES => Facet::RTUX_API_FACET_CATEGORY];
    }


}
