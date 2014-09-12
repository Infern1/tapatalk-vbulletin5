<?php

defined('MBQ_IN_IT') or exit;

/**
 * m_mark_as_spam action
 * 
 * @since  2012-9-26
 * @author Wu ZeTao <578014287@qq.com>
 */
Abstract Class MbqBaseActMMarkAsSpam extends MbqBaseAct {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * action implement
     */
    protected function actionImplement() {
        if (!MbqMain::$oMbqConfig->moduleIsEnable('user')) {
            MbqError::alert('', "Not support module user!", '', MBQ_ERR_NOT_SUPPORT);
        }
        $userId = MbqMain::$input[0] = 2;
        $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
        //vB::getUserContext()->isModerator();
        if ($oMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($userId, array('case' => 'byUserId'))) {
            $oMbqAclEtUser = MbqMain::$oClk->newObj('MbqAclEtUser');
            if ($oMbqAclEtUser->canAclMMarkAsSpam($oMbqEtUser)) {   //acl judge
                $oMbqWrEtUser = MbqMain::$oClk->newObj('MbqWrEtUser');
                $oMbqWrEtUser->mMarkAsSpam($oMbqEtUser);
                $this->data['result'] = true;
            } else {
                MbqError::alert('', '', '', MBQ_ERR_APP);
            }
        } else {
            MbqError::alert('', "User not found!", '', MBQ_ERR_APP);
        }
    }
  
}

?>