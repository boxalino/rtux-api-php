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

            //it`s a store property - has the allowed filters prefix
            if(strpos((string)$param, $this->getFacetPrefix()) === 0 )
            {
                $values = is_array($values) ? $values : explode($this->getFilterValuesDelimiter(), $values);
                $values = array_map("html_entity_decode", $values);
                $this->getApiRequest()->addFacets(
                    $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                        ->addWithValues($this->getPropertyNameWithoutFacetPrefix($param), $values, $this->useFilterByUrlKey, $this->getFacetValueKey(), $this->getFacetValueCorrelation())
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
     * Range properties definition as used in the projects
     *
     * @return array
     */
    abstract public function getRangeProperties() : array;

    /**
     * Delimiter for the filter values in the URL
     *
     * @return string
     */
    abstract public function getFilterValuesDelimiter() : string;

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


}
