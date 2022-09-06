<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response;

/**
 * Interface ApiResponseViewInterface
 * A base response view with the required elements
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response
 */
interface ApiResponseViewInterface
{

    /**
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator;

    /**
     * @param \ArrayIterator $blocks
     * @return $this
     */
    public function setBlocks(\ArrayIterator $blocks) : ApiResponseViewInterface;

    /**
     * @return \ArrayIterator
     */
    public function getCorrelations() : \ArrayIterator;

    /**
     * @param \ArrayIterator $correlations
     * @return $this
     */
    public function setCorrelations(\ArrayIterator $correlations) : ApiResponseViewInterface;

    /**
     * @return string
     */
    public function getGroupBy() : string;

    /**
     * @param string $groupBy
     * @return $this
     */
    public function setGroupBy(string $groupBy) : ApiResponseViewInterface;

    /**
     * @return bool
     */
    public function isFallback(): bool;

    /**
     * @param bool $fallback
     * @return $this
     */
    public function setFallback(bool $fallback) : ApiResponseViewInterface;

    /**
     * @return string
     */
    public function getVariantUuid(): string;

    /**
     * @param string $variantUuid
     * @return $this
     */
    public function setVariantUuid(string $variantUuid) : ApiResponseViewInterface;

    /**
     * @return \ArrayIterator
     */
    public function getLeft(): \ArrayIterator;

    /**
     * @param \ArrayIterator $left
     * @return ApiResponseViewInterface
     */
    public function setLeft(\ArrayIterator $left): ApiResponseViewInterface;

    /**
     * @return \ArrayIterator
     */
    public function getRight(): \ArrayIterator;

    /**
     * @param \ArrayIterator $right
     * @return ApiResponseViewInterface
     */
    public function setRight(\ArrayIterator $right): ApiResponseViewInterface;

    /**
     * @return \ArrayIterator
     */
    public function getBottom(): \ArrayIterator;

    /**
     * @param \ArrayIterator $bottom
     * @return ApiResponseViewInterface
     */
    public function setBottom(\ArrayIterator $bottom): ApiResponseViewInterface;

    /**
     * @return \ArrayIterator
     */
    public function getTop(): \ArrayIterator;

    /**
     * @param \ArrayIterator $top
     * @return ApiResponseViewInterface
     */
    public function setTop(\ArrayIterator $top): ApiResponseViewInterface;

    /**
     * @return \ArrayIterator|null
     */
    public function getSeoProperties(): ?\ArrayIterator;

    /**
     * @param \ArrayIterator|null $seoProperties
     * @return ApiResponseViewInterface
     */
    public function setSeoProperties(?\ArrayIterator $seoProperties): ApiResponseViewInterface;

    /**
     * @return \ArrayIterator|null
     */
    public function getSeoMetaTagsProperties(): ?\ArrayIterator;

    /**
     * @param \ArrayIterator|null $seoMetaTagsProperties
     * @return ApiResponseViewInterface
     */
    public function setSeoMetaTagsProperties(?\ArrayIterator $seoMetaTagsProperties): ApiResponseViewInterface;

    /**
     * @return string|null
     */
    public function getSeoPageTitle(): ?string;

    /**
     * @param string|null $seoPageTitle
     * @return ApiResponseViewInterface
     */
    public function setSeoPageTitle(?string $seoPageTitle): ApiResponseViewInterface;

    /**
     * @return string|null
     */
    public function getSeoPageMetaTitle(): ?string;

    /**
     * @param string|null $seoPageMetaTitle
     * @return ApiResponseViewInterface
     */
    public function setSeoPageMetaTitle(?string $seoPageMetaTitle): ApiResponseViewInterface;

    /**
     * @return array|null
     */
    public function getSeoBreadcrumbs(): ?array;

    /**
     * @param array|null $seoBreadcrumbs
     * @return ApiResponseViewInterface
     */
    public function setSeoBreadcrumbs(?array $seoBreadcrumbs): ApiResponseViewInterface;


}
