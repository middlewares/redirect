<?php

namespace Middlewares;

use ArrayAccess;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use InvalidArgumentException;
use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Redirect implements MiddlewareInterface
{
    private $redirects = [];
    private $permanent = true;
    private $query = true;
    private $method = ['GET'];

    /**
     * @param array|ArrayAccess $redirects [from => to]
     */
    public function __construct($redirects)
    {
        if (!is_array($redirects) && !($redirects instanceof ArrayAccess)) {
            throw new InvalidArgumentException(
                'The redirects argument must be an array or implement the ArrayAccess interface'
            );
        }

        $this->redirects = $redirects;
    }

    /**
     * @param  bool  $permanent
     * @return $this
     */
    public function permanent($permanent = true)
    {
        $this->permanent = $permanent;
        return $this;
    }

    /**
     * @param  bool  $query
     * @return $this
     */
    public function query($query = true)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param  array $method
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
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $uri = $request->getUri()->getPath();
        $query = $request->getUri()->getQuery();

        if ($this->query && strlen($query) > 0) {
            $uri .= '?' . $query;
        }

        if (!isset($this->redirects[$uri])) {
            return $handler->handle($request);
        }

        if (!in_array($request->getMethod(), $this->method)) {
            return Factory::createResponse(405);
        }

        $responseCode = $this->determineResponseCode($request);
        return Factory::createResponse($responseCode)->withAddedHeader('Location', $this->redirects[$uri]);
    }

    /**
     * Determine the response code according with the method and the permanent config
     * @param  ServerRequestInterface $request
     * @return int
     */
    private function determineResponseCode(ServerRequestInterface $request)
    {
        if (in_array($request->getMethod(), ['GET', 'HEAD', 'CONNECT', 'TRACE', 'OPTIONS'])) {
            return $this->permanent ? 301 : 302;
        }

        return $this->permanent ? 308 : 307;
    }
}
