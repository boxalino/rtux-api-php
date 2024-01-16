<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterDefinition;

/**
 * Class HeaderParameterDefinition
 *
 * Required parameters for every request:
 * User-Host, User-Referer, User-Url, User-Agent
 *
 * Any additional parameters can be added
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
#[\AllowDynamicProperties]
class HeaderParameterDefinition extends ParameterDefinition
{

    /**
     * @param string $property
     * @param string $value
     * @return $this
     */
    public function add(string $property, ?string $value)
    {
        $this->{$property} = $value;
        return $this;
    }

}
