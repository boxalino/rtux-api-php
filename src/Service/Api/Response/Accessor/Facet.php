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
class Facet extends Accessor
    implements AccessorInterface
{

    use FacetTrait;
    use FacetHierarchicalTrait;

    public const RTUX_API_FACET_CATEGORIES = "categories";
    public const RTUX_API_FACET_CATEGORY = "category_id";

    /**
     * @var string
     */
    protected $field;

    /**
     * @var null | string
     */
    protected $label = null;

    /**
     * Facet values
     * @var \ArrayIterator
     */
    protected $values;

    /**
     * @var bool
     */
    protected $isAndSelectedValues = false;

    /**
     * @var bool
     */
    protected $isBoundsOnly = false;

    /**
     * Numerical facet
     *
     * @var bool
     */
    protected $isNumerical = false;

    /**
     * Range facet (ex: price)
     *
     * @var bool
     */
    protected $isRange = false;

    /**
     * @var int
     */
    protected $sortCode = 1;

    /**
     * The order of the facet values in the view
     * As selected from the Boxalino Intelligence Admin Merchandising >> Facets
     *
     * @var string
     * alphabetical | counter | custom | 2 (store system order) | natural
     */
    protected $valueorderEnums = null;

    /**
     * @var bool
     */
    protected $finderFacet = false;

    /**
     * Front-End visualisation (display/template) of the facet
     * as selected from the Boxalino Intelligence Admin Merchandising >> Facets
     * @var string
     *
     * enumeration | multiselect | range | radio | slider | pushbutton | switch | dropdown
     * | multiselect-dropdown | search | stars | colorpicker | datepicker | sizepicker | checkbox | genderpicker
     * | thumbs | textarea | textfield
     */
    protected $visualisation = null;

    /**
     * @var string (hidden |  expanded  | collapsed)
     */
    protected $display = null;

    /**
     * @var string ( top | inlist | only )
     */
    protected $displaySelectedValues = null;

    /**
     * Display number of products matching each facet value or not
     * @var bool
     */
    protected $showCounter = true;

    /**
     * If set, only the <enumDisplaySize> nr of facet value will be displayed, the other would appear under a link 'see other values'
     *
     * @var null | int
     */
    protected $enumDisplaySize = null;

    /**
     * Same as <enumDisplaySize>
     * Except it sets the maximum facet values to be returned
     *
     * @var null | int
     */
    protected $enumDisplayMaxSize = null;

    /**
     * If set, it will only return the facet if the total product coverage of the facet values reaches the limit
     *
     * @var null | float
     */
    protected $minDisplayCoverage = null;

    /**
     * @var bool
     */
    protected $sortAscending = false;

    /**
     * @var array|AccessorInterface[]
     */
    protected $selectedValues = null;

    /**
     * @var bool
     */
    protected $selected = false;

    /**
     * If configured, facet position (left, top, bottom, right)
     * Otherwise - not part of response
     *
     * @var string | null
     */
    protected $position = null;

    /**
     * @var string | null
     */
    protected $requestField = null;

    /**
     * @var bool
     */
    protected $allowMultiselect = false;

    /**
     * @var string
     */
    protected $rangeFromField;

    /**
     * @var string
     */
    protected $rangeToField;

    /**
     * @var string | null
     */
    protected $fieldPrefix;

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return Facet
     */
    public function setField(string $field): Facet
    {
        $this->field = $this->_apiMapField($field);
        return $this;
    }

    /**
     * @return string | null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string | null $label
     * @return Facet
     */
    public function setLabel(?string $label): Facet
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getValues(): \ArrayIterator
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return Facet
     */
    public function setValues(array $values): Facet
    {
        if($this->getField() === self::RTUX_API_FACET_CATEGORY)
        {
            return $this->_setValuesHierarchical($values);
        }

        return $this->_setValues($values);
    }

    /**
     * @return bool
     */
    public function isAndSelectedValues(): bool
    {
        return $this->isAndSelectedValues;
    }

    /**
     * @param bool $isAndSelectedValues
     * @return Facet
     */
    public function setIsAndSelectedValues(bool $isAndSelectedValues): Facet
    {
        $this->isAndSelectedValues = $isAndSelectedValues;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBoundsOnly(): bool
    {
        return $this->isBoundsOnly;
    }

    /**
     * @param bool $isBoundsOnly
     * @return Facet
     */
    public function setIsBoundsOnly(bool $isBoundsOnly): Facet
    {
        $this->isBoundsOnly = $isBoundsOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNumerical(): bool
    {
        return $this->isNumerical;
    }

    /**
     * @param bool $isNumerical
     * @return Facet
     */
    public function setIsNumerical(bool $isNumerical): Facet
    {
        $this->isNumerical = $isNumerical;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRange(): bool
    {
        return $this->isRange;
    }

    /**
     * @param bool $isRange
     * @return Facet
     */
    public function setIsRange(bool $isRange): Facet
    {
        $this->isRange = $isRange;
        return $this;
    }

    /**
     * @return int | null
     */
    public function getSortCode(): ?int
    {
        return $this->sortCode;
    }

    /**
     * @param int | null $sortCode
     * @return Facet
     */
    public function setSortCode(?int $sortCode): Facet
    {
        $this->sortCode = $sortCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getValueorderEnums(): ?string
    {
        return $this->valueorderEnums;
    }

    /**
     * @param string $valueorderEnums
     * @return Facet
     */
    public function setValueorderEnums(string $valueorderEnums): Facet
    {
        $this->valueorderEnums = $valueorderEnums;

        if($this->getField() === self::RTUX_API_FACET_CATEGORY)
        {
            $this->_sortHierarchicalFacetValues();
            return $this;
        }

        $this->_natsortFacetValues();
        $this->_storeSortFacetValues();

        return $this;
    }

    /**
     * @return bool
     */
    public function isFinderFacet(): bool
    {
        return $this->finderFacet;
    }

    /**
     * @param bool $finderFacet
     * @return Facet
     */
    public function setFinderFacet(bool $finderFacet): Facet
    {
        $this->finderFacet = $finderFacet;
        return $this;
    }

    /**
     * @return string
     */
    public function getVisualisation(): ?string
    {
        return $this->visualisation;
    }

    /**
     * @param string | null $visualisation
     * @return Facet
     */
    public function setVisualisation(?string $visualisation): Facet
    {
        $this->visualisation = $visualisation;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplay(): ?string
    {
        return $this->display;
    }

    /**
     * @param string | null $display
     * @return Facet
     */
    public function setDisplay(?string $display): Facet
    {
        $this->display = $display;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplaySelectedValues(): ?string
    {
        return $this->displaySelectedValues;
    }

    /**
     * @param string | null $displaySelectedValues
     * @return Facet
     */
    public function setDisplaySelectedValues(?string $displaySelectedValues): Facet
    {
        $this->displaySelectedValues = $displaySelectedValues;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowCounter(): bool
    {
        return $this->showCounter;
    }

    /**
     * @param string | null $showCounter
     * @return Facet
     */
    public function setShowCounter(?string $showCounter): Facet
    {
        $this->showCounter = $showCounter === "true" ;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEnumDisplaySize(): ?int
    {
        return $this->enumDisplaySize;
    }

    /**
     * @param int|null $enumDisplaySize
     * @return Facet
     */
    public function setEnumDisplaySize(?int $enumDisplaySize): Facet
    {
        $this->enumDisplaySize = $enumDisplaySize;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEnumDisplayMaxSize(): ?int
    {
        return $this->enumDisplayMaxSize;
    }

    /**
     * @param int|null $enumDisplayMaxSize
     * @return Facet
     */
    public function setEnumDisplayMaxSize(?int $enumDisplayMaxSize): Facet
    {
        $this->enumDisplayMaxSize = $enumDisplayMaxSize;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getMinDisplayCoverage(): ?float
    {
        return $this->minDisplayCoverage;
    }

    /**
     * @param float|null $minDisplayCoverage
     * @return Facet
     */
    public function setMinDisplayCoverage(?float $minDisplayCoverage): Facet
    {
        $this->minDisplayCoverage = $minDisplayCoverage;
        return $this;
    }

    /**
     * @return bool | null
     */
    public function isSortAscending(): ?bool
    {
        return $this->sortAscending;
    }

    /**
     * @param bool | null $sortAscending
     * @return Facet
     */
    public function setSortAscending(?bool $sortAscending): Facet
    {
        $this->sortAscending = $sortAscending;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     * @return Facet
     */
    public function setSelected(bool $selected): Facet
    {
        $this->selected = $selected;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getPosition() : ?string
    {
        return $this->position;
    }

    /**
     * @param string | null $position
     * @return Facet
     */
    public function setPosition(?string $position) : Facet
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Flag if the facet is configured to allow multiple selected values
     *
     * @return bool | null
     */
    public function allowMultiselect() : ?bool
    {
        return $this->allowMultiselect;
    }

    /**
     * @param string | null $allow
     * @return $this
     */
    public function setAllowMultiselect(?string $allow) : Facet
    {
        $this->allowMultiselect = $allow == 'true';
        return $this;
    }

    /**
     * In case of range facets, range-from field can be configured
     * (further used for facetValue remove/select URLs)
     *
     * @return string | null
     */
    public function getRangeFromField(): ?string
    {
        return $this->rangeFromField;
    }

    /**
     * @param string | null $rangeFromField
     * @return Facet
     */
    public function setRangeFromField(?string $rangeFromField): Facet
    {
        $this->rangeFromField = $rangeFromField;
        return $this;
    }

    /**
     * In case of range facets, range-to field can be configured
     * (further used for facetValue remove/select URLs)
     *
     * @return string | null
     */
    public function getRangeToField(): ?string
    {
        return $this->rangeToField;
    }

    /**
     * @param string | null $rangeToField
     * @return Facet
     */
    public function setRangeToField(?string $rangeToField): Facet
    {
        $this->rangeToField = $rangeToField;
        return $this;
    }

    /**
     * Get request variable name which is used for apply filter
     * For ex: "products_" can be removed, fields renamed, etc
     *
     * @return string
     */
    public function getRequestField() : string
    {
        if(is_null($this->requestField))
        {
            $this->requestField = $this->getFieldPrefix() . $this->getField();
        }

        return $this->requestField ;
    }

    /**
     * Set the name of the facet as is to appear in the URL
     *
     * @param string $field
     */
    public function setRequestField(string $field) : Facet
    {
        $this->requestField = $field;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getFieldPrefix() : ?string
    {
        return $this->fieldPrefix;
    }

    /**
     * @param string | null $prefix
     * @return $this
     */
    public function setFieldPrefix(?string $prefix = null) : Facet
    {
        $this->fieldPrefix = $prefix;
        return $this;
    }

    /**
     * @return array
     */
    public function getSelectedValues() : array
    {
        if(is_null($this->selectedValues))
        {
            $this->selectedValues = array_filter(array_map(function(AccessorInterface $facetValue) {
                if($facetValue->isSelected())
                {
                    return $facetValue;
                }
            }, $this->getValues()->getArrayCopy()));
        }

        return $this->selectedValues;
    }


}
