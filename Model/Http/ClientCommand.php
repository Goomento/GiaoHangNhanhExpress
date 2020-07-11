<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Model\Http;

use Goomento\Base\Helper\Cache;
use Goomento\Base\Http\ClientException;
use Goomento\Base\Http\ClientInterface;
use Goomento\Base\Model\BuilderInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ClientCommand
 * @package Goomento\GiaoHangNhanhExpress\Model\Http
 */
class ClientCommand implements ClientCommandInterface
{
    /**
     * @var BuilderInterface
     */
    protected $requestBuilder;
    /**
     * @var TransferFactory
     */
    protected $transferFactory;
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $caching;

    public function __construct(
        BuilderInterface $requestBuilder,
        TransferFactory $transferFactory,
        ClientInterface $client,
        string $caching = ''
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->transferFactory = $transferFactory;
        $this->client = $client;
        $this->caching = trim($caching) ?: false;
    }

    protected static function getCacheKey($data, $prefix = '')
    {
        $_data = "";
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_data .= $key . '_';
                if (is_int($value) || is_string($value)) {
                    $_data .= $value . '_';
                }
            }
        }

        return md5($prefix . $_data);
    }

    /**
     * @param array $buildSubject
     * @return Cache
     * @throws \Exception
     */
    public function build(array $buildSubject = [])
    {
        $key = '';
        if ($this->caching) {
            $key = self::getCacheKey($buildSubject, $this->caching);
            if ($data = Cache::staticLoad($key)) {
                return \Zend_Json::decode($data);
            }
        }

        $requestData = $this->transferFactory->create(
            $this->requestBuilder->build($buildSubject)
        );
        $data = [];
        try {
            $response =  $this->client->placeRequest($requestData);
            self::assertResponseOk($response);
            $data = self::readResponseData($response);
        } catch (\Exception $e) {
            $data = [];
            throw $e;
        } finally {
            if ($this->caching) {
                Cache::staticSave(\Zend_Json::encode($data), $key);
            }
        }

        return $data;
    }

    /**
     * @param array $response
     * @throws ClientException
     */
    public static function assertResponseOk(array $response)
    {
        if (!isset($response['code'])||(string)$response['code'] !== (string) '200') {
            throw new ClientException(__($response['message'] ?? 'Something went wrong'));
        }
    }

    public static function readResponseData(array $response)
    {
        return $response['data'] ?? [];
    }
}
