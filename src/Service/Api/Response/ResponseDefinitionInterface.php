<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\AccessorHandlerInterface;
use GuzzleHttp\Psr7\Response;

interface ResponseDefinitionInterface
{

    const BOXALINO_PARAMETER_BX_VARIANT_UUID="_bx_variant_uuid";
    const BOXALINO_PARAMETER_CORRECTED_SEARCH_QUERY="correctedSearchQuery";
    const BOXALINO_PARAMETER_MAIN_HIT_COUNT="mainHitCount";
    const BOXALINO_PARAMETER_REDIRECT_URL="redirect_url";
    const BOXALINO_PARAMETER_HAS_SEARCH_SUBPHRASES="hasSearchSubPhrases";
    const BOXALINO_PARAMETER_BX_REQUEST_ID="_bx_request_id";
    const BOXALINO_PARAMETER_BX_GROUP_BY="_bx_group_by";
    const BOXALINO_PARAMETER_SEO_PAGE_TITLE="bx-page-title";
    const BOXALINO_PARAMETER_SEO_BREADCRUMBS="bx-seo-breadcrumbs";
    const BOXALINO_PARAMETER_SEO_PREFIX="bx-seo-";
    const BOXALINO_PARAMETER_SEO_META_TAGS_PREFIX="bx-html-meta-tags-";
    const BOXALINO_PARAMETER_SEO_META_TITLE="bx-html-meta-title";

    /**
     * @return int
     */
    public function getHitCount() : int;

    /**
     * @return string|null
     */
    public function getRedirectUrl() : ?string;

    /**
     * @return bool
     */
    public function isCorrectedSearchQuery() : bool;

    /**
     * @return string|null
     */
    public function getCorrectedSearchQuery() : ?string;

    /**
     * @return bool
     */
    public function hasSearchSubPhrases() : bool;

    /**
     * @return string
     */
    public function getRequestId() : string;

    /**
     * @return string
     */
    public function getGroupBy() : string;

    /**
     * @return string
     */
    public function getVariantId() : string;

    /**
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator;

    /**
     * @return \ArrayIterator
     */
    public function getCorrelations() : \ArrayIterator;

    /**
     * Debug and performance information
     *
     * @return array
     */
    public function getAdvanced() : array;

    /**
     * @return Response
     */
    public function getResponse() : Response;

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response);

    /**
     * @return string
     */
    public function getJson() : string;

    /**
     * @param \StdClass $data
     * @param AccessorInterface $model
     * @return mixed
     */
    public function toObject(\StdClass $data, AccessorInterface $model);

    /**
     * @return AccessorHandlerInterface
     */
    public function getAccessorHandler(): AccessorHandlerInterface;

    /**
     * Adding response positions for your integration
     * (ex: top, left, bottom, right,..)
     * This will make your content accessible in a structured way as the default "blocks" are accessed
     *
     * @param array $positions
     * @return mixed
     */
    public function addResponseSegments(array $positions);

    /**
     * @return array|string[]
     */
    public function getResponseSegments() : array;

    /**
     * The properties configured dynamically in the "SEO Content >> Others" tab of the Narrative
     * @return \ArrayIterator|null
     */
    public function getSeoProperties() : ?\ArrayIterator;

    /**
     * The properties configured dynamically in the "SEO Content >> Meta Tags" tab of the Narrative
     * @return \ArrayIterator|null
     */
    public function getSeoMetaTagsProperties() : ?\ArrayIterator;

    /**
     * @return string|null
     */
    public function getSeoPageMetaTitle() : ?string;

    /**
     * @return string|null
     */
    public function getSeoPageTitle() : ?string;

    /**
     * @return array|null
     */
    public function getSeoBreadcrumbs() : ?array;

    /**
     * Call to reset the object
     *
     * @return ResponseDefinitionInterface
     */
    public function reset() : ResponseDefinitionInterface;

}
