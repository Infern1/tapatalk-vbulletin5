<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtForumTopic');

/**
 * forum topic write class
 * 
 * @since  2012-8-15
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqWrEtForumTopic extends MbqBaseWrEtForumTopic {
    
    public function __construct() {
    }
    
    /**
     * add forum topic view num
     *
     * @param  Mixed  $var($oMbqEtForumTopic or $objsMbqEtForumTopic)
     */
    public function addForumTopicViewNum(&$var) {
        if (is_array($var)) {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
        } else {
            //do nothing
        }
    }
    
    /**
     * mark forum topic read
     *
     * @param  Mixed  $var($oMbqEtForumTopic or $objsMbqEtForumTopic)
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'markAllAsRead' means mark all my unread topics as read
     */
    public function markForumTopicRead(&$var = NULL, $mbqOpt = array()) {
        if ($mbqOpt['case'] == 'markAllAsRead') {
            $mark = vB_Api::instance('node')->markChannelsRead();
        } else {
            if (is_array($var)) {
                $mark = vB_Api::instance('node')->markReadMultiple($var);
            } else {
                $mark = vB_Api::instance('node')->markRead($var->topicId->oriValue);
            }
        }
        if($mark === null || !empty($mark['errors'])) {
            MbqError::alert('', "Mark topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * reset forum topic subscription
     *
     * @param  Mixed  $var($oMbqEtForumTopic or $objsMbqEtForumTopic)
     */
    public function resetForumTopicSubscription(&$var) {
        if (is_array($var)) {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
        } else {
            //do nothing
        }
    }
    
    function getErrorFromResult($result){
        $error = vB_Library::instance('vb4_functions')->getErrorResponse($result);
        if(!empty($error)){
            $message = $error['response']['errormessage'];
            if($message=='maxchars_exceeded_x_title_y'){
                $vboptions = vB::getDatastore()->getValue('options');
                $titlemaxchars = $vboptions['titlemaxchars'];
                MbqError::alert('', "Maximum number of characters exceeded in the title. It cannot be more than $titlemaxchars characters.", '', MBQ_ERR_APP);
            }else{
                MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
            }
        }
        MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
    }
    
    /**
     * add forum topic
     *
     * @param  Mixed  $var($oMbqEtForumTopic or $objsMbqEtForumTopic)
     */
    public function addMbqEtForumTopic(&$var) {
        if (is_array($var)) {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
        } else {
            $data['title'] = $var->topicTitle->oriValue;
            //$data['rawtext'] = $var->topicContent->oriValue;
            $data['rawtext'] = MbqMain::$oMbqCm->exttConvertAppAttBbcodeToNativeCode($var->topicContent->oriValue);     //attention!!!
            $data['parentid'] = $var->forumId->oriValue;
            $data['created'] = vB::getRequest()->getTimeNow();
            try {
                $result = vB_Api::instance('content_text')->add($data);
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    $var->topicId->setOriValue($result);
                    //handle atts start,ref vB5_Frontend_Controller_CreateContent::index()
                    $attIds = $var->attachmentIdArray->oriValue;
                    if ($attIds) {
                        foreach ($attIds as $attId) {
                            $attData = array(
                                'filedataid' => $attId,
                                'filename' => 'ImageUploadedByTapatalk'.microtime(true).'.jpg'  //TODO:since app only support jpg file,now only use a jpg file name
                            );
                            try {
                                $resultAtt = vB_Api::instance('node')->addAttachment($var->topicId->oriValue, $attData);    //
                                if (MbqMain::$oMbqAppEnv->exttHasErrors($resultAtt)) {
                                    MbqError::alert('', "Can not save attachment info!", '', MBQ_ERR_APP);
                                }
                            } catch (Exception $e) {
                            	MbqError::alert('', "Can not save attachment info!", '', MBQ_ERR_APP);
                            }
                        }
                    }
                    //handle atts end
                } else {
                    $this->getErrorFromResult($result);
                }
                $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
                $var = $oMbqRdEtForumTopic->initOMbqEtForumTopic($var->topicId->oriValue, array('case' => 'byTopicId'));    //for get state
            } catch (Exception $e) {
            	MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
            }
        }
    }
    
    /**
     * m_stick_topic
     */
    public function mStickTopic($oMbqEtForumTopic, $mode) {
        if($mode==1){
            $stick = vB_Api::instance('node')->setSticky(array($oMbqEtForumTopic->topicId->oriValue));
            if($stick === null || !empty($stick['errors'])) {
                 MbqError::alert('', "Stick topic failed!", '', MBQ_ERR_APP);
            }
        }else{
            $unstick = vB_Api::instance('node')->unsetSticky(array($oMbqEtForumTopic->topicId->oriValue));
            if($unstick === null || !empty($unstick['errors'])) {
                MbqError::alert('', "Unstick topic failed!", '', MBQ_ERR_APP);
            }
        }

    }
    
    /**
     * m_close_topic
     */
    public function mCloseTopic($oMbqEtForumTopic, $mode) {
        if($mode==1){
            $unlock = vB_Api::instance('node')->openNode($oMbqEtForumTopic->topicId->oriValue);
            if ($unlock === null || !empty($unlock['errors'])) {
                 MbqError::alert('', "Reopen topic failed!", '', MBQ_ERR_APP);
            }
        }else{
            $lock = vB_Api::instance('node')->closeNode($oMbqEtForumTopic->topicId->oriValue);
            if($lock === null || !empty($lock['errors'])) {
                MbqError::alert('', "Close topic failed!", '', MBQ_ERR_APP);
            }
        }
    }
    
    /**
     * m_delete_topic
     */
    public function mDeleteTopic($oMbqEtForumTopic, $mode, $reason) {
        ($mode == 2) ? $hard = true : $hard = false;
        $delete = vB_Api::instance('node')->deleteNodes(array($oMbqEtForumTopic->topicId->oriValue), $hard, $reason);
        if(empty($delete)) {
             MbqError::alert('', "Delete topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_undelete_topic
     */
    public function mUndeleteTopic($oMbqEtForumTopic) {
        $delete = vB_Api::instance('node')->undeleteNodes(array($oMbqEtForumTopic->topicId->oriValue));
        if(empty($delete)) {
             MbqError::alert('', "Undelete topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_undelete_topic
     */
    public function mMoveTopic($oMbqEtForumTopic, $oMbqEtForum, $redirect) {
        if(!$redirect) $redirect = array('redirect' => 'perm');
        else $redirect = array('redirect' => $redirect);
        $cleaner = vB::getCleaner();
        $threadids = $cleaner->clean($oMbqEtForumTopic->topicId->oriValue, vB_Cleaner::TYPE_STR);
        $destforumid = $cleaner->clean($oMbqEtForum->forumId->oriValue, vB_Cleaner::TYPE_UINT);
        $threadids = explode(',', $threadids);
        $threadids = array_map("trim", $threadids);
        if (empty($threadids)) {
            MbqError::alert('', "Need valid topic id!", '', MBQ_ERR_APP);
        }
        if (empty($destforumid)) {
            MbqError::alert('', "Need valid forum id!", '', MBQ_ERR_APP);
        }
        $moved = vB_Api::instance('node')->moveNodes($threadids, $destforumid, true, false, true, $redirect);
        if($moved === null || isset($moved['errors'])) {
             MbqError::alert('', "Move topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_undelete_topic
     */
    public function mRenameTopic($oMbqEtForumTopic, $title) {
        $topic = array(
            'title' => $title
        );
        $result = vB_Api::instance('content_text')->update($oMbqEtForumTopic->topicId->oriValue, $topic);
        if(empty($result) || isset($moved['errors']) ){
             MbqError::alert('', "Rename topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_approve_topic
     *
     * @param  Object  $oMbqEtForumTopic
     * @param  Integer  $mode
     */
    public function mApproveTopic($oMbqEtForumTopic, $mode) {
        
        if ($mode == 1) {
            $result = vB_Api::instance('node')->setApproved(array($oMbqEtForumTopic->topicId->oriValue), true);
        } elseif ($mode == 2) {
            $result = vB_Api::instance('node')->setApproved(array($oMbqEtForumTopic->topicId->oriValue), false);
        } else {
            MbqError::alert('', "Need valid mode!", '', MBQ_ERR_APP);
        }
        if ($result === null || isset($result['errors'])) {
            MbqError::alert('', vB_Library::instance('vb4_functions')->getErrorResponse($result), '', MBQ_ERR_APP);
        }
    }
  
    /**
     * m_merge_topic
     */
    public function mMergeTopic($topicIdA, $topicIdB ,$redirect) {
        $result = vB_Api::instance('node')->mergeTopics(array($topicIdA, $topicIdB), $topicIdA , array($redirect));
        if ($result === null || isset($result['errors'])) {
            MbqError::alert('', "Can not merge topic!", '', MBQ_ERR_APP);
        }
    }
}

?>