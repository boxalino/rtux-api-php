<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request;

/**
 * Class ParameterFactoryInterface
 * Creates the parameter setter objects which are declared as public services
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
interface ParameterFactoryInterface
{
    CONST BOXALINO_API_REQUEST_PARAMETER_TYPE_FACET = "facet";
    CONST BOXALINO_API_REQUEST_PARAMETER_TYPE_FILTER = "filter";
    CONST BOXALINO_API_REQUEST_PARAMETER_TYPE_ITEM = "item";
    CONST BOXALINO_API_REQUEST_PARAMETER_TYPE_SORT = "sort";
    CONST BOXALINO_API_REQUEST_PARAMETER_TYPE_USER = "user";
    CONST BOXALINO_API_REQUEST_PARAMETER_TYPE_HEADER = "header";
    CONST BOXALINO_API_REQUEST_PARAMETER_DEFINITION = "definition";
    CONST BOXALINO_API_REQUEST_PARAMETER_CORRELATION = "correlation";


    /**
     * @param string $type
     * @return ParameterInterface | null
     */
    public function get(string $type) : ?ParameterInterface;

}
