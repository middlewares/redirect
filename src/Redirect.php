<?php
declare(strict_types = 1);

namespace Middlewares;

use ArrayAccess;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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
     * Whether return a permanent redirect.
     */
    public function permanent(bool $permanent = true): self
    {
        $this->permanent = $permanent;
        return $this;
    }

    /**
     * Whether include the query to search the url
     */
    public function query(bool $query = true): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Configure the methods in which make the redirection
     */
    public function method(array $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Process a request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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
     */
    private function determineResponseCode(ServerRequestInterface $request): int
    {
        if (in_array($request->getMethod(), ['GET', 'HEAD', 'CONNECT', 'TRACE', 'OPTIONS'])) {
            return $this->permanent ? 301 : 302;
        }

        return $this->permanent ? 308 : 307;
    }
}
