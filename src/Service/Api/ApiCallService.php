<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use http\Client\Response;
use http\Message\Body;
use Psr\Log\LoggerInterface;

/**
 * Class ApiCallService
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api
 */
class ApiCallService implements ApiCallServiceInterface
{
    /**
     * @var Client
     */
    private $restClient;

    /**
     * @var ResponseDefinition
     */
    protected $apiResponse;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $fallback = false;

    /**
     * @var ResponseDefinitionInterface
     */
    protected $responseDefinition;

    /**
     * @var null | string
     */
    protected $fallbackMessage = null;

    /**
     * ApiCallService constructor.
     * @param LoggerInterface $logger
     * @param ResponseDefinitionInterface $responseDefinition
     */
    public function __construct(LoggerInterface $logger, ResponseDefinitionInterface $responseDefinition)
    {
        $this->restClient = new Client();
        $this->logger = $logger;
        $this->responseDefinition = $responseDefinition;
    }

    /**
     * @param RequestDefinitionInterface $apiRequest
     * @param string $restApiEndpoint
     * @return ResponseDefinition|null
     */
    public function call(RequestDefinitionInterface $apiRequest, string $restApiEndpoint) : ?ResponseDefinitionInterface
    {
        try {
            $this->responseDefinition->reset();
            $this->setFallback(false);
            $request = new Request(
                'POST',
                $this->getApiEndpoint($restApiEndpoint, $apiRequest->getProfileId()),
                [
                    'Content-Type' => 'application/json'
                ],
                $apiRequest->jsonSerialize()
            );

            /** when a request is done in test mode - log the API request */
            if($apiRequest->isTest())
            {
                $this->logger->info("Boxalino API request: " . $apiRequest->jsonSerialize());
            }

            /** @var  \GuzzleHttp\Psr7\Response $response */
            $response = $this->restClient->send($request);
            $this->setApiResponse($this->responseDefinition->setResponse($response));

            /** in case of successfull request & the request is done in inspect-mode - log both the API request & API response */
            $this->addInspect($apiRequest, $restApiEndpoint);

            return $this->getApiResponse();
        } catch (\Exception $exception)
        {
            $this->setFallback(true);
            $this->setFallbackMessage($exception->getMessage());
            $this->logger->error("BoxalinoAPIError: " . $exception->getMessage() . " at " . __CLASS__
                . " on request: " . $apiRequest->jsonSerialize()
            );
        }

        return null;
    }

    /**
     * @param RequestDefinitionInterface $apiRequest
     * @param string $restApiEndpoint
     * @return self
     */
    public function addInspect(RequestDefinitionInterface $apiRequest, string $restApiEndpoint) : self
    {
        if($apiRequest->isInspectMode())
        {
            $widget = $apiRequest->getWidget();

            header('BOXALINO_API_ENDPOINT_' . $widget .': '. $restApiEndpoint);
            header('BOXALINO_API_REQUEST_'. $widget .': '. $apiRequest->setApiSecret("********************")->jsonSerialize());
            header('BOXALINO_API_RESPONSE_'. $widget .': '. $this->getApiResponse()->getJson());
        }

        return $this;
    }

    /**
     * @param string $restApiEndpoint
     * @param string $profileId
     * @return string
     */
    public function getApiEndpoint(string $restApiEndpoint, string $profileId)
    {
        return stripslashes($restApiEndpoint) . "?profileId=$profileId";
    }

    /**
     * @return bool
     */
    public function isFallback() : bool
    {
        return $this->fallback;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setFallbackMessage(string $message) : self
    {
        $this->fallbackMessage = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFallbackMessage() : ?string
    {
        return $this->fallbackMessage;
    }

    /**
     * @param bool $fallback
     * @return $this
     */
    public function setFallback(bool $fallback) : self
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * @return ResponseDefinitionInterface
     */
    public function getApiResponse() : ResponseDefinitionInterface
    {
        return $this->apiResponse;
    }

    /**
     * @param ResponseDefinitionInterface $response
     * @return $this
     */
    public function setApiResponse(ResponseDefinitionInterface $response)
    {
        $this->apiResponse = $response;
        return $this;
    }

}
