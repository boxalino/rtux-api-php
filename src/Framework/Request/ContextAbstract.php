<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter\FacetDefinition;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorFacetModelInterface;

/**
 * Class ContextAbstract
 * System-specific request context definition
 * Builds the request with the use of the request definitions entities and the use of the request transformer
 *
 * @package Boxalino\RealTimeUserExperienceApi\Framework\Request
 */
abstract class ContextAbstract
{

    /**
     * @var RequestDefinitionInterface
     */
    protected $apiRequest;

    /**
     * @var ParameterFactoryInterface
     */
    protected $parameterFactory;

    /**
     * @var RequestTransformerInterface
     */
    protected $requestTransformer;

    /**
     * @var string
     */
    protected $widget;

    /**
     * @var bool
     */
    protected $orFilters = false;

    /**
     * @var int
     */
    protected $hitCount = 0;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var string
     */
    protected $groupBy = "products_group_id";

    /**
     * @var \ArrayObject
     */
    protected $properties;

    /**
     * @var null
     */
    protected $facetPrefix = null;

    /**
     * @var bool
     */
    protected $useCategoriesFilter = false;

    /**
     * @var bool
     */
    protected $useFilterByFacetOptionId = false;

    /**
     * @var bool
     */
    protected $useFilterByUrlKey = false;

    /**
     * @var null | string
     */
    protected $facetValueKeyFilter = null;

    /**
     * @var null | string
     */
    protected $facetValueCorrelation = null;

    /**
     * Listing constructor.
     *
     * @param RequestTransformerInterface $requestTransformer
     * @param ParameterFactoryInterface $parameterFactory
     */
    public function __construct(
        RequestTransformerInterface $requestTransformer,
        ParameterFactoryInterface $parameterFactory
    ) {
        $this->properties = new \ArrayObject();
        $this->requestTransformer = $requestTransformer;
        $this->parameterFactory = $parameterFactory;
    }

    /**
     * @param RequestInterface $request
     * @return RequestDefinitionInterface
     */
    public function get(RequestInterface $request) : RequestDefinitionInterface
    {
        $this->validateRequest($request);

        $this->requestTransformer->setRequestDefinition($this->getApiRequest())
            ->setLimit($this->getHitCount())
            ->transform($request);

        $this->setRequestDefinition($this->requestTransformer->getRequestDefinition());
        $this->getApiRequest()
            ->setReturnFields($this->getReturnFields())
            ->setGroupBy($this->getGroupBy())
            ->setWidget($this->getWidget());

        $this->addFilters($request);
        $this->addContextParameters($request);
        $this->addSort($request);

        return $this->getApiRequest();
    }

    abstract public function validateRequest(RequestInterface $request) : void;
    abstract function getContextNavigationId(RequestInterface $request) : array;
    abstract function getVisibilityFilter(RequestInterface $request) : ParameterInterface;
    abstract function getCategoryFilter(RequestInterface $request) : ParameterInterface;
    abstract function getActiveFilter(RequestInterface $request) : ParameterInterface;
    abstract function getContextVisibility() : array;
    abstract function getReturnFields() : array;

    /**
     * Adding a sort on the request
     * Redefine in a context that allows flexible update of the request
     * Either add a sort on the API request  (RequestDefinitionInterface) element
     * or add the sort parameter key:value on the RequestInterface $request
     * (it will automatically be added in the request
     *
     * @param RequestInterface $request
     */
    public function addSort(RequestInterface $request) : void
    {
        return;
    }

    /**
     * Adding context parameters per integration use-case
     * (for custom integrations)
     *
     * @param RequestInterface $request
     * @return void
     */
    protected function addContextParameters(RequestInterface $request) : void
    {
        if($this->getHitCount())
        {
            $this->getApiRequest()->setHitCount($this->getHitCount());
        }
    }

