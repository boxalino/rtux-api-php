<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

/**
 * Class Hit
 * A hit is a product item (or a different entity) that matches your request query
 * A hit has properties for all the return fields of the request
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
#[\AllowDynamicProperties]
class Hit extends Accessor
    implements AccessorInterface
{
}
