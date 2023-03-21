<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\CorrelationDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\FacetDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\FilterDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\HeaderParameterDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\UserParameterDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\SortingDefinition;
use JsonSerializable;

/**
 * Boxalino API Request definition interface
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
interface RequestDefinitionInterface extends \JsonSerializable
{

    /**
     * URL parameter to alert for an inspect request
     */
    public const BOXALINO_API_REQUEST_INSPECT_FLAG="_bx_inspect_key";

    /**
     * URL parameter to alert for an inspect request on a given widget
     */
    public const BOXALINO_API_WIDGET_INSPECT_FLAG="_bx_inspect_widget";

    /**
     * URL parameter to change view mode in "test:true"
     */
    public const BOXALINO_API_TEST_INSPECT_FLAG="_bx_inspect_test";

    /**
     * @param FilterDefinition ...$filterDefinitions
     * @return $this
     */
    public function addFilters(FilterDefinition ...$filterDefinitions) : RequestDefinitionInterface ;

    /**
     * @param SortingDefinition ...$sortingDefinitions
     * @return $this
     */
    public function addSort(SortingDefinition ...$sortingDefinitions) : RequestDefinitionInterface ;

    /**
     * @param FacetDefinition ...$facetDefinitions
     * @return $this
     */
    public function addFacets(FacetDefinition ...$facetDefinitions) : RequestDefinitionInterface;

    /**
     * @param CorrelationDefinition ...$correlationDefinitions
     * @return $this
     */
    public function addCorrelations(CorrelationDefinition ...$correlationDefinitions) : RequestDefinitionInterface;

    /**
     * @param HeaderParameterDefinition ...$headerParameterDefinitions
     * @return $this
     */
    public function addHeaderParameters(HeaderParameterDefinition ...$headerParameterDefinitions);

    /**
     * @param UserParameterDefinition ...$userParameterDefinitions
     * @return $this
     */
    public function addParameters(UserParameterDefinition ...$userParameterDefinitions);

    /**
     * @param string $apiSecret
     * @return RequestDefinitionInterface
     */
    public function setApiSecret(string $apiSecret) : RequestDefinitionInterface;

    /**
     * @param bool $dev
     * @return RequestDefinitionInterface
     */
    public function setDev(bool $dev) : RequestDefinitionInterface;

    /**
     * @param bool $test
     * @return RequestDefinitionInterface
     */
    public function setTest(bool $test) : RequestDefinitionInterface;

    /**
     * @param string $language
     * @return RequestDefinitionInterface
     */
    public function setLanguage(string $language) : RequestDefinitionInterface;

    /**
     * @param string $sessionId
     * @return RequestDefinitionInterface
     */
    public function setSessionId(string $sessionId) : RequestDefinitionInterface;

    /**
     * @param string $profileId
     * @return RequestDefinitionInterface
     */
    public function setProfileId(string $profileId) : RequestDefinitionInterface;

    /**
     * @return string
     */
    public function getProfileId() : string;

    /**
     * @param string $customerId
     * @return RequestDefinitionInterface
     */
    public function setCustomerId(string $customerId) : RequestDefinitionInterface;

    /**
     * @param string $widget
     * @return RequestDefinitionInterface
     */
    public function setWidget(string $widget) : RequestDefinitionInterface;

    /**
     * @param int $hitCount
     * @return RequestDefinitionInterface
     */
    public function setHitCount(int $hitCount) : RequestDefinitionInterface;

    /**
     * @param int $offset
     * @return RequestDefinitionInterface
     */
    public function setOffset(int $offset) : RequestDefinitionInterface;

    /**
     * @param string $groupBy
     * @return RequestDefinitionInterface
     */
    public function setGroupBy(string $groupBy) : RequestDefinitionInterface;

    /**
     * @param string $query
     * @return $this
     */
    public function setQuery(string $query) : RequestDefinitionInterface;

    /**
     * @param array $returnFields
     * @return RequestDefinitionInterface
     */
    public function setReturnFields(array $returnFields) : RequestDefinitionInterface;

    /**
     * @param bool $orFilters
     * @return RequestDefinitionInterface
     */
    public function setOrFilters(bool $orFilters) : RequestDefinitionInterface;

    /**
     * @param array $parameters
     * @return RequestDefinitionInterface
     */
    public function setParameters(array $parameters): RequestDefinitionInterface;

    /**
     * @param string $username
     * @return RequestDefinitionInterface
     */
    public function setUsername(string $username) : RequestDefinitionInterface;

    /**
     * @param string $apiKey
     * @return RequestDefinitionInterface
     */
    public function setApiKey(string $apiKey) : RequestDefinitionInterface;

    /**
     * @return string
     */
    public function getApiKey() : string;

    /**
     * @return bool
     */
    public function isInspectMode() : bool;

    /**
     * @return bool
     */
    public function isTestInspectMode() : bool;

    /**
     * @return string
     */
    public function getWidget() : string;

    /**
     * @return array
     */
    public function toArray() : array;

}