    /**
     * Adding general filters (sample)
     * (when implementing the abstract class, the system logic can differ)
     *
     * @param RequestInterface $request
     */
    protected function addFilters(RequestInterface $request) : void
    {
        $this->getApiRequest()
            ->addFilters(
                $this->getVisibilityFilter($request),
                $this->getCategoryFilter($request),
                $this->getActiveFilter($request)
            );
    }

    /**
     * @param string $widget
     * @return $this
     */
    public function setWidget(string $widget)
    {
        $this->widget = $widget;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidget() : string
    {
        return $this->widget;
    }

    /**
     * @return RequestDefinitionInterface
     */
    public function getApiRequest() : RequestDefinitionInterface
    {
        return $this->apiRequest;
    }

    /**
     * @param RequestDefinitionInterface $requestDefinition
     * @return $this
     */
    public function setRequestDefinition(RequestDefinitionInterface $requestDefinition)
    {
        $this->apiRequest = $requestDefinition;
        return $this;
    }

    /**
     * @param bool $orFilters
     * @return ContextAbstract
     */
    public function setOrFilters(bool $orFilters) : self
    {
        $this->orFilters = $orFilters;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOrFilters(): bool
    {
        return $this->orFilters;
    }

    /**
     * @param string $groupBy
     * @return ContextAbstract
     */
    public function setGroupBy(string $groupBy) : self
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupBy() : string
    {
        return $this->groupBy;
    }

    /**
     * @return int
     */
    public function getHitCount(): int
    {
        return $this->hitCount;
    }

    /**
     * @param int $hitCount
     * @return ContextAbstract
     */
    public function setHitCount(int $hitCount) : self
    {
        $this->hitCount = $hitCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return ContextAbstract
     */
    public function setOffset(int $offset) : self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param string $property
     * @param string | bool $value
     * @return $this
     */
    public function set(string $property, $value)
    {
        $this->properties->offsetSet($property, $value);
        return $this;
    }

    /**
     * @param string $property
     * @return string | bool | void
     */
    public function getProperty(string $property)
    {
        if($this->properties->offsetExists($property))
        {
            return $this->properties->offsetGet($property);
        }
    }

    /**
     * @param string $facetPrefix
     */
    public function setFacetPrefix(string $facetPrefix): void
    {
        $this->facetPrefix = $facetPrefix;
    }

    /**
     * @return string | null
     */
    public function getFacetPrefix(): ?string
    {
        return $this->facetPrefix ?? AccessorFacetModelInterface::BOXALINO_API_FACET_PREFIX;
    }

    /**
     * @return string
     */
    public function getFacetValueCorrelation(): string
    {
        return $this->facetValueCorrelation ?? FacetDefinition::BOXALINO_REQUEST_FACET_VALUE_CORRELATION_RTUX;
    }

    /**
     * @param string|null $facetValueCorrelation
     * @return ContextAbstract
     */
    public function setFacetValueCorrelation(?string $facetValueCorrelation): ContextAbstract
    {
        $this->facetValueCorrelation = $facetValueCorrelation;
        return $this;
    }

    /**
     * @param string $property
     * @return bool
     */
    public function has(string $property) : bool
    {
        return $this->properties->offsetExists($property);
    }

    /**
     * @param string $key
     * @param $value
     * @param string $type
     */
    public function addRequestParameter(string $key, $value, $type=ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_HEADER)
    {
        if(in_array($type,
            [
                ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_USER,
                ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_HEADER
            ]
        )
        ) {
            $this->getApiRequest()->addHeaderParameters(
                $this->parameterFactory->get($type)
                    ->add($key, $value)
            );
        }

        return $this;
    }

    /**
     * @param string $param
     * @return string
     */
    public function getPropertyNameWithoutFacetPrefix(string $param) : string
    {
        if($this->getFacetPrefix())
        {
            return substr($param, strlen($this->getFacetPrefix()), strlen($param));
        }

        return $param;
    }


}
