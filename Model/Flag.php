<?php
namespace Forix\WebscaleTools\Model;

/**
 * Flag indicates that some rules have changed but changes have not been applied yet.
 */
class Flag extends \Magento\Framework\Flag
{
    /**
     * Flag code
     *
     * @var string
     */
    protected $_flagCode = 'webscale_flush_cache';
}
