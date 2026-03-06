<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

/**
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
interface AccessorModelInterface
{

    /**
     * @param AccessorInterface | null $context
     * @return AccessorModelInterface
     */
    public function addAccessorContext(?AccessorInterface $context = null) : AccessorModelInterface;

    /**
     * Prepare loaded content for pwa-like setup
     * Used in order to disclose model/block getters
     */
    public function load() : void;

}
