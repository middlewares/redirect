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
        $redirects = [
            '/foo' => '/bar',
            '/posts?id=133' => '/post/133',
        ];

        yield [
            new Redirect($redirects),
            Factory::createServerRequest()->withUri(new Uri('/')),
            200,
            [],
        ];

        yield [
            new Redirect($redirects),
            Factory::createServerRequest()->withUri(new Uri('/foo')),
            301,
            [
                'Location' => ['/bar'],
            ],
        ];

        yield [
            (new Redirect($redirects))->permanent(false),
            Factory::createServerRequest()->withUri(new Uri('/foo')),
            302,
            [
                'Location' => ['/bar'],
            ],
        ];

        yield [
            new Redirect($redirects),
            Factory::createServerRequest()->withUri(new Uri('/foo?bar')),
            200,
            [],
        ];

        yield [
            (new Redirect($redirects))->query(false),
            Factory::createServerRequest()->withUri(new Uri('/foo?bar')),
            301,
            [
                'Location' => ['/bar'],
            ],
        ];

        yield [
            (new Redirect($redirects))->query(false),
            Factory::createServerRequest()->withUri(new Uri('/posts?id=133')),
            200,
            [],
        ];

        yield [
            new Redirect($redirects),
            Factory::createServerRequest()->withUri(new Uri('/posts?id=133')),
            301,
            [
                'Location' => ['/post/133'],
            ],
        ];

        yield [
            new Redirect($redirects),
            Factory::createServerRequest()->withUri(new Uri('/foo'))->withMethod('PUT'),
            405,
            [],
        ];

        yield [
            (new Redirect($redirects))->method(['GET', 'POST']),
            Factory::createServerRequest()->withUri(new Uri('/foo'))->withMethod('POST'),
            308,
            [
                'Location' => ['/bar'],
            ],
        ];

        yield [
            (new Redirect($redirects))->method(['GET', 'POST'])->permanent(false),
            Factory::createServerRequest()->withUri(new Uri('/foo'))->withMethod('POST'),
            307,
            [
                'Location' => ['/bar'],
            ],
        ];
    }

    /**
     * @dataProvider provideRequests
     */
    public function testRedirect(Redirect $redirect, ServerRequestInterface $request, $expectedCode, $expectedHeaders)
    {
        $response = Dispatcher::run([$redirect], $request);

        $this->assertSame($expectedCode, $response->getStatusCode());
        $this->assertSame($expectedHeaders, $response->getHeaders());
    }
}
