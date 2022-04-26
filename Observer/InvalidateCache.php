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
    /**
     * @var \Forix\WebscaleTools\Model\FlagFactory
     */
    protected $flagFactory;
    protected $_logger;

    public function __construct(
        Api $api,
        Data $helper,
        \Forix\WebscaleTools\Model\FlagFactory $flag_factory,
        Logger $logger
    ){
        $this->_api    = $api;
        $this->_helper = $helper;
        $this->flagFactory = $flag_factory;
        $this->_logger = $logger;
    }

    public function execute(Observer $observer){
        if(!$this->_helper->isEnable()){
            return;
        }
        if($this->checkFlag() > $this->_helper->getIntervalFlushCacheTime()){
            $this->_api->flushCache();
            $this->setFlag();
        }else{
            $this->_logger->addWarning(__("Webscale cache is not invalidated because there is another process is running. Please try again after few seconds."));
        }
    }

    public function checkFlag(){
        /** @var \Forix\WebscaleTools\Model\Flag $flag */
        $flag = $this->flagFactory->create();
        $flag->loadSelf();
        return time() - (int)$flag->getFlagData();
    }
    public function setFlag(){
        /** @var \Forix\WebscaleTools\Model\Flag $flag */
        $flag = $this->flagFactory->create();
        $flag->loadSelf();
        $flag->setFlagData(time());
        $flag->save();
    }
}
