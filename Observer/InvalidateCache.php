<?php
/**
 * Created by: Bruce
 * Date: 20/04/2022
 * Time: 14:05
 */

namespace Forix\WebscaleTools\Observer;

use Forix\WebscaleTools\Helper\Data;
use Forix\WebscaleTools\Logger\Logger;
use Forix\WebscaleTools\Model\Api;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class InvalidateCache implements ObserverInterface{

    protected $_api;
    protected $_helper;
    protected $_logger;

    public function __construct(
        Api $api,
        Data $helper,
        \Forix\WebscaleTools\Model\FlagFactory $flag_factory,
        Logger $logger
    ){
        $this->_api    = $api;
        $this->_helper = $helper;
        $this->_logger = $logger;
    }

    public function execute(Observer $observer){
        try{
            $this->_api->flushCache();
        }catch(\Exception $e){
            $this->_logger->addError($e->getMessage());
        }
    }
}
