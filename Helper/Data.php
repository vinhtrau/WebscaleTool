<?php
/**
 * Created by: Bruce
 * Date: 20/04/2022
 * Time: 14:16
 */

namespace Forix\WebscaleTools\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper{

    public function __construct(
        Context $context
    ){
        parent::__construct($context);
    }

    public function isEnable(){
        return $this->scopeConfig->getValue('webscale_tools/general/enabled');
    }

    public function getAccessKey(){
        return $this->scopeConfig->getValue('webscale_tools/general/access_key');
    }
    public function getAppId(){
        return $this->scopeConfig->getValue('webscale_tools/general/app_id');
    }
    public function isEnableMaintenanceModeWithMagento(){
        return $this->isEnable() && $this->scopeConfig->getValue('webscale_tools/maintenance/enabled');
    }

    public function getIntervalFlushCacheTime(){
        return $this->scopeConfig->getValue('webscale_tools/caching/invalidate_after');
    }
}
