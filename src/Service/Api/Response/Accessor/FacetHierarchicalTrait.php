<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

/**
 * Class Facet
 *
 * Boxalino API facet model
 * The properties defined are a base on what can be configured in Boxalino Intelligence Admin
 * The rest of the properties (custom defined) can be accessed directly
 *
 * Sample of hierarchical facet: categories
 * a) identify the 1st parent with more than 1 children & show all kids
 * b) identifies the selected facet & shows only it`s kids
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
trait FacetHierarchicalTrait
{

    /**
     * @var \ArrayIterator
     */
    protected $apiValues;

    /**
     * @var bool
     */
    protected $noHighlightedFound = true;

    /**
     * @param array $values
     * @return Facet
     */
    protected function _setValuesHierarchical(array $values): Facet
    {
        $this->values = new \ArrayIterator();
        $this->apiValues = new \ArrayIterator();

        foreach ($values as $index => $value)
        {
            $value->path = [];
            $this->_buildTree($value, 0);
        }

        $this->_createLevelWithValidChildren();
        return $this;
    }

    /**
     * @param $value
     * @param int $level
     * @param string|null $parentId
     * @return void
     */
    protected function _buildTree($value, int $level, ?string $parentId = null)
    {
        $value->label = [$value->label];
        if($level > 1)
        {
            $value->path[] = $value->label[0];
        }

        $this->_createFacetValueEntity($value, $level, $parentId);
        if(count($value->children))
        {
            $level++;
            foreach($value->children as $index => $child)
            {
                $child->path = $value->path;
                $this->_buildTree($child, $level, $value->value);
            }
        }
    }

    /**
     * @param $value
     * @param $level
     * @param $parentId
     * @return void
     */
    protected function _createFacetValueEntity($value, int $level, ?string $parentId = null) : void
    {
        /** only create facetValueEntities excluding the first 2 parents from hierarchy (ex: root & website level) */
        if($level > 1)
        {
            /** @var FacetValue $facetValueEntity */
            $facetValueEntity = $this->toObject($value, $this->getAccessorHandler()->getAccessor("facetValue"));
            $facetValueEntity->setId([$facetValueEntity->getValue()]);
            $facetValueEntity->set("level", (string)$level);
            $facetValueEntity->set("parent", (string)$parentId);

            $this->apiValues->append($facetValueEntity);
        }
    }

    /**
     * 1. If there is a selected facet option -> show the children from it only
     * 2. If there is no selected facet option:
     * 2.a Show only the highlighted values
     * 2.b show the lowelest level with more than one kid
     *
     * @return void
     */
    protected function _createLevelWithValidChildren() : void
    {
        $facetValues = [];
        array_map(function(AccessorInterface $facetValue) use (&$facetValues) {
            $facetValues[$facetValue->getLevel()][] = $facetValue->getValue();
            if($facetValue->isSelected())
            {
                $facetValues["selected"] = $facetValue->getValue();
            }
            if($facetValue->isHighlighted())
            {
                $facetValues["highlighted"] = true;
            }
        }, $this->getApiValues()->getArrayCopy());

        if(isset($facetValues["highlighted"]))
        {
            $this->noHighlightedFound = false;
            $this->_setChildrenByRules(["highlighted" => true]);
            return;
        }

        if(isset($facetValues["selected"]))
        {
            $this->_setChildrenByRules([
                    "parent" => $facetValues["selected"],
                    "value" => $facetValues["selected"]]
            );
            return;
        }

        $this->_setChildren("level", $this->_getLevel($facetValues));
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
        if(in_array($sortOption, ["natural","counter","alphabetical", "alphabetical_desc"]))
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

            if($sortOption == "counter")
            {
                krsort($facetValuesByKey, SORT_NUMERIC);
            }

            if(in_array($sortOption, ["natural","alphabetical"]))
            {
                ksort($facetValuesByKey, SORT_NATURAL);
            }

            if(in_array($sortOption, ["alphabetical_desc"]))
            {
                krsort($facetValuesByKey, SORT_NATURAL);
            }
            $index = 0;
            foreach($facetValuesByKey as $key => $facetValue)
            {
                $index++;
                $facetValue->setShow($facetValue->isHighlighted());

                if($this->noHighlightedFound)
                {
                    if($this->_getFromData("enumDisplaySize") && $index < $this->_getFromData("enumDisplaySize"))
                    {
                        $facetValue->setShow(true);
                    }

                    if($this->_getFromData("enumDisplayMaxSize") && $index < $this->_getFromData("enumDisplayMaxSize"))
                    {
                        $facetValue->setShow(true);
                    }
                }
                $sortedValues->append($facetValue);
            }

            $this->values = $sortedValues;
        }
    }

    /**
     * @param array $valueRules
     * @return void
     */
    protected function _setChildrenByRules(array $valueRules) : void
    {
        $values = new \ArrayIterator();
        foreach($valueRules as $property => $value)
        {
            $this->_addFacetValueToCollection($values, $property, $value);
        }

        $this->values = $values;
    }

    /**
     * @param string $property
     * @param string $value
     * @return void
     */
    protected function _setChildren(string $property, string $value) : void
    {
        $values = new \ArrayIterator();
        $this->_addFacetValueToCollection($values, $property, $value);

        $this->values = $values;
    }

    /**
     * @param \ArrayIterator $values
     * @param string $property
     * @param string | bool | int $value
     * @return void
     */
    protected function _addFacetValueToCollection(\ArrayIterator &$values, string $property, $value) : void
    {
        foreach($this->getApiValues() as $facetValue)
        {
            if($facetValue->get($property) === $value)
            {
                $values->append($facetValue);
            }
        }
    }

    /**
     * @param array $list
     * @return string
     */
    protected function _getLevel(array $list) : string
    {
        ksort($list, SORT_NATURAL);
        $levels = [];
        array_map(function ($level, $values) use (&$levels){
            if(is_array($values) && count($values) > 1)
            {
                $levels[$level] = count($values);
            }
        }, array_keys($list), $list);

        reset($levels);

        return (string)key($levels);
    }

    /**
     * @return \ArrayIterator
     */
    public function getApiValues(): \ArrayIterator
    {
        return $this->apiValues;
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
