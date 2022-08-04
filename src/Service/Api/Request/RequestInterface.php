<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request;

/**
 * Class RequestInterface
 * Adapts the framework request to a generic request interface (wrapper/processor) used within the plugin
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
interface RequestInterface
{
    
    public const METHOD_POST = "POST";
    public const METHOD_GET = "GET";

    /**
     * Returns the framework request
     * 
     * @return mixed
     */
    public function getRequest();

    /**
     * @param mixed $request
     * @return self
     */
    public function setRequest($request) : RequestInterface;

    /**
     * Check if cookie exists
     * 
     * @param string $key
     * @return bool
     */
    public function hasCookie(string $key) : bool;

    /**
     * Sets cookie value 
     * 
     * @param string $key
     * @param $value
     * @return $this
     */
    public function setCookie(string $key, $value) : RequestInterface;
    
    /**
     * Accesses cookie value 
     * 
     * @param string $key
     * @param $default
     * @return string
     */
    public function getCookie(string $key, $default=null): string;

    /**
     * Access to request locale
     * 
     * @return string
     */
    public function getLocale() : string;

    /**
     * @return string | null
     */
    public function getClientIp() : ?string;

    /**
     * @return string | null
     */
    public function getUserAgent() : ?string;

    /**
     * @return string | null
     */
    public function getUserReferer() : ?string;

    /**
     * @return string | null
     */
    public function getUserUrl() : ?string;

    /**
     * Gets the list of all parameters on the request (query, etc)
     * 
     * @return array
     */
    public function getParams() : array;

    /**
     * Gets value of specific parameter
     * 
     * @param string $key
     * @param null $default
     */
    public function getParam(string $key, $default = null);

    /**
     * Check the method of the original request
     * 
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method) : bool;

}
