<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseActPrefetchAccount');

/**
 * prefetch account
 * 
 * @since  2013-10-16
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqActPrefetchAccount extends MbqBaseActPrefetchAccount {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * action implement
     */
    public function actionImplement() {
        parent::actionImplement();
    }
  
}

?>