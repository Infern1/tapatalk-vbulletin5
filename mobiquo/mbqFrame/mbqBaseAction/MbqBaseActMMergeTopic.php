<?php

defined('MBQ_IN_IT') or exit;

/**
 * m_merge_topic action
 * 
 * @since  2012-9-26
 * @author Wu ZeTao <578014287@qq.com>
 */
Abstract Class MbqBaseActMMergeTopic extends MbqBaseAct {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * action implement
     */
    protected function actionImplement() {
        if (!MbqMain::$oMbqConfig->moduleIsEnable('forum')) {
            MbqError::alert('', "Not support module forum!", '', MBQ_ERR_NOT_SUPPORT);
        }
        $topicIdA = MbqMain::$input[0];
        $topicIdB = MbqMain::$input[1];
        $redirect = MbqMain::$input[2];
        $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
        $oMbqEtForumTopicA = $oMbqRdEtForumTopic->initOMbqEtForumTopic($topicIdA, array('case' => 'byTopicId'));
        $oMbqEtForumTopicB = $oMbqRdEtForumTopic->initOMbqEtForumTopic($topicIdB, array('case' => 'byTopicId'));
        
        if ( $oMbqEtForumTopicA &&  $oMbqEtForumTopicB) {
            $oMbqAclEtForumTopic = MbqMain::$oClk->newObj('MbqAclEtForumTopic');
            if ($oMbqAclEtForumTopic->canAclMMergeTopic($oMbqEtForumTopicA, $oMbqEtForumTopicB)) {    //acl judge
                $oMbqWrEtForumTopic = MbqMain::$oClk->newObj('MbqWrEtForumTopic');
                $oMbqWrEtForumTopic->mMergeTopic($topicIdA, $topicIdB ,$redirect);
                $this->data['result'] = true;
            } else {
                MbqError::alert('', '', '', MBQ_ERR_APP);
            }
        } else {
            MbqError::alert('', "Need valid topic id!", '', MBQ_ERR_APP);
        }
    }
  
}

?>