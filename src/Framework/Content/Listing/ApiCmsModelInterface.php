<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;

/**
 * Class ApiCmsModelInterface
 * Model used for the Boxalino Narrative CMS block
 *
 * @package Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing
 */
interface ApiCmsModelInterface extends ApiResponseViewInterface
{

    /**
     * @return int
     */
    public function getTotalHitCount(): int;
    /**
     * @param int $totalHitCount
     * @return ApiCmsModelInterface
     */
    public function setTotalHitCount(int $totalHitCount): ApiCmsModelInterface;
    /**
     * @return string|null
     */
    public function getNavigationId(): ?string;

    /**
     * @param string|null $navigationId
     * @return ApiCmsModelInterface
     */
    public function setNavigationId(?string $navigationId): ApiCmsModelInterface;

    /**
     * @return \ArrayIterator
     */
    public function getLeft(): \ArrayIterator;

    /**
     * @param \ArrayIterator $left
     * @return ApiCmsModelInterface
     */
    public function setLeft(\ArrayIterator $left): ApiCmsModelInterface;

    /**
     * @return \ArrayIterator
     */
    public function getRight(): \ArrayIterator;

    /**
     * @param \ArrayIterator $right
     * @return ApiCmsModelInterface
     */
    public function setRight(\ArrayIterator $right): ApiCmsModelInterface;

    /**
     * @return \ArrayIterator
     */
    public function getBottom(): \ArrayIterator;

    /**
     * @param \ArrayIterator $bottom
     * @return ApiCmsModelInterface
     */
    public function setBottom(\ArrayIterator $bottom): ApiCmsModelInterface;

    /**
     * @return \ArrayIterator
     */
    public function getTop(): \ArrayIterator;

    /**
     * @param \ArrayIterator $top
     * @return ApiCmsModelInterface
     */
    public function setTop(\ArrayIterator $top): ApiCmsModelInterface;

}
