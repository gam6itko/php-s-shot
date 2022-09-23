<?php

declare(strict_types=1);

namespace Gam6itko\SShot;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class UrlBuilder
{
    private const HOST = 'https://api.s-shot.ru';

    private const OPTIONS = [
        'resolution',
        'size',
        'scale',
        'format', //JPEG,PNG
        'timeout',
        'delay',
        'jsSupport',
        'flashSupport',
        'proxy',
        'cookies',
        'referer',
        'userAgent',
    ];

    private string $apiKey;
    private array $defaultOptions;

    public function __construct(string $apiKey, array $defaultOptions = [])
    {
        $this->apiKey = $apiKey;
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * @throws ValidationException
     */
    public function build(string $url, array $options = []): string
    {
        $this->validateOptions(array_merge($this->defaultOptions, $options));

        $parts = [
            self::HOST,
            $this->apiKey,
        ];

        foreach ($options as $key => $value) {
            $parts[] = $this->normalizeOptionValue($key, (string) $value);
        }

        $parts[] = "?$url";

        return implode('/', $parts);
    }

    private function normalizeOptionValue(string $key, string $value): string
    {
        switch ($key) {
            case 'format':
                return strtoupper($value);
            case 'scale':
                return "Z$value";
            case 'timeout':
                return "T$value";
            case 'delay':
                return "D$value";
            case 'jsSupport':
                return 'JS1';
            case 'flashSupport':
                return 'FS0';
            case 'proxy':
                return "PX($value)";
            case 'cookies':
                return "CK($value)";
            case 'referer':
                return "RF($value)";
            case 'userAgent':
                return "UA($value)";
            default:
                return $value;
        }
    }

    private function validateOptions(array $options): void
    {
        if (!$options) {
            return;
        }

        if ($unexpected = array_diff(array_keys($options), self::OPTIONS)) {
            throw new ValidationException('Unexpected options: '.implode(', ', $unexpected));
        }

        foreach ($this->getValidationRules() as $name => $fn) {
            if (isset($options[$name]) && false === call_user_func($fn, $options[$name])) {
                throw new ValidationException(sprintf('Incorrect value "%s" of property "%s"', $options[$name], $name));
            }
        }
    }

    private function getValidationRules(): array
    {
        return [
            'resolution' => function ($v): bool {
                if (is_string($v) && strpos($v, 'x')) {
                    $pair = array_filter(explode('x', $v));
                    if (2 !== count($pair)) {
                        return false;
                    }
                    return array_reduce($pair, fn ($c, $v) => is_numeric($v) && $c, true);
                }
                return is_numeric($v);
            },
            'size' => 'is_numeric',
            'scale' => 'is_numeric',
            'format' => fn ($v) => in_array(strtolower($v), ['jpeg', 'png']), //JPEG,PNG
            'timeout' => 'is_numeric',
            'delay' => 'is_numeric',
            'jsSupport' => 'is_bool',
            'flashSupport' => 'is_bool',
        ];
    }
}
