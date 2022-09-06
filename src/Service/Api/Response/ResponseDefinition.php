<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Response;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\AccessorHandlerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

/**
 * Class ResponseDefinition
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Response
 */
class ResponseDefinition implements ResponseDefinitionInterface
{

    use ResponseHydratorTrait;

    /**
     * If the facets are declared on a certain position, they are isolated in a specific block
     * All the other content is located under "blocks"
     */
    const BOXALINO_RESPONSE_POSITION = ["left", "right", "sidebar", "top", "bottom"];

    /**
     * @var string
     */
    protected $json;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var null | \StdClass
     */
    protected $data = null;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AccessorHandlerInterface
     */
    protected $accessorHandler = null;

    /**
     * @var null | \ArrayIterator
     */
    protected $blocks = null;

    /**
     * @var null | \ArrayIterator
     */
    protected $correlations = null;

    /**
     * @var null | \ArrayIterator
     */
    protected $seoProperties = null;

    /**
     * @var null | \ArrayIterator
     */
    protected $seoMetaProperties = null;

    /**
     * The visual elements declared with a property "position" different than "main" or none, will have their own
     * RESPONSE SEGMENT by which they can be accessed
     *
     * @var array
     */
    protected $segments = ["top", "right", "bottom", "left"];

    public function __construct(LoggerInterface $logger, AccessorHandlerInterface $accessorHandler)
    {
        $this->logger = $logger;
        $this->accessorHandler = $accessorHandler;
    }

