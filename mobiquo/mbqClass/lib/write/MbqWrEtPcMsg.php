<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtPcMsg');

/**
 * private conversation message write class
 * 
 * @since  2012-11-4
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqWrEtPcMsg extends MbqBaseWrEtPcMsg {
    
    public function __construct() {
    }
    
    /**
     * add private conversation message
     *
     * @param  Object  $oMbqEtPcMsg
     * @param  Object  $oMbqEtPc
     */
    public function addMbqEtPcMsg(&$oMbqEtPcMsg, $oMbqEtPc) {
        try {
            $cleaner = vB::getCleaner();
            $result = vB_Api::instanceInternal('content_privatemessage')->add(
                array(
            		'respondto' => $oMbqEtPc->convId->oriValue,
            		'rawtext' => htmlspecialchars($cleaner->clean($oMbqEtPcMsg->msgContent->oriValue) ,ENT_NOQUOTES ),
            		'msgtype' => 'message'
                )
            );
            if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                $oMbqEtPcMsg->msgId->setOriValue($result);
            } else {
                MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
            }
        } catch (Exception $e) {
            MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
        }
    }
  
}

?>