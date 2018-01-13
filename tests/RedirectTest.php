<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use InvalidArgumentException;
use Middlewares\Redirect;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    public function testUnknowUrl()
    {
        $response = Dispatcher::run(
            [
                new Redirect(['/foo' => '/bar']),
            ],
            Factory::createServerRequest([], 'GET', '/')
        );

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testRedirect()
    {
        $response = Dispatcher::run(
            [
                new Redirect(['/foo' => '/bar']),
            ],
            Factory::createServerRequest([], 'GET', '/foo')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('/bar', $response->getHeaderLine('Location'));
    }

    public function testPermanentRedirect()
    {
        $response = Dispatcher::run(
            [
                (new Redirect(['/foo' => '/bar']))
                    ->permanent(),
            ],
            Factory::createServerRequest([], 'GET', '/foo')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('/bar', $response->getHeaderLine('Location'));
    }

    public function testQuery()
    {
        $response = Dispatcher::run(
            [
                new Redirect(['/foo' => '/bar']),
            ],
            Factory::createServerRequest([], 'GET', '/foo?bar')
        );

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testIgnoreQuery()
    {
        $response = Dispatcher::run(
            [
                (new Redirect(['/foo' => '/bar']))->query(false),
            ],
            Factory::createServerRequest([], 'GET', '/foo?bar')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('/bar', $response->getHeaderLine('Location'));
    }

    public function testQueryMatch()
    {
        $response = Dispatcher::run(
            [
                new Redirect(['/posts?id=133' => '/post/133']),
            ],
            Factory::createServerRequest([], 'GET', '/posts?id=133')
        );

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('/post/133', $response->getHeaderLine('Location'));
    }

    public function testInvalidPutMethod()
    {
        $response = Dispatcher::run(
            [
                new Redirect(['/foo' => '/bar']),
            ],
            Factory::createServerRequest([], 'PUT', '/foo')
        );

        $this->assertSame(405, $response->getStatusCode());
    }

    public function testRedirectPostMethod()
    {
        $response = Dispatcher::run(
            [
                (new Redirect(['/foo' => '/bar']))->method(['GET', 'POST']),
            ],
            Factory::createServerRequest([], 'POST', '/foo')
        );

        $this->assertSame(308, $response->getStatusCode());
        $this->assertSame('/bar', $response->getHeaderLine('Location'));
    }

    public function testTemporaryRedirectPostMethod()
    {
        $response = Dispatcher::run(
            [
                (new Redirect(['/foo' => '/bar']))
                    ->method(['GET', 'POST'])
                    ->permanent(false),
            ],
            Factory::createServerRequest([], 'POST', '/foo')
        );

        $this->assertSame(307, $response->getStatusCode());
        $this->assertSame('/bar', $response->getHeaderLine('Location'));
    }

    public function testInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);

        new Redirect('not-valid');
    }
}
