<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseHydratorTrait;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\AccessorHandlerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;

/**
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
#[\AllowDynamicProperties]
class Accessor implements AccessorInterface
{
    use ResponseHydratorTrait;

    /**
     * @var AccessorHandlerInterface
     */
    protected $accessorHandler;

    /**
     * @var BxAttributeList | \ArrayIterator | []
     */
    protected $bxAttributes = null;

    /** @var string | null */
    protected $bxContent = null;

    /** @var array  */
    protected $_accessorData = [];

    public function __construct(AccessorHandlerInterface $accessorHandler)
    {
        $this->accessorHandler = $accessorHandler;
    }

    /**
     * Required for serialization (pre-cache processing)
     */
    public function __sleep()
    {
        return ["bxContent", "_accessorData"];
    }

    /**
     * Dynamically add properties to the object
     *
     * @param string $methodName
     * @param null $params
     * @return $this
     */
    public function __call(string $methodName, $params = null)
    {
        $methodPrefix = substr($methodName, 0, 3);
        $key = strtolower(substr($methodName, 3, 1)) . substr($methodName, 4);
        if($methodPrefix == 'get')
        {
            try{
                return $this->$key;
            } catch (\Throwable $exception)
            {
                throw new UndefinedPropertyError("BoxalinoAPI: the property $key is not available in the " . get_called_class());
            }
        }

        if($methodPrefix == 'has')
        {
            try{
                foreach (get_object_vars($this) as $property => $value)
                {
                    if($key === $property)
                    {
                        return true;
                    }
                }

                return false;
            } catch (\Throwable $exception)
            {
            }
        }
    }

    /**
     * Sets either accessor objects or accessor fields to the response object
     *
     * @param string $propertyName
     * @param $content
     * @return $this
     */
    public function set(string $propertyName, $content)
    {
        $this->$propertyName = $content;
        return $this;
    }

    /**
     * Sets either accessor objects or accessor fields to the response object
     *
     * @param string $propertyName
     */
    public function get(string $propertyName)
    {
        try{
            return $this->$propertyName;
        } catch (\Throwable $exception)
        {
            // do nothing
        }
    }

    /**
     * Sets either accessor objects or accessor fields to the response object
     *
     * @param string $propertyName
     * @param $content
     * @return $this
     */
    public function add(string $propertyName, $content)
    {
        $this->$propertyName[] = $content;
        return $this;
    }

    /**
     * @param string | array | \StdClass $data
     * @return AccessorInterface
     */
    public function _addAccessorData($data) : AccessorInterface
    {
        if(is_object($data))
        {
            $data = json_encode($data, true);
        }

        if(json_decode($data, true))
        {
            $data = json_decode($data, true);
        }

        if(!is_array($data))
        {
            $data = [$data];
        }

        $this->_accessorData = $data;
        return $this;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function _getFromData(string $key)
    {
        if(isset($this->_accessorData[$key]))
        {
            return $this->_accessorData[$key];
        }

        return null;
    }

    /**
     * @return AccessorHandlerInterface
     */
    public function getAccessorHandler(): AccessorHandlerInterface
    {
        return $this->accessorHandler;
    }

    /**
     *
     * @param array|null $attributesList
     * @return $this
     */
    public function setBxAttributes(?array $attributesList) : self
    {
        $this->bxAttributes = new BxAttributeList();
        foreach($attributesList as $attribute)
        {
            $this->bxAttributes->append($this->toObject($attribute, $this->getAccessorHandler()->getAccessor("bx-attributes")));
        }

        return $this;
    }

    /**
     * @return BxAttributeList | \ArrayIterator
     */
    public function getBxAttributes() : \ArrayIterator
    {
        return $this->bxAttributes ?? new BxAttributeList();
    }

    /**
     * @return string|null
     */
    public function getBxContext() : ?string
    {
        return $this->bxContent;
    }

    /**
     * @param \StdClass $element
     */
    public function setBxContext(\StdClass $element) : void
    {
        $this->bxContent = $element->widget ?? null;
    }

    /**
     * Loading content into accessor
     */
    public function load(): void {}


}
