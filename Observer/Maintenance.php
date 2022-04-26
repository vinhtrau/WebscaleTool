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

class Maintenance implements ObserverInterface{

    protected $_api;
    protected $_helper;
    protected $_logger;

    public function __construct(
        Api $api,
        Data $helper,
        Logger $logger
    ){
        $this->_api    = $api;
        $this->_helper = $helper;
        $this->_logger = $logger;
    }

    public function execute(Observer $observer){
        if(!$this->_helper->isEnableMaintenanceModeWithMagento()){
            return;
        }
        try{
            if($observer->getData('isOn')){
                $this->_api->startMaintenance();
            } else{
                $this->_api->stopMaintenance();
            }
        }catch(\Exception $e){
            $this->_logger->addCritical($e->getMessage());
        }
    }
}
