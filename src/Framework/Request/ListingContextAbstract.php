<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiFacetModelAbstract;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Context\ListingContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\WrongDependencyTypeException;

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
            //it`s a store property - has the allowed filters prefix
            if(strpos((string)$param, $this->getFacetPrefix())===0)
            {
                if (in_array($param, array_keys($this->getRangeProperties())))
                {
                    continue;
                }
                $values = is_array($values) ? $values : explode($this->getFilterValuesDelimiter(), $values);
                $values = array_map("html_entity_decode", $values);
                $this->getApiRequest()->addFacets(
                    $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                        ->addWithValues(substr($param, strlen($this->getFacetPrefix()), strlen($param)), $values)
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
            $from = (int) $request->getParam($configurations['from'], 0);
            $to = (int) $request->getParam($configurations['to'], 0);
            if($from > 0 || $to > 0)
            {
                $this->getApiRequest()->addFacets(
                    $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                        ->addRange($propertyName, $from, $to)
                );
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


}
