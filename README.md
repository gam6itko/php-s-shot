# php-s-shot-client
api client for https://www.s-shot.ru/ API


## usage

```php
$builder = new UrlBuilder('API_KEY');
$builder->build('https://google.com', [
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
]);
// https://api.s-shot.ru/API_KEY/1024x768/400/JPEG/Z100/T2/D3/JS1/FS0/PX(proxy.com:8080)/CK(CookieString)/RF(my-site.ru)/UA(Internet+Explorer+3.0)/?https://google.com
```
