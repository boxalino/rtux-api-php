<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Framework\Content\Page;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ApiResponseViewInterface;

/**
 * Class ApiResponsePage
 * After the API request - stores all information relevant for a view
 * (required by dependency)
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response\Page
 */
class ApiResponsePage extends ApiBasePage
    implements ApiResponsePageInterface
{
    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var bool
     */
    protected $hasSearchSubPhrases = false;

    /**
     * @var string | null
     */
    protected $redirectUrl;

    /**
     * @var int
     */
    protected $totalHitCount = 0;

    /**
     * @var string
     */
    protected $searchTerm;


    /**
     * @return string
     */
    public function getRequestId() : string
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     * @return $this
     */
    public function setRequestId(string $requestId) : ApiResponseViewInterface
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSearchSubPhrases(): bool
    {
        return $this->hasSearchSubPhrases;
    }

    /**
     * @param bool $hasSearchSubPhrases
     * @return ApiResponseViewInterface
     */
    public function setHasSearchSubPhrases(bool $hasSearchSubPhrases)
    {
        $this->hasSearchSubPhrases = $hasSearchSubPhrases;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * @param string|null $redirectUrl
     * @return ApiResponseViewInterface
     */
    public function setRedirectUrl(?string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalHitCount(): int
    {
        return $this->totalHitCount;
    }

    /**
     * @param int $totalHitCount
     * @return ApiResponseViewInterface
     */
    public function setTotalHitCount(int $totalHitCount)
    {
        $this->totalHitCount = $totalHitCount;
        return $this;
    }

    /**
     * @param string $searchTerm
     * @return $this
     */
    public function setSearchTerm(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchTerm() : string
    {
        return $this->searchTerm;
    }

}
