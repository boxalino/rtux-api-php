<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Util;

/**
 * Class ConfigurationInterface
 * Configurations defined for the REST API requests
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Util
 */
interface ConfigurationInterface
{
    public const RTUX_API_ENDPOINT_PRODUCTION="https://main.%%domain%%.com/narrative/%%account%%/api/1";
    public const RTUX_API_ENDPOINT_STAGE="https://r-st.%%domain%%.com/narrative/%%account%%/api/1";
    public const BOXALINO_API_TRACKING_PRODUCTION="//track.bx-cloud.com/static/bav2.min.js";
    public const BOXALINO_API_TRACKING_STAGE="//r-st.bx-cloud.com/static/bav2.min.js";
    public const BOXALINO_API_SERVER_PRODUCTION="//track.bx-cloud.com/track/v2";
    public const BOXALINO_API_SERVER_STAGE="//r-st.bx-cloud.com/track/v2";
    public const RTUX_API_DOMAIN_MAIN="bx-cloud";
    public const RTUX_API_DOMAIN_ALTERNATIVE="wi-platform-cloud";

    /**
     * The API endpoint depends on the testing conditionals and on the data index
     *
     * @return string
     */
    public function getRestApiEndpoint() : string;

    /**
     * @return string
     */
    public function getUsername() : string;

    /**
     * @return string
     */
    public function getApiKey() : string;

    /**
     * @return string
     */
    public function getApiSecret() : string;

    /**
     * @return bool
     */
    public function getIsDev() : bool;

    /**
     * @return bool
     */
    public function getIsTest() : bool;

    
}
