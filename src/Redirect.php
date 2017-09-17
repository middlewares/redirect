<?php

namespace Middlewares;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Redirect implements MiddlewareInterface
{
    private $redirects = [];
    private $permanent = true;
    private $method = ['GET'];

    /**
     * @param array $redirects
     * @param $redirectCode
     */
    public function __construct($redirects)
    {
        $this->redirects = $redirects;
    }

    /**
     * @param bool $permanent
     * @return $this
     */
    public function permanent($permanent)
    {
        $this->permanent = $permanent;
        return $this;
    }

    /**
     * @param array $method
     * @return $this
     */
    public function method(array $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Process a request and return a response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $uri = $request->getUri()->getPath();
        $query = $request->getUri()->getQuery();
        if (strlen($query) > 0) {
            $uri .= '?' . $query;
        }

        if (!isset($this->redirects[$uri])) {
            return $delegate->process($request);
        }

        if (!in_array($request->getMethod(), $this->method)) {
            return Factory::createResponse(405);
        }

        $responseCode = $this->determineResponseCode($request);
        return Factory::createResponse($responseCode)->withAddedHeader('Location', $this->redirects[$uri]);
    }

    private function determineResponseCode(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET' && $this->permanent === true) {
            return 301;
        }

        if ($request->getMethod() === 'GET' && $this->permanent === false) {
            return 302;
        }

        if ($this->permanent === true) {
            return 308;
        }

        return 307;
    }
}
