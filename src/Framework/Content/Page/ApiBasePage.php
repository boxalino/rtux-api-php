<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Content\Page;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;

/**
 * Class ApiDefaultPage
 * After the API request - stores all information relevant for a view
 * (required by dependency)
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response\Page
 */
class ApiBasePage implements ApiResponseViewInterface
{
    /**
     * @var \ArrayIterator
     */
    protected $blocks;

    /**
     * @var string
     */
    protected $groupBy;

    /**
     * @var bool
     */
    protected $fallback = true;

    /**
     * @var string
     */
    protected $variantUuid;

    /**
     * @var \ArrayIterator
     */
    protected $left;

    /**
     * @var \ArrayIterator
     */
    protected $right;

    /**
     * @var \ArrayIterator
     */
    protected $bottom;

    /**
     * @var \ArrayIterator
     */
    protected $top;

    /**
     * @var \ArrayIterator | null
     */
    protected $seoProperties;

    /**
     * @var \ArrayIterator | null
     */
    protected $seoMetaTagsProperties;

    /**
     * @var string | null
     */
    protected $seoPageTitle;

    /**
     * @var string | null
     */
    protected $seoPageMetaTitle;

    /**
     * @var array | null
     */
    protected $seoBreadcrumbs;

    /**
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator
    {
        return $this->blocks ?? new \ArrayIterator();
    }

    /**
     * @return string
     */
    public function getGroupBy() : string
    {
        return $this->groupBy;
    }

    /**
     * @param \ArrayIterator $blocks
     * @return $this
     */
    public function setBlocks(\ArrayIterator $blocks) : ApiResponseViewInterface
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @param string $groupBy
     * @return $this
     */
    public function setGroupBy(string $groupBy) : ApiResponseViewInterface
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFallback(): bool
    {
        return $this->fallback;
    }

    /**
     * @param bool $fallback
     * @return ApiResponseViewInterface
     */
    public function setFallback(bool $fallback): ApiResponseViewInterface
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariantUuid(): string
    {
        return $this->variantUuid;
    }

    /**
     * @param string $variantUuid
     * @return ApiResponseViewInterface
     */
    public function setVariantUuid(string $variantUuid): ApiResponseViewInterface
    {
        $this->variantUuid = $variantUuid;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getLeft(): \ArrayIterator
    {
        return $this->left;
    }

    /**
     * @param \ArrayIterator $left
     * @return ApiResponseViewInterface
     */
    public function setLeft(\ArrayIterator $left): ApiResponseViewInterface
    {
        $this->left = $left;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getRight(): \ArrayIterator
    {
        return $this->right;
    }

    /**
     * @param \ArrayIterator $right
     * @return ApiResponseViewInterface
     */
    public function setRight(\ArrayIterator $right): ApiResponseViewInterface
    {
        $this->right = $right;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getBottom(): \ArrayIterator
    {
        return $this->bottom;
    }

    /**
     * @param \ArrayIterator $bottom
     * @return ApiResponseViewInterface
     */
    public function setBottom(\ArrayIterator $bottom): ApiResponseViewInterface
    {
        $this->bottom = $bottom;
        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getTop(): \ArrayIterator
    {
        return $this->top;
    }

    /**
     * @param \ArrayIterator $top
     * @return ApiResponseViewInterface
     */
    public function setTop(\ArrayIterator $top): ApiResponseViewInterface
    {
        $this->top = $top;
        return $this;
    }

    /**
     * @return \ArrayIterator|null
     */
    public function getSeoProperties(): ?\ArrayIterator
    {
        return $this->seoProperties;
    }

    /**
     * @param \ArrayIterator|null $seoProperties
     * @return ApiResponseViewInterface
     */
    public function setSeoProperties(?\ArrayIterator $seoProperties): ApiResponseViewInterface
    {
        $this->seoProperties = $seoProperties;
        return $this;
    }

    /**
     * @return \ArrayIterator|null
     */
    public function getSeoMetaTagsProperties(): ?\ArrayIterator
    {
        return $this->seoMetaTagsProperties;
    }

    /**
     * @param \ArrayIterator|null $seoMetaTagsProperties
     * @return ApiResponseViewInterface
     */
    public function setSeoMetaTagsProperties(?\ArrayIterator $seoMetaTagsProperties): ApiResponseViewInterface
    {
        $this->seoMetaTagsProperties = $seoMetaTagsProperties;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoPageTitle(): ?string
    {
        return $this->seoPageTitle;
    }

    /**
     * @param string|null $seoPageTitle
     * @return ApiResponseViewInterface
     */
    public function setSeoPageTitle(?string $seoPageTitle): ApiResponseViewInterface
    {
        $this->seoPageTitle = $seoPageTitle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeoPageMetaTitle(): ?string
    {
        return $this->seoPageMetaTitle;
    }

    /**
     * @param string|null $seoPageMetaTitle
     * @return ApiResponseViewInterface
     */
    public function setSeoPageMetaTitle(?string $seoPageMetaTitle): ApiResponseViewInterface
    {
        $this->seoPageMetaTitle = $seoPageMetaTitle;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getSeoBreadcrumbs(): ?array
    {
        return $this->seoBreadcrumbs;
    }

    /**
     * @param array|null $seoBreadcrumbs
     * @return ApiResponseViewInterface
     */
    public function setSeoBreadcrumbs(?array $seoBreadcrumbs): ApiResponseViewInterface
    {
        $this->seoBreadcrumbs = $seoBreadcrumbs;
        return $this;
    }

}
