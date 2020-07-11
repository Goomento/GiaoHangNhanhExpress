<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_GiaoHangNhanhExpress
 * @link https://github.com/Goomento/GiaoHangNhanhExpress
 */

namespace Goomento\GiaoHangNhanhExpress\Console;

use Goomento\GiaoHangNhanhExpress\Helper\Logger;

/**
 * Class GhnSyncOrderCron
 * @package Goomento\GiaoHangNhanhExpress\Console
 */
class GhnSyncOrderCron
{
    /**
     * @var \Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository
     */
    protected $ghnShipmentRepository;

    public function __construct(\Goomento\GiaoHangNhanhExpress\Api\GhnShipmentRepository $ghnShipmentRepository)
    {
        $this->ghnShipmentRepository = $ghnShipmentRepository;
    }

    /**
     * Run cron job to update order
     */
    public function execute()
    {
        try {
            $this->ghnShipmentRepository->syncList();
        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
        }
    }
}
