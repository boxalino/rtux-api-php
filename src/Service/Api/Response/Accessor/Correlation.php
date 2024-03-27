<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

/**
 * Class Correlation
 * Model for the API response of correlations
 * 
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
class Correlation extends Accessor
    implements AccessorInterface
{

    /**
     * @var string
     */
    protected $origin;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array | \ArrayIterator
     */
    protected $target;

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return AccessorInterface
     */
    public function setOrigin(string $origin): AccessorInterface
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return AccessorInterface
     */
    public function setSource(string $source): AccessorInterface
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return AccessorInterface
     */
    public function setType(string $type): AccessorInterface
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getTarget(): \ArrayIterator
    {
        return $this->target;
    }

    /**
     * @param array $targets
     * @return AccessorInterface
     */
    public function setTarget(array $targets): AccessorInterface
    {
        $this->target = new \ArrayIterator();
        foreach($targets as $target)
        {
            $this->target->append($this->toObject(json_decode($target), $this->getAccessorHandler()->getAccessor("bx-hit")));
        }

        return $this;
    }



}
