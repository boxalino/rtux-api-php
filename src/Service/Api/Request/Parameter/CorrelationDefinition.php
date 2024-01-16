<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Parameter;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterDefinition;

/**
 * Class CorrelationDefinition
 *
 * Adds correlations to the request (depends on context and integration)
 * A correlation is defined by: origin, type, source
 *
 * In the API response, a new JSON property is added to the node: correlations (list)
 * The parsing of the list, as well as the BA awareness of what the list represents, is defined in the integration layer
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
#[\AllowDynamicProperties]
class CorrelationDefinition extends ParameterDefinition
{

    CONST BOXALINO_REQUEST_CORRELATION_ORIGIN_RTUX = "rtux-data-integration";
    CONST BOXALINO_REQUEST_CORRELATION_TYPE_DOC_ATTR_VALUE= "doc_attribute_value";

    /**
     * @param string $source
     * @param string|null $origin
     * @param string|null $type
     * @return $this
     */
    public function add(string $source, ?string $origin = self::BOXALINO_REQUEST_CORRELATION_ORIGIN_RTUX, ?string $type = self::BOXALINO_REQUEST_CORRELATION_TYPE_DOC_ATTR_VALUE) : self
    {
        $this->origin = $origin;
        $this->type = $type;
        $this->source = $source;

        return $this;
    }


}
