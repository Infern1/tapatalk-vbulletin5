<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtPc');

/**
 * private conversation write class
 * 
 * @since  2012-11-4
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqWrEtPc extends MbqBaseWrEtPc {
    
    public function __construct() {
    }
    
    /**
     * add private conversation
     *
     * @param  Object  $oMbqEtPc
     */
    public function addMbqEtPc(&$oMbqEtPc) {
        try {
            $result = vB_Api::instanceInternal('content_privatemessage')->add(
                array(
                    'msgRecipients' => implode(',', $oMbqEtPc->userNames->oriValue),
                    'title' => $oMbqEtPc->convTitle->oriValue,
                    'rawtext' => $oMbqEtPc->convContent->oriValue
                )
            );
            if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                $oMbqEtPc->convId->setOriValue($result);
            } else {
                MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
            }
        } catch (Exception $e) {
            MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * delete conversation
     *
     * @param  Object  $oMbqEtPc
     * @param  Integer  $mode
     */
    public function deleteConversation($oMbqEtPc, $mode) {
        if ($mode ==1 || $mode == 2) {
            try {
                $result = vB_Api::instance('content_privatemessage')->toTrashcan($oMbqEtPc->convId->oriValue);
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    if (!$result) {
                        MbqError::alert('', "Can not delete conversation!", '', MBQ_ERR_APP);
                    }
                } else {
                    MbqError::alert('', "Can not delete conversation!", '', MBQ_ERR_APP);
                }
            } catch (Exception $e) {
                MbqError::alert('', "Can not delete conversation!", '', MBQ_ERR_APP);
            }
        } else {
            MbqError::alert('', "Need valid mode id!", '', MBQ_ERR_APP);
        }
    }
  
}

?>