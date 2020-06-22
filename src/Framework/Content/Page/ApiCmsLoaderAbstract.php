<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Content\Page;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\CreateFromTrait;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiCmsModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCallServiceInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Block;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ApiCmsLoaderAbstract
 * Sample based on a familiar block component
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Content\Page
 */
abstract class ApiCmsLoaderAbstract extends ApiLoaderAbstract
{
    use CreateFromTrait;

    /**
     * @var array
     */
    protected $cmsConfig = [];

    /**
     * Loads the content of an API Response page
     */
    public function load() : ApiLoaderInterface
    {
        $this->addProperties();
        $this->call();

        /** @var ApiCmsModelInterface $page */
        $page = $this->getApiResponsePage();
        $page->setBlocks($this->apiCallService->getApiResponse()->getBlocks())
            ->setLeft($this->apiCallService->getApiResponse()->getLeft())
            ->setTop($this->apiCallService->getApiResponse()->getTop())
            ->setBottom($this->apiCallService->getApiResponse()->getBottom())
            ->setRight($this->apiCallService->getApiResponse()->getRight())
            ->setRequestId($this->apiCallService->getApiResponse()->getRequestId())
            ->setGroupBy($this->getGroupBy())
            ->setVariantUuid($this->getVariantUuid())
            ->setNavigationId($this->getNavigationId($this->getRequest()))
            ->setTotalHitCount($this->apiCallService->getApiResponse()->getHitCount());

        $this->setApiResponsePage($page);

        return $this;
    }


    /**
     * @return ApiResponseViewInterface | null
     */
    abstract public function getApiResponsePage() : ?ApiResponseViewInterface;

    /**
     * Accessing the navigation/page ID
     * @param RequestInterface $request
     * @return string
     */
    abstract protected function getNavigationId(RequestInterface $request) : string;

    /**
     * @param array $config
     * @return $this
     */
    public function setCmsConfig(array $config)
    {
        $this->cmsCconfig = $config;
        return $this;
    }

    /**
     * @return array
     */
    public function getCmsConfig() : array
    {
        return $this->cmsConfig;
    }

    /**
     * Adds properties to the CmsContextAbstract
     */
    protected function addProperties()
    {
        foreach($this->getCmsConfig() as $key => $value)
        {
            if($key == 'widget')
            {
                $this->apiContextInterface->setWidget($value);
                continue;
            }
            if($key == 'hitCount')
            {
                $this->apiContextInterface->setHitCount((int) $value);
                continue;
            }
            if($key == 'groupBy')
            {
                $this->apiContextInterface->setGroupBy($value);
                continue;
            }

            if(!is_null($value) && !empty($value))
            {
                $this->apiContextInterface->set($key, $value);
            }
        }
    }

    /**
     * This function can be used to access parts of the response
     * and isolate them in different sections
     * ex: a single narrative request on a page with 3 sections
     *
     * @param string $property
     * @param string $value
     * @param string $segment
     * @return \ArrayIterator
     */
    public function getBlocksByPropertyValue(string $property, string $value, string $segment = 'blocks') : \ArrayIterator
    {
        $newSectionBlocks = new \ArrayIterator();
        $responseSegmentGetter = "get" . ucfirst($segment);
        $blocks = $this->apiCallService->getApiResponse()->$responseSegmentGetter();
        /** @var Block $block */
        foreach($blocks as $index => $block)
        {
            try{
                $functionName = "get".ucfirst($property);
                $propertyValue = $block->$functionName();
                if($propertyValue[0] == $value)
                {
                    $newSectionBlocks->append($block);
                    $blocks->offsetUnset($index);
                }
            } catch (UndefinedPropertyError $exception)
            {
                continue;
            }
        }

        return $newSectionBlocks;
    }

}
