<?php

declare(strict_types=1);

namespace Tests\Gam6itko\SShot;

use Gam6itko\SShot\UrlBuilder;
use Gam6itko\SShot\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\SShot\UrlBuilder
 */
class UrlBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @dataProvider dataBuild
     */
    public function testBuild(array $options, string $expected)
    {
        self::assertSame($expected, (new UrlBuilder('API_KEY'))->build('https://google.com', $options));
    }

    public function dataBuild(): iterable
    {
        yield [
            [
                'resolution' => '1280x720',
            ],
            'https://api.s-shot.ru/API_KEY/1280x720/?https://google.com',
        ];

        yield [
            [
                'resolution' => 1280,
                'size' => 200,
            ],
            'https://api.s-shot.ru/API_KEY/1280/200/?https://google.com',
        ];

        yield [
            [
                'resolution' => '1024x768',
                'size' => '400',
                'format' => 'jpeg',
                'scale' => 100,
                'timeout' => 2,
                'delay' => 3,
                'jsSupport' => true,
                'flashSupport' => true,
                'proxy' => 'proxy.com:8080',
                'cookies' => 'CookieString',
                'referer' => 'my-site.ru',
                'userAgent' => 'Internet Explorer 3.0',
            ],
            'https://api.s-shot.ru/API_KEY/1024x768/400/JPEG/Z100/T2/D3/JS1/FS0/PX(proxy.com:8080)/CK(CookieString)/RF(my-site.ru)/UA(Internet Explorer 3.0)/?https://google.com'
        ];
    }

    /**
     * @dataProvider dataBuildValidationException
     */
    public function testBuildValidationException(array $options)
    {
        self::expectException(ValidationException::class);
        (new UrlBuilder('API_KEY'))->build('https://google.com', $options);
    }

    public function dataBuildValidationException(): iterable
    {
        yield [
            [
                'resolution' => '1280x',
            ],
        ];

        yield [
            [
                'resolution' => 'asd',
            ],
        ];

        yield [
            [
                'jpeg' => 'tiff',
            ],
        ];
    }
}
