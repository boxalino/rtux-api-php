<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Content;

/**
 * Trait LoadPropertiesTrait
 *
 * @package Boxalino\RealTimeUserExperienceApi\Framework\Content
 */
trait LoadPropertiesTrait
{


    /**
     * @param $object
     * @param array $excludeProperties
     * @param array $ignoreFunctions
     * @param bool $camelCase
     */
    public function loadPropertiesToObject($object, array $excludeProperties = [], array $ignoreFunctions=[], bool $camelCase = false)
    {
        $excludeProperties = array_merge($excludeProperties, $this->getDefaultPropertiesToIgnoreForElementLoad());
        $ignoreFunctions = array_merge($ignoreFunctions, $this->getDefaultFunctionsToIgnoreForElementLoad());

        $functions = get_class_methods($object);
        foreach ($functions as $function)
        {
            $method = substr($function, 0, 3);
            $property = substr($function, 3);
            if($camelCase)
            {
                $property = strtolower(substr($function, 3, 1)) . substr($function, 4);
            }
            /** for simple get/set/has etc functions - ignore */
            if(empty($property))
            {
                continue;
            }

            if($method === "set" )
            {
                continue;
            }

            if(in_array($property, $excludeProperties))
            {
                continue;
            }

            if(in_array($function, $ignoreFunctions))
            {
                continue;
            }

            if($method == "get")
            {
                try{
                    $object->$property = $object->$function();
                } catch (\Throwable $exception)
                {
                    // in case the function expects arguments - do nothing
                }
                continue;
            }

            try{
                $object->$function = $object->$function();
            } catch (\Throwable $exception)
            {
                // in case the function expects arguments - do nothing
            }
        }
    }

    /**
     * @return string[]
     */
    public function getDefaultFunctionsToIgnoreForElementLoad() : array
    {
        return [
            "addAccessorContext", "getAccessorHandler", "load", "__construct", "toObject",
            "findObjectWithProperty", "getDefaultFunctionsToIgnoreForElementLoad", "getDefaultPropertiesToIgnoreForElementLoad"
        ];
    }

    /**
     * @return string[]
     */
    public function getDefaultPropertiesToIgnoreForElementLoad() : array
    {
        return ["AccessorHandler"];
    }

}
