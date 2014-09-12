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
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
        } else {
            if (is_array($var)) {
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
            } else {
                $result = vB_Api::instance('node')->markRead($var->topicId->oriValue);
            }
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
                    $attIds = MbqMain::$oMbqCm->getAttIdsFromContent($data['rawtext']);
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
                    MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
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
     * m_undelete_topic
     */
    public function mMoveTopic($threadid, $destforumid) {
        $moved = vB_Api::instance('node')->moveNodes(array($threadid), $destforumid, true);
        if($moved === null || isset($moved['errors'])) {
             MbqError::alert('', "Move topic failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_undelete_topic
     */
    public function mRenameTopic($threadid, $title) {
        MbqError::alert('', "Not support rename topic!", '', MBQ_ERR_APP);
    }
    
    /**
     * m_approve_topic
     *
     * @param  Object  $oMbqEtForumTopic
     * @param  Integer  $mode
     */
    public function mApproveTopic($tlist, $mode) {
        
        if ($mode == 1) {
            $result = vB_Api::instance('node')->setApproved(array($tlist), true);
        } elseif ($mode == 2) {
            $result = vB_Api::instance('node')->setApproved(array($tlist), false);
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