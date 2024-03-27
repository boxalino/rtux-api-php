<?php
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor;

use ArrayIterator;

/**
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor
 */
interface AccessorFacetModelInterface extends AccessorModelInterface
{
    const BOXALINO_STORE_FACET_PREFIX = "products_";
    const BOXALINO_SYSTEM_FACET_PREFIX = "bx_";
    const BOXALINO_API_FACET_PREFIX = "api_";
    const BOXALINO_BQ_FACET_PREFIX = "bq_";
    const SELECTED_FACET_VALUES_URL_DELIMITER = "|";

    /**
     * @return ArrayIterator
     */
    public function getFacets() : \ArrayIterator;

    /**
     * @param string $position
     * @return \ArrayIterator
     */
    public function getByPosition(string $position) : \ArrayIterator;

    /**
     * @return string | null
     */
    public function getFacetPrefix() : ?string;

    /**
     * @param string $prefix
     */
    public function setFacetPrefix(string $prefix) : void;

}
