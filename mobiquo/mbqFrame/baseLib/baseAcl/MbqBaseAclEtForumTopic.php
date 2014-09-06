<?php

defined('MBQ_IN_IT') or exit;

/**
 * forum topic acl class
 * 
 * @since  2012-8-10
 * @author Wu ZeTao <578014287@qq.com>
 */
Abstract Class MbqBaseAclEtForumTopic extends MbqBaseAcl {
    
    public function __construct() {
    }
    
    /**
     * judge can get topic from the forum
     *
     * @return  Boolean
     */
    public function canAclGetTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can get thread
     *
     * @return  Boolean
     */
    public function canAclGetThread() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can new topic
     *
     * @return  Boolean
     */
    public function canAclNewTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can get subscribed topic
     *
     * @return  Boolean
     */
    public function canAclGetSubscribedTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can mark all my unread topics as read
     *
     * @return  Boolean
     */
    public function canAclMarkAllAsRead() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can get_unread_topic
     *
     * @return  Boolean
     */
    public function canAclGetUnreadTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can get_participated_topic
     *
     * @return  Boolean
     */
    public function canAclGetParticipatedTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can get_latest_topic
     *
     * @return  Boolean
     */
    public function canAclGetLatestTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can search_topic
     *
     * @return  Boolean
     */
    public function canAclSearchTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can subscribe_topic
     *
     * @return  Boolean
     */
    public function canAclSubscribeTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can unsubscribe_topic
     *
     * @return  Boolean
     */
    public function canAclUnsubscribeTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can get_user_topic
     *
     * @return  Boolean
     */
    public function canAclGetUserTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
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
     * @return  Boolean
     */
    public function canAclMMoveTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can m_rename_topic
     *
     * @return  Boolean
     */
    public function canAclMRenameTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * judge can m_approve_topic
     *
     * @return  Boolean
     */
    public function canAclMApproveTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    /**
     * judge isModeration
     *
     * @return  Boolean
     */
    public function isModeration(){
        $session = vB::getCurrentSession();
        if (!$session->validateCpsession()) {
            MbqError::alert('', 'This action require a moderator authentication', '', MBQ_ERR_APP);
        }
        return true;
    }
  
}

?>