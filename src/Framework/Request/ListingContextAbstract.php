<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Context\ListingContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\FacetDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;

/**
 * Boxalino Listing Request handler
 * Allows to set the nr of subphrases and products returned on each subphrase hit
 *
 * @package Boxalino\RealTimeUserExperienceApi\Framework\Request
 */
abstract class ListingContextAbstract
    extends ContextAbstract
    implements ListingContextInterface
{

    /**
     * Adding  facets from the request
     *
     * @param RequestInterface $request
     * @return RequestDefinitionInterface
     */
    public function get(RequestInterface $request) : RequestDefinitionInterface
    {
        parent::get($request);
        $this->addFacets($request);
        $this->addRangeFacets($request);
        $this->addCategoryFacet($request);

        return $this->getApiRequest();
    }

    /**
     * Filter the list of query parameters by either being a product property or a defined property used in filter
     *
     * @param RequestInterface $request
     * @return SearchContextAbstract
     */
    public function addFacets(RequestInterface $request): ListingContextAbstract
    {
        foreach($request->getParams() as $param => $values)
        {
            if (in_array($param, array_column($this->getRangeProperties(), "from")))
            {
                continue;
            }

            if(in_array($param, $this->getSystemParameters()))
            {
                continue;
            }

            //it`s a store property or has the allowed filters prefix
            if($this->isParamAllowedAsFilter((string)$param))
            {
                $values = is_array($values) ? $values : explode($this->getFilterValuesDelimiter(), $values);
                $values = array_map("html_entity_decode", $values);
                $this->getApiRequest()->addFacets(
                    $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                        ->addWithValues($this->getPropertyNameWithoutFacetPrefix((string)$param), $values, $this->useFilterByUrlKey, $this->getFacetValueKey(), $this->getFacetValueCorrelation())
                );
            }
        }

        return $this;
    }

    /**
     * Setting the range facets provided from the class to the request
     *
     * @param RequestInterface $request
     * @return $this
     */
    public function addRangeFacets(RequestInterface $request) : ListingContextAbstract
    {
        foreach($this->getRangeProperties() as $propertyName=>$configurations)
        {
            try{
                $from = (float) $request->getParam($configurations['from'], 0);
                $to = (float) $request->getParam($configurations['to'], 0);
                if($from > 0 || $to > 0)
                {
                    $this->getApiRequest()->addFacets(
                        $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                            ->addRange($propertyName, $from, $to)
                    );
                }
            } catch (\Throwable $exception)
            {
                //do nothing, maybe an issue in definition of the range properties?
            }
        }

        return $this;
    }

    /**
     * Adding the categories facet to the API request
     *
     * @param RequestInterface $request
     * @return ListingContextAbstract
     */
    public function addCategoryFacet(RequestInterface $request) : ListingContextAbstract
    {
        if($this->useCategoriesFilter)
        {
            $this->getApiRequest()->addFacets(
                $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                    ->add("categories", -1, 1, $this->getFacetValueCorrelation())
            );
        }

        return $this;
    }

    /**
     * @param string $param
     * @return bool
     */
    protected function isParamAllowedAsFilter(string $param)
    {
        $allowedAsFilter = in_array($param, $this->getFilterablePropertyNames()) || !$this->getFacetPrefix();

        if($this->getFacetPrefix())
        {
            if(strpos((string)$param, $this->getFacetPrefix()) === 0)
            {
                $allowedAsFilter = true;
            }
        }

        return $allowedAsFilter;
    }

    /**
     * Range properties definition as used in the projects
     *
     * @return array
     */
    abstract public function getRangeProperties() : array;

    /**
     * Delimiter for the filter values in the URL
     * RULE: must be the same as the one used in facet model (if exists)
     *
     * @return string
     */
    abstract public function getFilterValuesDelimiter() : string;

    /**
     * Environment-specific generic request properties (ex: sorting, page nr, limit, etc)
     *
     * @return array
     */
    abstract public function getSystemParameters() : array;

    /**
     * @param bool $value
     * @return $this
     */
    public function addFilterByFacetOptionId(bool $value) : ListingContextInterface
    {
        $this->useFilterByFacetOptionId = $value;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function addFilterByFacetUrlKey(bool $value) : ListingContextInterface
    {
        $this->useFilterByUrlKey = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function addFilterByFacetValueKey(string $value) : ListingContextInterface
    {
        $this->facetValueKeyFilter = $value;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function addCategoriesFilter(bool $value) : ListingContextInterface
    {
        $this->useCategoriesFilter = $value;
        return $this;
    }

    /**
     * Returns the configured valueKey for the integration use-case
     * @return string|null
     */
    protected function getFacetValueKey() : ?string
    {
        if($this->useFilterByFacetOptionId)
        {
            return FacetDefinition::BOXALINO_REQUEST_FACET_VALUEKEY;
        }

        return $this->facetValueKeyFilter;
    }

    /**
     * @return array
     */
    abstract public function getFilterablePropertyNames() : array;


}
