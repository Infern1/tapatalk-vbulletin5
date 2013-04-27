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
                                'filename' => 'ImageUploadedByTapatalk'.microtime(true).'.tmp'  //TODO:now only use a temp file name
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
  
}

?>