<?php

defined('MBQ_IN_IT') or exit;

/**
 * forum topic write class
 * 
 * @since  2012-8-15
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqBaseWrEtForumTopic extends MbqBaseWr {
    
    public function __construct() {
    }
    
    /**
     * add forum topic view num
     */
    public function addForumTopicViewNum() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * mark forum topic read
     */
    public function markForumTopicRead() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * reset forum topic subscription
     */
    public function resetForumTopicSubscription() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * add forum topic
     */
    public function addMbqEtForumTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * subscribe topic
     */
    public function subscribeTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * unsubscribe topic
     */
    public function unsubscribeTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * m_stick_topic
     */
    public function mStickTopic($threadid, $mode) {
        if($mode==1){
            $stick = vB_Api::instance('node')->setSticky(array($threadid));
            if($stick === null || !empty($stick['errors'])) {
                 MbqError::alert('', "Stick topic failed!", '', MBQ_ERR_APP);
            }
        }else{
            $unstick = vB_Api::instance('node')->unsetSticky(array($threadid));
            if($unstick === null || !empty($unstick['errors'])) {
                MbqError::alert('', "Unstick topic failed!", '', MBQ_ERR_APP);
            }
        }

    }
    
    /**
     * m_close_topic
     */
    public function mCloseTopic($threadid, $mode) {
        if($mode==1){
            $unlock = vB_Api::instance('node')->openNode($threadid);
            if ($unlock === null || !empty($unlock['errors'])) {
                 MbqError::alert('', "Reopen topic failed!", '', MBQ_ERR_APP);
            }
        }else{
            $lock = vB_Api::instance('node')->closeNode($threadid);
            if($lock === null || !empty($lock['errors'])) {
                MbqError::alert('', "Close topic failed!", '', MBQ_ERR_APP);
            }
        }
    }
    
    /**
     * m_delete_topic
     */
    public function mDeleteTopic($threadid, $mode, $reason) {
        ($mode == 2) ? $hard = true : $hard = false;
        $delete = vB_Api::instance('node')->deleteNodes(array($threadid), $hard, $reason);
        if(empty($delete)) {
             MbqError::alert('', "Delete topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_undelete_topic
     */
    public function mUndeleteTopic($threadid) {
        $delete = vB_Api::instance('node')->undeleteNodes(array($threadid));
        if(empty($delete)) {
             MbqError::alert('', "Undelete topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_move_topic
     */
    public function mMoveTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * m_rename_topic
     */
    public function mRenameTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
    
    /**
     * m_approve_topic
     */
    public function mApproveTopic() {
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NEED_ACHIEVE_IN_INHERITED_CLASSE);
    }
  
}

?>