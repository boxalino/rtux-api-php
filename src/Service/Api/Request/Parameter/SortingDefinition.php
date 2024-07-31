<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterDefinition;

/**
 * Class SortingDefinition
 * Setting a sorting option on the request
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
#[\AllowDynamicProperties]
class SortingDefinition extends ParameterDefinition
{

    /**
     * @param string $field
     * @param bool $reverse
     * @return $this
     */
    public function add(string $field, bool $reverse = false) : self
    {
        $this->field = $field;
        $this->reverse = $reverse;

        return $this;
    }

}
