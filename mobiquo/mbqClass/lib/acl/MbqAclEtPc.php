<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtPc');

/**
 * private conversation acl class
 * 
 * @since  2012-11-4
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqAclEtPc extends MbqBaseAclEtPc {
    
    public function __construct() {
    }
    
    /**
     * judge can get_inbox_stat
     *
     * @return  Boolean
     */
    public function canAclGetInboxStat() {
        return MbqMain::hasLogin();
    }
    
    /**
     * judge can get_conversations
     *
     * @return  Boolean
     */
    public function canAclGetConversations() {
        return MbqMain::hasLogin();
    }
    
    /**
     * judge can get_conversation
     *
     * @param  Object  $oMbqEtPc
     * @return  Boolean
     */
    public function canAclGetConversation($oMbqEtPc) {
        if (MbqMain::hasLogin()) {
            foreach($oMbqEtPc->objsRecipientMbqEtUser as $oRecipientMbqEtUser) {
               if ($oRecipientMbqEtUser->userId->oriValue == MbqMain::$oCurMbqEtUser->userId->oriValue) {
                  return true;
               }
            }
        }
        return false;
    }
    
    /**
     * judge can new_conversation
     *
     * @param  Object  $oMbqEtPc
     * @return  Boolean
     */
    public function canAclNewConversation($oMbqEtPc) {
        if (MbqMain::hasLogin() && strlen(trim($oMbqEtPc->convContent->oriValue)) > 0) {
            $num = 0;
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            foreach ($oMbqEtPc->userNames->oriValue as $userName) {
                if ($oMbqRdEtUser->initOMbqEtUser($userName, array('case' => 'byLoginName'))) {
                    $num ++;
                }
            }
            return $num ? true : false;
        }
        return false;
    }
    
    /**
     * judge can delete_conversation
     *
     * @param  Object  $oMbqEtPc
     * @param  Integer  $mode  
     * @return  Boolean
     */
    public function canAclDeleteConversation($oMbqEtPc, $mode) {
        if (MbqMain::hasLogin() && ($mode == 1 ||  $mode == 2) ) {
            foreach ($oMbqEtPc->objsRecipientMbqEtUser as $oRecipientMbqEtUser) {
                if ($oRecipientMbqEtUser->userId->oriValue == MbqMain::$oCurMbqEtUser->userId->oriValue) {
                    return true;
                }
            }
        }
        return false;
    }
  
}

?>