    /**
     * Allows accessing other parameters
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public function __call(string $method, array $params = []) : ?\ArrayIterator
    {
        preg_match('/^(get)(.*?)$/i', $method, $matches);
        $prefix = $matches[1] ?? '';
        $element = $matches[2] ?? '';
        $element = strtolower($element);
        if ($prefix == 'get')
        {
            return $this->getContentByType($element);
        }

        return null;
    }

    /**
     * @return int
     */
    public function getHitCount() : int
    {
        try{
            try {
                if(property_exists($this->get()->system, "mainHitCount"))
                {
                    return $this->get()->system->mainHitCount;
                }
                throw new UndefinedPropertyError("BoxalinoAPI Logical Branch switch.");
            } catch(UndefinedPropertyError $exception)
            {
                foreach($this->getBlocks() as $block)
                {
                    try{
                        $object = $this->findObjectWithProperty($block, "bxHits");
                        if(is_null($object))
                        {
                            return 0;
                        }

                        return $object->getBxHits()->getTotalHitCount();
                    } catch (\Exception $exception)
                    {
                        $this->logger->info($exception->getMessage());
                        continue;
                    }
                }
            }

            return 0;
        } catch(\Exception $exception)
        {
            return 0;
        }

        return 0;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl() : ?string
    {
        try{
            $index = 0;
            if(property_exists($this->get()->advanced->$index, ResponseDefinitionInterface::BOXALINO_PARAMETER_REDIRECT_URL))
            {
                return $this->get()->advanced->$index->redirect_url;
            }

            return null;
        } catch(\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isCorrectedSearchQuery() : bool
    {
        try{
            if(property_exists($this->get()->system, ResponseDefinitionInterface::BOXALINO_PARAMETER_CORRECTED_SEARCH_QUERY))
            {
                return (bool) $this->get()->system->correctedSearchQuery;
            }

            return false;
        } catch(\Exception $exception)
        {
            return false;
        }
    }

    /**
     * @return string | null
     */
    public function getCorrectedSearchQuery() : ?string
    {
        try{
            if(property_exists($this->get()->system, ResponseDefinitionInterface::BOXALINO_PARAMETER_CORRECTED_SEARCH_QUERY))
            {
                return $this->get()->system->correctedSearchQuery;
            }

            return null;
        } catch(\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function hasSearchSubPhrases() : bool
    {
        try{
            if(property_exists($this->get()->system, ResponseDefinitionInterface::BOXALINO_PARAMETER_HAS_SEARCH_SUBPHRASES))
            {
                return (bool) $this->get()->system->hasSearchSubPhrases;
            }

            return false;
        } catch(\Exception $exception)
        {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getRequestId() : string
    {
        try{
            $index = 0;
            if(property_exists($this->get()->advanced->$index, ResponseDefinitionInterface::BOXALINO_PARAMETER_BX_REQUEST_ID))
            {
                return $this->get()->advanced->$index->_bx_request_id;
            }

            return "N/A";
        } catch(\Exception $exception)
        {
            return "N/A";
        }
    }

    /**
     * @return string
     */
    public function getGroupBy() : string
    {
        try{
            $index = 0;
            if(property_exists($this->get()->advanced->$index, ResponseDefinitionInterface::BOXALINO_PARAMETER_BX_GROUP_BY))
            {
                return $this->get()->advanced->$index->_bx_group_by;
            }

            return "N/A";
        } catch(\Exception $exception)
        {
            return "N/A";
        }
    }

    /**
     * @return string
     */
    public function getVariantId() : string
    {
        try{
            $index = 0;
            if(property_exists($this->get()->advanced->$index, ResponseDefinitionInterface::BOXALINO_PARAMETER_BX_VARIANT_UUID))
            {
                return $this->get()->advanced->$index->_bx_variant_uuid;
            }

            return "N/A";
        } catch(\Exception $exception)
        {
            return "N/A";
        }
    }

    /**
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator
    {
        if(is_null($this->blocks))
        {
            $this->blocks = $this->getContentByType("blocks");
        }

        return $this->blocks;
    }

    /**
     * @return \ArrayIterator
     */
    public function getCorrelations() : \ArrayIterator
    {
        if(is_null($this->correlations))
        {
            $this->correlations = new \ArrayIterator();
            try{
                foreach($this->get()->correlations as $correlation)
                {
                    $this->correlations->append($this->toObject($correlation, $this->getAccessorHandler()->getAccessor("correlations")));
                }
            } catch (\Throwable $exception)
            {
                $this->log("BoxalinoResponseAPI: Something when wrong when accessing the correlations. Error :" . $exception->getMessage());
            }
        }

        return $this->correlations;
    }

    /**
     * @param string $type
     * @return \ArrayIterator
     */
    public function getContentByType(string $type) : \ArrayIterator
    {
        $content = new \ArrayIterator();
        try{
            if(property_exists($this->get(), $type))
            {
                $blocks = $this->get()->$type;
                foreach($blocks as $block)
                {
                    $content->append($this->getBlockObject($block));
                }
            }
        } catch (\ErrorException $error)
        {
            /** there is no layout position for the narrative, not an issue */
        } catch (\Exception $error)
        {
            $this->logger->warning("BoxalinoResponseAPI: Something went wrong during content extract for $type: " . $error->getMessage());
        }

        return $content;
    }

    /**
     * @param \StdClass $block
     * @return AccessorInterface
     */
    public function getBlockObject(\StdClass $block) : AccessorInterface
    {
        return $this->toObject($block, $this->getAccessorHandler()->getAccessor("blocks"));
    }

    /**
     * Debug and performance information
     *
     * @return array
     */
    public function getAdvanced() : array
    {
        try{
            $index = 0;
            return array_merge($this->get()->performance, $this->get()->advanced->$index);
        } catch(\Exception $exception)
        {
            return $this->get()->performance;
        }
    }

    /**
     * @return \ArrayIterator|null
     */
    public function getSeoProperties() : ?\ArrayIterator
    {
        try{
            if(is_null($this->seoProperties))
            {
                $this->seoProperties = $this->getPropertyByPrefix(ResponseDefinitionInterface::BOXALINO_PARAMETER_SEO_PREFIX);
            }

            return $this->seoProperties;
        } catch(\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @return \ArrayIterator|null
     */
    public function getSeoMetaTagsProperties() : ?\ArrayIterator
    {
        try{
            if(is_null($this->seoMetaProperties))
            {
                $this->seoMetaProperties = $this->getPropertyByPrefix(ResponseDefinitionInterface::BOXALINO_PARAMETER_SEO_META_TAGS_PREFIX);
            }

            return $this->seoMetaProperties;
        } catch(\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getSeoPageTitle() : ?string
    {
        try{
            $index = 0;
            $property = ResponseDefinitionInterface::BOXALINO_PARAMETER_SEO_PAGE_TITLE;
            if(property_exists($this->get()->advanced->$index, $property))
            {
                return $this->get()->advanced->$index->$property;
            }

            return null;
        } catch(\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getSeoPageMetaTitle() : ?string
    {
        try{
            $index = 0;
            $property = ResponseDefinitionInterface::BOXALINO_PARAMETER_SEO_META_TITLE;
            if(property_exists($this->get()->advanced->$index, $property))
            {
                return $this->get()->advanced->$index->$property;
            }

            return null;
        } catch(\Exception $exception)
        {
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function getSeoBreadcrumbs() : ?array
    {
        try{
            $index = 0;
            $property = ResponseDefinitionInterface::BOXALINO_PARAMETER_SEO_BREADCRUMBS;
            if(property_exists($this->get()->advanced->$index, $property))
            {
                return json_decode($this->get()->advanced->$index->$property->value, true);
            }

            return [];
        } catch(\Exception $exception)
        {
            return [];
        }
    }

    /**
     * @param string $prefix
     * @return \ArrayIterator
     */
    protected function getPropertyByPrefix(string $prefix) : \ArrayIterator
    {
        $index = 0;
        $dataAsObject = new \ReflectionObject($this->get()->advanced->$index);
        $properties = $dataAsObject->getProperties();
        $returnObject = new \ArrayIterator();
        foreach($properties as $property)
        {
            $propertyName = $property->getName();
            if(strpos((string)$propertyName, $prefix) === 0)
            {
                $element = $this->get()->advanced->$index->$propertyName;
                $returnObject->offsetSet($element->name, $element->value);
            }
        }

        return $returnObject;
    }

    /**
     * @return \StdClass|null
     */
    public function get() : ?\StdClass
    {
        if(is_null($this->data))
        {
            $this->data = json_decode($this->json);
        }

        return $this->data;
    }

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        $this->setJson($response->getBody()->getContents());

        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse() : Response
    {
        return $this->response;
    }

    /**
     * @param string $json
     * @return $this
     */
    public function setJson(string $json)
    {
        $this->json = $json;
        return $this;
    }

    /**
     * @return string
     */
    public function getJson() : string
    {
        return json_encode(json_decode($this->json), JSON_PRETTY_PRINT);
    }

    /**
     * @return AccessorHandlerInterface
     */
    public function getAccessorHandler(): AccessorHandlerInterface
    {
        return $this->accessorHandler;
    }

    /**
     * @param array $segments
     * @return $this
     */
    public function addResponseSegments(array $segments)
    {
        foreach($segments as $segment)
        {
            if(isset($this->segments[$segment]))
            {
                continue;
            }

            $this->segments[] = $segment;
        }

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getResponseSegments() : array
    {
        return $this->segments;
    }

    /**
     * Resets the API response
     *
     * @return ResponseDefinitionInterface
     */
    public function reset() : ResponseDefinitionInterface
    {
        $this->data = null;
        $this->blocks = null;
        $this->seoMetaProperties = null;
        $this->seoMetaProperties = null;

        return $this;
    }

}
