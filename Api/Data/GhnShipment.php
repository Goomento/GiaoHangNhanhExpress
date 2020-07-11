<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Api\Data;

/**
 * Interface GhnShipment
 * @package Goomento\GiaoHangNhanhExpress\Api\Data
 */
interface GhnShipment
{
    const STATUS_PENDING = 'pending';
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param null $data
     * @return array
     */
    public function getData($data = null);

    /**
     * @param $data
     * @return mixed
     */
    public function setData($data);

    /**
     * @return mixed
     */
    public function sync();

    /**
     * @return mixed
     */
    public function getResponseData();

    /**
     * @return mixed
     */
    public function getSendData();

    /**
     * @return string
     */
    public function getTracking();

    /**
     * @return mixed
     */
    public function getLastSync();

    /**
     * @return string
     */
    public function getLog();
}
