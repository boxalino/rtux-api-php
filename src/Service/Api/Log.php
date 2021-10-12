<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;

/**
 * Class Log
 * 
 * Generic logger to render content on the front-end (inspect mode)
 * 
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api
 */
class Log
{

    /**
     * @param string $endpoint
     * @param RequestDefinitionInterface $apiRequest
     * @param ResponseDefinitionInterface $apiResponse
     */
    public function inspect(string $endpoint, RequestDefinitionInterface $apiRequest, ResponseDefinitionInterface $apiResponse) : void
    {
        $this->printEndpoint($endpoint);
        $this->printApiRequest($apiRequest);
        $this->printApiResponse($apiResponse);
    }

    /**
     * @param string $content
     * @return string
     */
    public function print(string $content) : void
    {
        print_r("<pre>" . $content . "</pre>");
    }

    /**
     * @param string $endpoint
     */
    public function printEndpoint(string $endpoint) : void
    {
        $this->print('<b>BOXALINO API Endpoint:</b>');
        $this->print($endpoint);
    }

    /**
     * @param RequestDefinitionInterface $apiRequest
     */
    public function printApiRequest(RequestDefinitionInterface $apiRequest) : void
    {
        $this->print('<b>BOXALINO API REQUEST:</b>');
        $this->print($apiRequest->setApiSecret("********************")->jsonSerialize());
    }

    /**
     * @param ResponseDefinitionInterface $apiResponse
     */
    public function printApiResponse(ResponseDefinitionInterface $apiResponse) : void
    {
        $this->print('<b>BOXALINO API RESPONSE:</b>');
        $this->print($apiResponse->getJson());
    }

}
