<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtUser');

/**
 * user acl class
 * 
 * @since  2012-9-13
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqAclEtUser extends MbqBaseAclEtUser {
    
    public function __construct() {
    }
    
    /**
     * judge can get online users
     *
     * @return  Boolean
     */
    public function canAclGetOnlineUsers() {
        if (MbqMain::hasLogin()) {
            return true;
        } else {
            if (MbqMain::$oMbqConfig->getCfg('user.guest_whosonline')->oriValue == MbqBaseFdt::getFdt('MbqFdtConfig.user.guest_whosonline.range.support')) {
                return true;
            }
        }
        return false;
    }
  
    /**
     * judge can m_ban_user
     *
     * @return  Boolean
     */
    public function canAclMBanUser($oMbqEtUser, $mode) {
        if (MbqMain::hasLogin() && $oMbqEtUser->userId->oriValue) return true;
        return false;
    }
    
    /**
     * judge can m_mark_as_spam
     *
     * @return  Boolean
     */
    public function canAclMMarkAsSpam($oMbqEtUser) {
        if (MbqMain::hasLogin() && $oMbqEtUser->userId->oriValue) return true;
        return false;
    }
    
}

?>