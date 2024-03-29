<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Request;

use \Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use \Symfony\Component\HttpFoundation\Request as PlatformRequest;

/**
 * Class Request
 * Adapts the system request to a generic request interface (wrapper/processor)
 * 
 * @package Boxalino\RealTimeUserExperienceApi\Framework\Request
 */
class Request implements RequestInterface
{

    /**
     * @var PlatformRequest
     */
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    /**
     * @param mixed $request
     * @return self
     */
    public function setRequest($request) : RequestInterface
    {
        $this->request = $request;
        return $this;
    }


    /**
     * The Shopware6 request is generally a \Symfony\Component\HttpFoundation\Request object
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasCookie(string $key) : bool
    {
        return $this->request->cookies->has($key);
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function setCookie(string $key, $value) : RequestInterface
    {
        $this->request->cookies->set($key, $value);
        return $this;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getCookie(string $key, $default=null): string
    {
        return $this->request->cookies->get($key, $default);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->request->getLocale();
    }

    /**
     * @return string | null
     */
    public function getClientIp(): ?string
    {
        return $this->request->getClientIp();
    }

    /**
     * @return string | null
     */
    public function getUserAgent(): ?string
    {
        return $this->request->headers->get('user-agent');
    }

    /**
     * @return string | null
     */
    public function getUserReferer(): ?string
    {
        return $this->request->headers->get('referer') ?? $this->getUserUrl();
    }

    /**
     * @return string | null
     */
    public function getUserUrl(): ?string
    {
        return $this->request->getUri();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        $queryString = $this->request->getQueryString();
        if(is_null($queryString))
        {
            return [];
        }
        parse_str($queryString, $params);

        return $params;
    }

    /**
     * @param string $key
     * @param string | null $default
     * @return mixed
     */
    public function getParam(string $key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method) : bool
    {
        return $this->request->isMethod($method);
    }

}
