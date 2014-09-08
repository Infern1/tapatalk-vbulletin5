<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtForumTopic');

/**
 * forum topic acl class
 * 
 * @since  2012-8-10
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqAclEtForumTopic extends MbqBaseAclEtForumTopic {
    
    public function __construct() {
    }
    
    /**
     * judge can get topic from the forum
     *
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclGetTopic($oMbqEtForum) {
        return true;
    }
    
    /**
     * judge can get thread
     *
     * @param  Object  $oMbqEtForumTopic
     * @return  Boolean
     */
    public function canAclGetThread($oMbqEtForumTopic) {
        return true;
    }
    
    /**
     * judge can get_user_topic
     *
     * @return  Boolean
     */
    public function canAclGetUserTopic() {
        if (MbqMain::$oMbqConfig->getCfg('user.guest_okay')->oriValue == MbqBaseFdt::getFdt('MbqFdtConfig.user.guest_okay.range.support')) {
            return true;
        } else {
            return MbqMain::hasLogin();
        }
    }
    
    /**
     * judge can new topic
     *
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclNewTopic($oMbqEtForum) {
        //if (MbqMain::hasLogin() && $oMbqEtForum->mbqBind['channelFullContent']['content']['createpermissions']['vbforum_text'] && $oMbqEtForum->mbqBind['channelFullContent']['content']['options']['cancontainthreads']) {
        if (MbqMain::hasLogin() && $oMbqEtForum->mbqBind['channelFullContent']['content']['createpermissions']['vbforum_text'] && !$oMbqEtForum->mbqBind['channelRecord']['category']) {
            return true;
        } 
        return false;
    }
    
    /**
     * judge can get_unread_topic
     *
     * @return  Boolean
     */
    public function canAclGetUnreadTopic() {
        return MbqMain::hasLogin();
    }
    
    /**
     * judge can get_participated_topic
     *
     * @return  Boolean
     */
    public function canAclGetParticipatedTopic() {
        return MbqMain::hasLogin();
    }
    
    /**
     * judge can get_latest_topic
     *
     * @return  Boolean
     */
    public function canAclGetLatestTopic() {
        if (MbqMain::$oMbqConfig->getCfg('forum.guest_search')->oriValue == MbqBaseFdt::getFdt('MbqFdtConfig.forum.guest_search.range.support')) {
            return true;
        } else {
            return MbqMain::hasLogin();
        }
    }
    
    /**
     * judge can search_topic
     *
     * @return  Boolean
     */
    public function canAclSearchTopic() {
        if (MbqMain::$oMbqConfig->getCfg('forum.guest_search')->oriValue == MbqBaseFdt::getFdt('MbqFdtConfig.forum.guest_search.range.support')) {
            return true;
        } else {
            return MbqMain::hasLogin();
        }
    }
    
    /**
     * judge can get subscribed topic
     *
     * @return  Boolean
     */
    public function canAclGetSubscribedTopic() {
        return MbqMain::hasLogin();
    }
  
    
       /**
     * judge can m_stick_topic
     *
     * @return  Boolean
     */
    public function canAclMStickTopic($oMbqEtForumTopic, $mode) {
        if ($mode == 1) {   //stick
            if ($oMbqEtForumTopic->canStick->oriValue && !$oMbqEtForumTopic->isSticky->oriValue) {
                return true;
            }
        } elseif ($mode == 2) { //unstick
            if ($oMbqEtForumTopic->canStick->oriValue && $oMbqEtForumTopic->isSticky->oriValue) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * judge can m_close_topic
     *
     * @return  Boolean
     */
    public function canAclMCloseTopic($oMbqEtForumTopic, $mode) {
        if ($mode == 1) {   //reopen
            if ($oMbqEtForumTopic->canClose->oriValue && $oMbqEtForumTopic->isClosed->oriValue) {
                return true;
            }
        } elseif ($mode == 2) { //close
            if ($oMbqEtForumTopic->canClose->oriValue && !$oMbqEtForumTopic->isClosed->oriValue) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * judge can m_delete_topic
     *
     * @return  Boolean
     */
    public function canAclMDeleteTopic($oMbqEtForumTopic, $mode) {
       if ($mode == 1) {   //soft-delete
            if (!$oMbqEtForumTopic->isDeleted->oriValue && $oMbqEtForumTopic->canDelete->oriValue) {
                return true;
            }
        } elseif ($mode == 2) { //hard-delete
            //not support
        }
        return false;
    }
    
    /**
     * judge can m_undelete_topic
     *
     * @return  Boolean
     */
    public function canAclMUndeleteTopic($oMbqEtForumTopic) {
        if ($oMbqEtForumTopic->isDeleted->oriValue && $oMbqEtForumTopic->canDelete->oriValue) {
            return true;
        }
        return false;
    }
    
     /**
     * judge can m_move_topic
     *
     * @param  Object  $oMbqEtForumTopic
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclMMoveTopic($oMbqEtForumTopic, $oMbqEtForum) {
        if ($oMbqEtForumTopic->canMove->oriValue) {
            return true;
        }
        return false;
    }
    
    /**
     * judge can m_rename_topic
     *
     * @param  Object  $oMbqEtForumTopic
     * @return  Boolean
     */
    public function canAclMRenameTopic($oMbqEtForumTopic) {
        return $oMbqEtForumTopic->canRename->oriValue;
    }
    
    /**
     * judge can m_approve_topic
     *
     * @param  Object  $oMbqEtForumTopic
     * @param  Integer  $mode
     * @return  Boolean
     */
    public function canAclMApproveTopic($oMbqEtForumTopic, $mode) {
        if ($mode == 1) {   //approve
            if ($oMbqEtForumTopic->canApprove->oriValue && !$oMbqEtForumTopic->isApproved->oriValue) {
                return true;
            }
        } elseif ($mode == 2) { //unapprove
            if ($oMbqEtForumTopic->canApprove->oriValue && $oMbqEtForumTopic->isApproved->oriValue) {
                return true;
            }
        }
        return false;
    }

    
    
}

?>