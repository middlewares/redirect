<?php

namespace Middlewares;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

final class Redirect implements MiddlewareInterface
{
    private $redirects = [];
    private $redirectCode;

    /**
     * @param array $redirects
     * @param $redirectCode
     */
    public function __construct(array $redirects, $redirectCode = 301)
    {
        $this->redirects = $redirects;
        $this->redirectCode = $redirectCode;
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
        $path = $request->getUri()->getPath();
        if (isset($this->redirects[$path])) {
            return Factory::createResponse($this->redirectCode)->withAddedHeader('Location', $this->redirects[$path]);
        }

        return $delegate->process($request);
    }
}
