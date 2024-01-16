<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterDefinition;

/**
 * Class ItemDefinition
 * Used when the context is defined by items
 * (ex: product recommendation, basket recommendation, etc)
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter
 */
#[\AllowDynamicProperties]
class ItemDefinition extends ParameterDefinition
{

    /**
     * @param string $field
     * @param string $value
     * @param string $role
     * @return $this
     */
    public function add(string $field, string $value, string $role="mainProduct") : self
    {
        $this->field = $field;
        $this->value = $value;
        $this->role = $role;

        return $this;
    }

}
