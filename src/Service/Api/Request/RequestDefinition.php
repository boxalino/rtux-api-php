<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\CorrelationDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\FacetDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\FilterDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\HeaderParameterDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\UserParameterDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\SortingDefinition;

/**
 * Boxalino API Request definition object
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api
 */
class RequestDefinition implements RequestDefinitionInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiSecret;

    /**
     * @var bool
     */
    protected $dev;

    /**
     * @var bool
     */
    protected $test;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var string
     */
    protected $profileId;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $widget;

    /**
     * @var int
     */
    protected $hitCount;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var string
     */
    protected $groupBy;

    /**
     * @var string
     */
    protected $query = "";

    /**
     * @var array
     */
    protected $returnFields = [];

    /**
     * @var array
     */
    protected $sort = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var bool
     */
    protected $orFilters = false;

    /**
     * @var array
     */
    protected $facets = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $correlations = [];

    /**
     * @param FilterDefinition ...$filterDefinitions
     * @return RequestDefinitionInterface
     */
    public function addFilters(FilterDefinition ...$filterDefinitions) : RequestDefinitionInterface
    {
        foreach ($filterDefinitions as $filter) {
            $this->filters[] = $filter->toArray();
        }

        return $this;
    }

    /**
     * @param SortingDefinition ...$sortingDefinitions
     * @return RequestDefinitionInterface
     */
    public function addSort(SortingDefinition ...$sortingDefinitions) : RequestDefinitionInterface
    {
        foreach ($sortingDefinitions as $sort) {
            $this->sort[] = $sort->toArray();
        }

        return $this;
    }

    /**
     * @param FacetDefinition ...$facetDefinitions
     * @return RequestDefinitionInterface
     */
    public function addFacets(FacetDefinition ...$facetDefinitions) : RequestDefinitionInterface
    {
        foreach ($facetDefinitions as $facet) {
            $this->facets[] = $facet->toArray();
        }

        return $this;
    }

    /**
     * @param HeaderParameterDefinition ...$headerParameterDefinitions
     * @return RequestDefinitionInterface
     */
    public function addHeaderParameters(HeaderParameterDefinition ...$headerParameterDefinitions)
    {
        foreach ($headerParameterDefinitions as $parameter) {
            $this->parameters = array_merge($this->parameters, $parameter->toArray());
        }

        return $this;
    }

    /**
     * @param UserParameterDefinition ...$userParameterDefinitions
     * @return RequestDefinitionInterface
     */
    public function addParameters(UserParameterDefinition ...$userParameterDefinitions)
    {
        foreach ($userParameterDefinitions as $parameter) {
            $this->parameters = array_merge($this->parameters, $parameter->toArray());
        }

        return $this;
    }

    /**
     * @param CorrelationDefinition ...$correlationDefinitions
     * @return RequestDefinitionInterface
     */
    public function addCorrelations(CorrelationDefinition ...$correlationDefinitions) : RequestDefinitionInterface
    {
        foreach ($correlationDefinitions as $correlation) {
            $this->correlations[] = $correlation->toArray();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     * @return RequestDefinitionInterface
     */
    public function setApiSecret(string $apiSecret) : RequestDefinitionInterface
    {
        $this->apiSecret = $apiSecret;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDev(): bool
    {
        return $this->dev;
    }

    /**
     * @param bool $dev
     * @return RequestDefinition
     */
    public function setDev(bool $dev) : RequestDefinitionInterface
    {
        $this->dev = $dev;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->test;
    }

    /**
     * @param bool $test
     * @return RequestDefinitionInterface
     */
    public function setTest(bool $test) : RequestDefinitionInterface
    {
        $this->test = $test;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return RequestDefinitionInterface
     */
    public function setLanguage(string $language) : RequestDefinitionInterface
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return RequestDefinitionInterface
     */
    public function setSessionId(string $sessionId) : RequestDefinitionInterface
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfileId(): string
    {
        return $this->profileId;
    }

    /**
     * @param string $profileId
     * @return RequestDefinitionInterface
     */
    public function setProfileId(string $profileId) : RequestDefinitionInterface
    {
        $this->profileId = $profileId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     * @return RequestDefinitionInterface
     */
    public function setCustomerId(string $customerId) : RequestDefinitionInterface
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getWidget(): string
    {
        return $this->widget;
    }

    /**
     * @param string $widget
     * @return RequestDefinitionInterface
     */
    public function setWidget(string $widget) : RequestDefinitionInterface
    {
        $this->widget = $widget;
        return $this;
    }

    /**
     * @return int
     */
    public function getHitCount(): int
    {
        return $this->hitCount;
    }

    /**
     * @param int $hitCount
     * @return RequestDefinitionInterface
     */
    public function setHitCount(int $hitCount) : RequestDefinitionInterface
    {
        $this->hitCount = $hitCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return RequestDefinitionInterface
     */
    public function setOffset(int $offset) : RequestDefinitionInterface
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupBy(): string
    {
        return $this->groupBy;
    }

    /**
     * @param string $groupBy
     * @return RequestDefinitionInterface
     */
    public function setGroupBy(string $groupBy) : RequestDefinitionInterface
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() : string
    {
        return strip_tags($this->query);
    }

    /**
     * @param string $query
     * @return RequestDefinitionInterface
     */
    public function setQuery(string $query) : RequestDefinitionInterface
    {
        $this->query = strip_tags($query);
        return $this;
    }

    /**
     * @return array
     */
    public function getReturnFields(): array
    {
        return $this->returnFields;
    }

    /**
     * @param array $returnFields
     * @return RequestDefinitionInterface
     */
    public function setReturnFields(array $returnFields) : RequestDefinitionInterface
    {
        $this->returnFields = $returnFields;
        return $this;
    }

    /**
     * @return array
     */
    public function getSort(): array
    {
        return $this->sort;
    }
	
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return bool
     */
    public function isOrFilters(): bool
    {
        return $this->orFilters;
    }

    /**
     * @param bool $orFilters
     * @return RequestDefinitionInterface
     */
    public function setOrFilters(bool $orFilters) : RequestDefinitionInterface
    {
        $this->orFilters = $orFilters;
        return $this;
    }

    /**
     * @return array
     */
    public function getFacets(): array
    {
        return $this->facets;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return RequestDefinitionInterface
     */
    public function setParameters(array $parameters): RequestDefinitionInterface
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getCorrelations(): array
    {
        return $this->correlations;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return RequestDefinitionInterface
     */
    public function setUsername(string $username) : RequestDefinitionInterface
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey() : string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return RequestDefinitionInterface
     */
    public function setApiKey(string $apiKey) : RequestDefinitionInterface
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInspectMode() : bool
    {
        foreach($this->getParameters() as $parameterKey=>$parameterValue)
        {
            if($parameterKey === RequestDefinitionInterface::BOXALINO_API_REQUEST_INSPECT_FLAG)
            {
                if($parameterValue[0] === $this->getApiKey())
                {
                    if(in_array(RequestDefinitionInterface::BOXALINO_API_WIDGET_INSPECT_FLAG, array_keys($this->getParameters())))
                    {
                        $widgetToInspect = $this->getParameters()[RequestDefinitionInterface::BOXALINO_API_WIDGET_INSPECT_FLAG][0];
                        if($widgetToInspect === $this->getWidget())
                        {
                            return true;
                        }

                        return false;
                    }
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isTestInspectMode() : bool
    {
        foreach($this->getParameters() as $parameterKey=>$parameterValue)
        {
            if($parameterKey === RequestDefinitionInterface::BOXALINO_API_TEST_INSPECT_FLAG)
            {
                if($parameterValue[0] === $this->getApiKey())
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }

    /**
     * @return false|mixed|string
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }


}
