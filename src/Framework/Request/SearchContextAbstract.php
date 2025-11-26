<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Context\ListingContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Context\SearchContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Definition\SearchRequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\WrongDependencyTypeException;

/**
 * Boxalino Search Request handler
 * Allows to set the nr of subphrases and products returned on each subphrase hit
 *
 * @package Boxalino\RealTimeUserExperienceApi\Framework\Request
 */
abstract class SearchContextAbstract
    extends ListingContextAbstract
    implements SearchContextInterface, ListingContextInterface
{

    /**
     * @var null | int
     */
    protected $subPhrasesCount;

    /**
     * @var null | int
     */
    protected $subPhrasesProductsCount;

    /**
     * @return string
     */
    abstract public function getSearchParameter() : string;

    /**
     * Adding additional subphrases details for the search request
     *
     * @param RequestInterface $request
     * @return RequestDefinitionInterface
     */
    public function get(RequestInterface $request) : RequestDefinitionInterface
    {
        parent::get($request);

        if(!is_null($this->subPhrasesCount))
        {
            $this->getApiRequest()->setMaxSubPhrases($this->getSubPhrasesCount());
        }

        if(!is_null($this->subPhrasesProductsCount))
        {
            $this->getApiRequest()->setMaxSubPhrasesHits($this->getSubPhrasesProductsCount());
        }

        return $this->getApiRequest();
    }
	
	/**
	 * Adding general filters
	 * (updated from main class, to ignore category filter)
	 *
	 * @param RequestInterface $request
	 */
	protected function addFilters(RequestInterface $request) : void
	{
		$this->getApiRequest()
			->addFilters(
				$this->getVisibilityFilter($request),
				$this->getActiveFilter($request)
			);
	}

    /**
     * @param RequestInterface $request
     * @return void
     */
    protected function addContextParameters(RequestInterface $request) : void
    {
        parent::addContextParameters($request);
        $this->getApiRequest()->addHeaderParameters(
            $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_HEADER)
                ->add("query", (string) $request->getParam($this->getSearchParameter(), ""))
        );
    }

    /**
     * @return int|null
     */
    public function getSubPhrasesCount(): ?int
    {
        return $this->subPhrasesCount;
    }

    /**
     * @param int $subPhrasesCount
     * @return SearchContextAbstract
     */
    public function setSubPhrasesCount(int $subPhrasesCount): SearchContextAbstract
    {
        $this->subPhrasesCount = $subPhrasesCount;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSubPhrasesProductsCount(): ?int
    {
        return $this->subPhrasesProductsCount;
    }

    /**
     * @param int $subPhrasesProductsCount
     * @return SearchContextAbstract
     */
    public function setSubPhrasesProductsCount(int $subPhrasesProductsCount): SearchContextAbstract
    {
        $this->subPhrasesProductsCount = $subPhrasesProductsCount;
        return $this;
    }

    /**
     * Enforce a dependency type for the SearchContext requests
     *
     * @param RequestDefinitionInterface $requestDefinition
     * @return self
     */
    public function setRequestDefinition(RequestDefinitionInterface $requestDefinition)
    {
        if($requestDefinition instanceof SearchRequestDefinitionInterface)
        {
            return parent::setRequestDefinition($requestDefinition);
        }

        throw new WrongDependencyTypeException("BoxalinoAPIContext: " . get_called_class() .
            " request definition must be an instance of SearchRequestDefinitionInterface");
    }

}
