<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;

/**
 * Class BxAttributeList
 * An extension of the ArrayIterator to allow retrieving the property value
 *
 * (ex: data-bx-variant-uuid, data-bx-narrative-name, data-bx-narrative-group-by, data-bx-item-id, class,
 * data-bx-related-variant-uuid, data-bx-related-item-id, data-bx-related-narrative-name, etc)
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
#[\AllowDynamicProperties]
class BxAttributeList extends \ArrayIterator
{

    /**
     * @var \ArrayIterator
     */
    protected $data;

    public function __construct($array = array(), $flags = 0)
    {
        parent::__construct($array);
    }

    /**
     * Dynamically access properties values from the list of bx-attributes
     *
     * @param string $methodName
     * @param null $params
     */
    public function __call(string $methodName, $params = null)
    {
        $methodPrefix = substr($methodName, 0, 3);
        $key = strtolower(implode("-", array_filter(preg_split('/(?=[A-Z])/', substr($methodName, 3)))));
        if($methodPrefix == 'get')
        {
            try{
                foreach ($this->getArrayCopy() as $attribute)
                {
                    if($attribute->getName() == $key)
                    {
                        return $attribute->getValue();
                    }
                }

                return null;
            } catch (\Throwable $exception)
            {
                throw new UndefinedPropertyError("BoxalinoAPI: the property $key is not available in the " . get_called_class());
            }
        }
    }

    /**
     * Access the value of a bx-attribute property (if exists)
     *
     * @param string $propertyName
     * @return string | null
     */
    public function get(string $propertyName) : ?string
    {
        foreach ($this->getArrayCopy() as $attribute)
        {
            if($attribute->getName() == $propertyName)
            {
                return $attribute->getValue();
            }
        }

        return null;
    }

}
