<?php

declare(strict_types=1);

namespace PoP\APIEndpoints;

use PoP\APIEndpoints\EndpointUtils;

abstract class AbstractEndpointHandler
{
    /**
     * Endpoint
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Provide the endpoint
     *
     * @var string
     */
    abstract protected function getEndpoint(): string;

    /**
     * Initialize the client
     *
     * @return void
     */
    public function initialize(): void
    {
        /**
         * Subject to the endpoint having been defined
         */
        if ($this->endpoint = $this->getEndpoint()) {
            // Make sure the endpoint has trailing "/" on both ends
            $this->endpoint = EndpointUtils::slashURI($this->endpoint);
        }
    }

    /**
     * If `true`, the endpoint must exactly match the URL
     * If `false`, the endpoint is triggered when it is contained at the end of the URL
     *
     * @return boolean
     */
    protected function doesEndpointMatchWholeURL(): bool
    {
        return true;
    }

    /**
     * Indicate if the endpoint has been requested
     *
     * @return void
     */
    protected function isEndpointRequested(): bool
    {
        // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
        $uri = EndpointUtils::removeMarkersFromURI($_SERVER['REQUEST_URI']);
        if ($this->doesEndpointMatchWholeURL()) {
            return $uri == $this->endpoint;
        }
        return EndpointUtils::doesURIEndWith($uri, $this->endpoint);
    }
}
