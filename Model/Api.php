<?php
/**
 * Created by: Bruce
 * Date: 20/04/2022
 * Time: 14:14
 */

namespace Forix\WebscaleTools\Model;

use Forix\WebscaleTools\Helper\Data;
use Forix\WebscaleTools\Logger\Logger;
use Magento\Framework\HTTP\Client\Curl;

class Api{

    CONST TASK_EXIT_MAINTMODE = "exit_maintenance";
    CONST TASK_ENTER_MAINTMODE = "enter_maintenance";
    CONST TASK_INVALIDATE_CACHE = "invalidate-cache";
    const URL = 'https://api.webscale.com/v2/tasks';
    protected $_action;
    /**
     * @var Curl $_curl
     */
    protected $_curl;
    protected $_helper;
    protected $_storeManager;
    protected $_logger;

    public function __construct(
        Curl $curl, Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Logger $logger
    ){
        $this->_curl = $curl;
        $this->_curl->addHeader('Content-Type', 'application/json');
        $this->_curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        $this->_logger = $logger;
    }

    public function addAuthHeader(){
        if($key = $this->_helper->getAccessKey()){
            $this->_curl->addHeader("Authorization", "Bearer " . $key);
        } else{
            throw new \Exception("No Access key found");
        }
        return $this;
    }

    public function logResp(){
        $this->_logger->addInfo($this->_curl->getBody());

    }
    public function startMaintenance(){
        return $this->executeMaintenance(true);
    }

    public function stopMaintenance(){
        return $this->executeMaintenance(false);
    }

    public function executeMaintenance($mode){
        $this->addAuthHeader();
        if($appId = $this->_helper->getAppId()){
            if($mode == true){
                $this->_action = self::TASK_ENTER_MAINTMODE;
            } else{
                $this->_action = self::TASK_EXIT_MAINTMODE;
            }

            $data = [
                'type'   => $this->_action,
                'target' => '/v2/applications/' . $appId
            ];
            $this->_curl->post(self::URL, json_encode($data));
            $this->logResp();
            return true;
        }
        throw new \Exception("No App Id found");
    }

    public function flushCache(){
        $this->addAuthHeader();
//        $url = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB) ."*";
        $url = "https://staging.smokehouse.com/*";
        if($appId = $this->_helper->getAppId()){
            $data = [
                "type"       => self::TASK_INVALIDATE_CACHE,
                'target' => '/v2/applications/' . $appId,
                "parameters" => [
                    "urls" => [$url]
                ]
            ];
            $this->_curl->post(self::URL, json_encode($data));
            $this->logResp();
            return true;
        }
        throw new \Exception("No App Id found");
    }
}
