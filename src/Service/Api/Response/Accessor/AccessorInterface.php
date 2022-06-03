<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

/**
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
interface AccessorInterface
{

    /**
     *
     */
    public const BOXALINO_API_ACCESSOR_PARAMETER = "accessor";

    /**
     * @param \StdClass $data
     * @param AccessorInterface $object
     * @return AccessorInterface
     */
    public function toObject(\StdClass $data, AccessorInterface $object) : AccessorInterface;

    /**
     * @param string $parameterName
     * @param $content
     * @return mixed
     */
    public function set(string $parameterName, $content);

    /**
     * @param string $parameterName
     * @return mixed
     */
    public function get(string $parameterName);

    /**
     * Sets either accessor objects or accessor fields to the response object
     *
     * @param string $propertyName
     * @param $content
     * @return $this
     */
    public function add(string $propertyName, $content);

    /**
     * Access the Boxalino response attributes for API JS tracker
     *
     * @return \ArrayIterator
     */
    public function getBxAttributes() : \ArrayIterator;

    /**
     * Access the Boxalino bx-context
     * (It returns the WIDGET name)
     *
     * @return string | null
     */
    public function getBxContext() : ?string;

    /**
     * @param \StdClass $element
     */
    public function setBxContext(\StdClass $element) : void;

    /**
     * Loading content into accessor
     */
    public function load() : void;

}
