<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Context\ListingContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;

/**
 * Class CmsContextAbstract
 * Boxalino Cms Request handler
 *
 * Allows to set the configurations from the Boxalino Narrative CMS block
 * (facets, filters, sidebar, etc)
 *
 * @package Boxalino\RealTimeUserExperienceApi\Framework\Request
 */
abstract class CmsContextAbstract
    extends ListingContextAbstract
    implements ListingContextInterface
{

    /**
     * Adding general filters
     * Add the category filter (if any)
     * Adding configured filters (if any)
     *
     * @param RequestInterface $request
     */
    protected function addFilters(RequestInterface $request) : void
    {
        $this->getApiRequest()
            ->setHitCount($this->getHitCount())
            ->addFilters(
                $this->getVisibilityFilter($request),
                $this->getActiveFilter($request)
            );

        $categoryIds = $this->getContextNavigationId($request);
        if(!empty($categoryIds))
        {
            $this->getApiRequest()->addFilters(
                $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FILTER)
                    ->add("category_id", $categoryIds)
            );
        }

        if($this->has("filters"))
        {
            $configuredFilters = explode(",", $this->getProperty("filters"));
            foreach($configuredFilters as $filter)
            {
                $params = explode("=", $filter);
                $this->getApiRequest()->addFilters(
                    $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FILTER)
                        ->add($params[0], array_map("html_entity_decode",  explode("|", $params[1])))
                );
            }
        }
    }

    /**
     * Adding the requested facets (if allowed)
     * Adding configured facets (if any)
     *
     * @param RequestInterface $request
     * @return SearchContextAbstract
     */
    public function addFacets(RequestInterface $request): ListingContextAbstract
    {
        if($this->has("facets"))
        {
            $configuredFacets = explode(",", $this->getProperty("facets"));
            foreach($configuredFacets as $facet)
            {
                $params = explode("=", $facet);
                if (in_array($params[0], array_keys($this->getRangeProperties())))
                {
                    continue;
                }
                $this->getApiRequest()->addFacets(
                    $this->_getApiRequestFacet(
                        $params[0],
                        array_map("html_entity_decode",  explode("|", $params[1]))
                    )
                );
            }
        }

        if($this->getProperty("applyRequestParams"))
        {
            parent::addFacets($request);
        }

        return $this;
    }

    /**
     * Add the configured sort options only if there is not a selected sort option already
     *
     * @param RequestInterface $request
     */
    public function addSort(RequestInterface $request) : void
    {
        $existingSortInRequest = $request->getParam($this->requestTransformer->getSortParameter(), null);
        if($this->has("sort") && is_null($existingSortInRequest))
        {
            /** @var string $sortField */
            $sortKey= $this->getProperty("sort");
            foreach($this->requestTransformer->getSortingByKey($sortKey) as $sort)
            {
                $this->getApiRequest()->addSort(
                    $this->parameterFactory->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_SORT)
                        ->add($sort["field"], $sort["reverse"])
                );
            }

            return;
        }

        parent::addSort($request);
    }

    /**
     * If there are configured returnFields in the CMS element - they will be used
     *
     * @return array
     */
    public function getReturnFields() : array
    {
        if($this->has("returnFields"))
        {
            return $this->getProperty("returnFields");
        }

        return ["id", "products_group_id"];
    }

    /**
     * If there is configured hitCount in the CMS element - they will be used
     *
     * @return int
     */
    public function getHitCount() : int
    {
        if($this->has("hitCount"))
        {
            return (int) $this->getProperty("hitCount");
        }

        return parent::getHitCount();
    }


}
