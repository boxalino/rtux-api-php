<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiFacetModelAbstract;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Context\ListingContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Definition\ListingRequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactory;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\WrongDependencyTypeException;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @return RequestDefinitionInterface
     */
    public function get(Request $request) : RequestDefinitionInterface
    {
        parent::get($request);
        $this->addFacets($request);
        $this->addRangeFacets($request);

        return $this->getApiRequest();
    }

    /**
     * Filter the list of query parameters by either being a product property or a defined property used in filter
     *
     * @param Request $request
     * @return SearchContextAbstract
     */
    public function addFacets(Request $request): ListingContextAbstract
    {
        foreach($request->query->all() as $param => $values)
        {
            //it`s a store property
            if(strpos($param, ApiFacetModelAbstract::BOXALINO_STORE_FACET_PREFIX)===0)
            {
                if (in_array($param, array_keys($this->getRangeProperties())))
                {
                    continue;
                }
                $values = is_array($values) ? $values : explode("|", $values);
                $values = array_map("html_entity_decode", $values);
                $this->getApiRequest()->addFacets(
                    $this->parameterFactory->get(ParameterFactory::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
                        ->addWithValues($param, $values)
                );
            }
        }

        return $this;
    }

    /**
     * Setting the range facets provided from the class to the request
     *
     * @param Request $request
     * @return $this
     */
    public function addRangeFacets(Request $request) : ListingContextAbstract
    {
        foreach($this->getRangeProperties() as $propertyName=>$configurations)
        {
            $from = $request->query->getInt($configurations['from'], 0);
            $to = $request->query->getInt($configurations['to'], 0);
            if($from > 0 || $to > 0)
            {
                $this->getApiRequest()->addFacets(
                    $this->parameterFactory->get(ParameterFactory::BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET)
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

}