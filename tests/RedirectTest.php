<?php

namespace Middlewares\Tests;

use Middlewares\Redirect;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Uri;

class RedirectTest extends TestCase
{
    public function provideRequests()
    {
        yield [
            Factory::createServerRequest()->withUri(new Uri('/')),
            200,
            [],
        ];

        yield [
            Factory::createServerRequest()->withUri(new Uri('/foo')),
            301,
            [
                'Location' => ['/bar'],
            ],
        ];

        yield [
            Factory::createServerRequest()->withUri(new Uri('/foo?bar')),
            301,
            [
                'Location' => ['/bar'],
            ],
        ];
    }

    /**
     * @dataProvider provideRequests
     */
    public function testRedirect(ServerRequestInterface $request, $expectedCode, $expectedHeaders)
    {
        $redirects = [
            '/foo' => '/bar',
        ];

        $response = Dispatcher::run([
            new Redirect($redirects),
        ], $request);

        $this->assertSame($expectedCode, $response->getStatusCode());
        $this->assertSame($expectedHeaders, $response->getHeaders());
    }
}
