<?php

defined('MBQ_IN_IT') or exit;

/**
 * mark_pm_unread action
 * 
 * @since  2012-12-29
 * @author Wu ZeTao <578014287@qq.com>
 */
Abstract Class MbqBaseActMarkPmRead extends MbqBaseAct {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * action implement
     */
    protected function actionImplement() {
        if (MbqMain::$oMbqConfig->moduleIsEnable('pm')) {
            if(MbqMain::$oMbqAppEnv->pm){
                $msgId = MbqMain::$input[0];
                $oCurJUser = MbqMain::$oMbqAppEnv->oCurJUser;
                if($oCurJUser->id){
                    $oMbqWrEtPm = MbqMain::$oClk->newObj('MbqWrEtPm'); //write class
                    if($msg = $oMbqWrEtPm->markMbqEtPmRead($oCurJUser->id, $msgId)){
                        $this->data['result'] = true;
                        $this->data['result_text'] = (string) $msg->message;
                    }else MbqError::alert('', "Mark message read failed!", '', MBQ_ERR_APP);
                }else{
                    MbqError::alert('', "User not found!", '', MBQ_ERR_APP);
                }
            }else{
                MbqError::alert('', "You not install component uddeim!", '', MBQ_ERR_APP);
            }
        } else {
            MbqError::alert('', "Not support module private message!", '', MBQ_ERR_NOT_SUPPORT);
        }
    }
  
}

?>