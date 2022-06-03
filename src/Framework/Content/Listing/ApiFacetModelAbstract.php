<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\LoadPropertiesTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorFacetModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Facet;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseHydratorTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\AccessorHandlerInterface;

/**
 * Class ApiFacetModel
 *
 * Item refers to any data model/logic that is desired to be rendered/displayed
 * The integrator can decide to either use all data as provided by the Narrative API,
 * or to design custom data layers to represent the fetched content
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Content
 */
abstract class ApiFacetModelAbstract implements AccessorFacetModelInterface
{
    use ResponseHydratorTrait;
    use LoadPropertiesTrait;

    /**
     * @var \ArrayIterator
     */
    protected $facets;

    /**
     * @var string
     */
    protected $facetPrefix = null;

    /**
     * @var \ArrayIterator
     */
    protected $selectedFacets;

    /**
     * @var \ArrayObject
     */
    protected $positionFacets;

    /**
     * @var AccessorHandlerInterface
     */
    protected $accessorHandler;

    /**
     * @var null | string
     */
    protected $defaultLanguageId = null;

    public function __construct()
    {
        $this->positionFacets = new \ArrayObject();
        $this->selectedFacets = new \ArrayIterator();
    }

    /**
     * Accessing translation for the property name from DB
     *
     * @param string $propertyName
     * @return string
     */
    abstract public function getLabel(string $propertyName) : string;

    /**
     * @return \ArrayIterator
     */
    public function getFacets() :  \ArrayIterator
    {
        return $this->facets;
    }

    /**
     * @return \ArrayIterator
     */
    public function getSelectedFacets() : \ArrayIterator
    {
        return $this->selectedFacets;
    }

    /**
     * Accessing the facets list based on the facet configured position
     *
     * @param string $position
     * @return \ArrayIterator
     */
    public function getByPosition(string $position) : \ArrayIterator
    {
        if($this->positionFacets->offsetExists($position))
        {
            return $this->positionFacets->offsetGet($position);
        }
        /** @var Facet $facet */
        $facets = array_filter(array_map(function(AccessorInterface $facet) use ($position) {
            if($facet->getPosition() === $position)
            {
                return $facet;
            }
        }, $this->getFacets()->getArrayCopy()));

        $this->positionFacets->offsetSet($position, new \ArrayIterator($facets));

        return $this->positionFacets->offsetGet($position);
    }

    /**
     * @param array $facets
     * @return $this
     */
    public function setFacets(array $facets) : self
    {
        $this->facets = new \ArrayIterator();
        foreach($facets as $facet)
        {
            $facet = $this->toObject($facet, $this->getAccessorHandler()->getAccessor("facet"));
            if($this->facetRequiresPrefix($facet))
            {
                $facet->setFieldPrefix($this->getFacetPrefix());
            }
            $label = $facet->getLabel();
            if(!$label || empty($label))
            {
                $facet->setLabel($this->getLabel($facet->getField()));
            }

            $this->facets->append($facet);
            if($facet->isSelected() || count($facet->getSelectedValues()))
            {
                $this->addSelectedFacet($facet);
            }
        }

        return $this;
    }

    /**
     * Check that the facet is a store/e-shop property
     * If it is not a store property - the prefix must be added
     *
     * @param $facet
     * @return bool
     */
    abstract protected function facetRequiresPrefix($facet) : bool;

    /**
     * @param AccessorInterface $facet
     * @return $this
     */
    public function addSelectedFacet(AccessorInterface $facet) : self
    {
        $this->selectedFacets->append($facet);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSelectedFacets() : bool
    {
        return (bool) $this->selectedFacets->count();
    }

    /**
     * @return bool
     */
    public function hasFacets() : bool
    {
        return (bool) $this->facets->count();
    }

    /**
     * Sets the facets
     * Sets the accessor handler to be able to run toObject construct
     *
     * @param null | AccessorInterface $context
     * @return AccessorModelInterface
     */
    public function addAccessorContext(?AccessorInterface $context = null): AccessorModelInterface
    {
        $this->setAccessorHandler($context->getAccessorHandler());
        $this->setFacets($context->getBxFacets());
        return $this;
    }

    /**
     * @return AccessorHandlerInterface
     */
    public function getAccessorHandler(): AccessorHandlerInterface
    {
        return $this->accessorHandler;
    }

    /**
     * @param AccessorHandlerInterface $accessorHandler
     * @return $this
     */
    public function setAccessorHandler(AccessorHandlerInterface $accessorHandler)
    {
        $this->accessorHandler = $accessorHandler;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getFacetPrefix(): ?string
    {
        return $this->facetPrefix;
    }

    /**
     * @param string $facetPrefix
     */
    public function setFacetPrefix(string $facetPrefix): void
    {
        $this->facetPrefix = $facetPrefix;
    }

    /**
     * Preparing element for API preview (ex: pwa context)
     * Call the _loadAccessors() in order to apply the propper content setters & getters on the facet options
     * Use camelcase to reuse the API properties
     */
    public function load(): void
    {
        $this->_loadAccessors();
        $this->loadPropertiesToObject($this, [], ["getLabel", "addSelectedFacet", "getByPosition", "getFacetsPrefix", "_loadAccessors", "facetRequiresPrefix"], true);
    }

    /**
     * @return \ArrayIterator
     */
    protected function _loadAccessors() : void
    {
        foreach($this->getFacets() as $facet)
        {
            $facet->load();
        }

        foreach($this->getSelectedFacets() as $facet)
        {
            $facet->load();
        }
    }

}
