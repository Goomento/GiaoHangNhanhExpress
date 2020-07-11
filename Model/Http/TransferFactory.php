<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Http;

use Goomento\Base\Http\TransferBuilder;
use Goomento\Base\Http\TransferFactoryInterface;
use Goomento\GiaoHangNhanhExpress\Helper\Config;
use Magento\Framework\HTTP\ZendClient;
use Magento\Framework\Serialize\JsonConverter;

/**
 * Class TransferFactory
 * @package Goomento\GiaoHangNhanhExpress\Model\Http
 */
class TransferFactory implements TransferFactoryInterface
{
    /**
     * @var TransferBuilder
     */
    protected $transferBuilder;

    const BODY = 'body';
    const HEADER = 'header';
    const URI = 'uri';
    const METHOD = 'method';

    public function __construct(
        TransferBuilder $transferBuilder
    ) {
        $this->transferBuilder = $transferBuilder;
    }

    public function create(array $request)
    {
        return $this->transferBuilder
            ->setUri($this->getEndpoint(self::readUri($request)))
            ->setHeaders(array_merge(
                [
                    'Token' => Config::staticConfigGet('token'),
                    'Content-Type' => 'application/json',
                ],
                self::readHeader($request)
            ))
            ->setBody(JsonConverter::convert(self::readBody($request)))
            ->setMethod(self::readMethod($request))
            ->build();
    }

    public function getEndpoint(string $uri)
    {
        return Config::staticApiUrl() . '/' . ltrim($uri, '\\/');
    }

    public static function readUri(array $data)
    {
        return $data[self::URI] ?? null;
    }

    public static function readBody(array $data)
    {
        return $data[self::BODY] ?? [];
    }

    public static function readHeader(array $data)
    {
        return $data[self::HEADER] ?? [];
    }

    public static function readMethod(array $data)
    {
        return $data[self::METHOD] ?? ZendClient::GET;
    }
}
