<?php

defined('MBQ_IN_IT') or exit;

/**
 * m_delete_topic action
 * 
 * @since  2012-9-26
 * @author Wu ZeTao <578014287@qq.com>
 */
Abstract Class MbqBaseActMDeleteTopic extends MbqBaseAct {
    
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
        $topicId = MbqMain::$input[0];
        $mode = (int) MbqMain::$input[1];
        if ($mode != 1 && $mode != 2) {
            MbqError::alert('', "Need valid mode!", '', MBQ_ERR_APP);
        }
        if ($mode == 2) {
            MbqError::alert('', "Sorry!Not support hard-delete a topic!", '', MBQ_ERR_APP);
        }
        $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
        if ($oMbqEtForumTopic = $oMbqRdEtForumTopic->initOMbqEtForumTopic($topicId, array('case' => 'byTopicId'))) {
            $oMbqAclEtForumTopic = MbqMain::$oClk->newObj('MbqAclEtForumTopic');
            if ($oMbqAclEtForumTopic->canAclMDeleteTopic($oMbqEtForumTopic, $mode)) {    //acl judge
                $oMbqWrEtForumTopic = MbqMain::$oClk->newObj('MbqWrEtForumTopic');
                $oMbqWrEtForumTopic->mDeleteTopic($topicId, $mode);
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