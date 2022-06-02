<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\WrongDependencyTypeException;

/**
 * Class Block
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Accessor
 */
class Block extends Accessor
    implements BlockInterface
{

    /**
     * @var string
     */
    protected $model;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string | null
     */
    protected $position;

    /**
     * @var null | string
     */
    protected $accessor = null;

    /**
     * @var \ArrayIterator
     */
    protected $blocks;

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var \ArrayIterator
     */
    protected $facets;

    /**
     * The load of the model is done on model request to ensure all other properties
     * (blocks, etc) have been set on the context which is passed via "$this"
     *
     * @return string| AccessorModelInterface | null
     */
    public function getModel() :?AccessorModelInterface
    {
        if(is_string($this->model))
        {
            $this->model = $this->getAccessorHandler()->getModel($this->model, $this);
        }

        return $this->model;
    }

    /**
     * @return string
     */
    public function getTemplate() : string
    {
        return $this->template;
    }

    /**
     * @return string|null
     */
    public function getPosition() : ?string
    {
        return $this->position;
    }

    /**
     * @return string|null
     */
    public function getAccessor() : ?string
    {
        return $this->accessor;
    }

    /**
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator
    {
        return $this->blocks;
    }

    /**
     * @return int
     */
    public function getIndex() : int
    {
        return $this->index;
    }

    /**
     * @param null | array $model
     * @return $this
     */
    public function setModel(array $model)
    {
        $this->model = $model[0] ?? null;
        return $this;
    }

    /**
     * @param array $template
     * @return $this
     */
    public function setTemplate(array $template)
    {
        $this->template = $template[0] ?? null;
        return $this;
    }

    /**
     * Accessor is identified as another widget request that provides content to the element
     * (ex: in the case of no search results matching the query, an automated request for "noresults" is done)
     *
     * @param array|null $accessor
     * @return $this
     */
    public function setAccessor($accessor = null)
    {
        $this->accessor = $accessor[0] ?? null;
        return $this;
    }

    /**
     * @param array | null
     * @return $this
     */
    public function setBlocks(?array $blocks)
    {
        $this->blocks = new \ArrayIterator();
        foreach($blocks as $block)
        {
            $this->blocks->append($this->toObject($block, $this->getAccessorHandler()->getAccessor("blocks")));
        }

        return $this;
    }

    /**
     * @param array $position
     * @return $this
     */
    public function setPosition(?array $position)
    {
        $this->position = $position[0] ?? null;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getFacets() : \ArrayIterator
    {
        try{
            $model = $this->getModel();
            if($model instanceof AccessorFacetModelInterface)
            {
                $this->facets = $this->getModel()->getFacets();
                return $this->facets;
            }
            throw new WrongDependencyTypeException("BoxalinoAPIBlock: the facets model must be an instance of the AccessorFacetModelInterface.");
        } catch (\Throwable $exception)
        {
            $this->log($exception->getMessage());
        }

        return new \ArrayIterator();
    }

    /**
     * @param int|null $index
     * @return $this
     */
    public function setIndex(?int $index)
    {
        $this->index = $index ?? 0;
        return $this;
    }

}
