<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtForumPost');

/**
 * forum post write class
 * 
 * @since  2012-8-21
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqWrEtForumPost extends MbqBaseWrEtForumPost {
    
    public function __construct() {
    }
    
    /**
     * add forum post
     *
     * @param  Mixed  $var($oMbqEtForumPost or $objsMbqEtForumPost)
     */
    public function addMbqEtForumPost(&$var) {
        if (is_array($var)) {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
        } else {
            $data['title'] = '(Untitled)';
            //$data['rawtext'] = $var->postContent->oriValue;
            $data['rawtext'] = MbqMain::$oMbqCm->exttConvertAppAttBbcodeToNativeCode($var->postContent->oriValue);     //attention!!!
            $data['parentid'] = $var->topicId->oriValue;
            $data['created'] = vB::getRequest()->getTimeNow();
            $result = vB_Api::instance('content_text')->add($data);
            if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                $var->postId->setOriValue($result);
                //handle atts start,ref vB5_Frontend_Controller_CreateContent::index()
                $attIds = MbqMain::$oMbqCm->getAttIdsFromContent($data['rawtext']);
                if ($attIds) {
                    foreach ($attIds as $attId) {
                        $attData = array(
                            'filedataid' => $attId,
                            'filename' => 'ImageUploadedByTapatalk'.microtime(true).'.jpg'  //TODO:since app only support jpg file,now only use a jpg file name
                        );
                        try {
                            $resultAtt = vB_Api::instance('node')->addAttachment($var->postId->oriValue, $attData);     //
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
            $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
            $var = $oMbqRdEtForumPost->initOMbqEtForumPost($var->postId->oriValue, array('case' => 'byPostId'));    //for get state
        }
    }
    
    /**
     * modify forum post
     *
     * @param  Mixed  $var($oMbqEtForumPost or $objsMbqEtForumPost)
     */
    public function mdfMbqEtForumPost(&$var) {
        if (is_array($var)) {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
        } else {
            $data['title'] = $var->postTitle->oriValue;
            $data['parentid'] = $var->topicId->oriValue;
            //$data['rawtext'] = $var->postContent->oriValue;
            $data['rawtext'] = MbqMain::$oMbqCm->exttConvertAppAttBbcodeToNativeCode($var->postContent->oriValue);     //attention!!!
            $result = vB_Api::instance('content_text')->update($var->postId->oriValue, $data);
            if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
            } else {
                MbqError::alert('', "Can not save!Content too short or please post later.", '', MBQ_ERR_APP);
            }
            $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
            $var = $oMbqRdEtForumPost->initOMbqEtForumPost($var->postId->oriValue, array('case' => 'byPostId'));    //for get state
        }
    }
    
    
    /**
     * m_delete_post
     */
    public function mDeletePost($nodeids, $mode, $reason='') {
        ($mode == 2) ? $hard = true : $hard = false;
        $delete = vB_Api::instance('node')->deleteNodes($nodeids, $hard, $reason);
        if($delete === null || !$delete) {
            MbqError::alert('', "Delete post failed!", '', MBQ_ERR_APP);
        }
    }
    
    /**
     * m_undelete_post
     */
    public function mUndeletePost($nodeid) {
        $delete = vB_Api::instance('node')->undeleteNodes($nodeid);
        if ($delete === null || !empty($delete['errors'])) {
            MbqError::alert('', "Undelete post failed!", '', MBQ_ERR_APP);
        }
    }
  
    /**
     * m_undelete_post
     */
    public function mMovePost($postids, $destforumid, $title='') {
        
        $cleaner = vB::getCleaner();
        $postids = $cleaner->clean($postids, vB_Cleaner::TYPE_STR);
        $title = $cleaner->clean($title, vB_Cleaner::TYPE_STR);
        $destforumid = $cleaner->clean($destforumid, vB_Cleaner::TYPE_UINT);
        $postids = explode(',', $postids);
        $postids = array_map("trim", $postids);
        if (empty($postids)) {
            MbqError::alert('', "Need valid threads!", '', MBQ_ERR_APP);
        }
        if (empty($destforumid)) {
            MbqError::alert('', "Need valid forum!", '', MBQ_ERR_APP);
        }
        $result = vB_Api::instance('node')->moveNodes($postids, $destforumid, true, $title);
        if ($result === null || isset($result['errors'])) {
            MbqError::alert('', vB_Library::instance('vb4_functions')->getErrorResponse($result), '', MBQ_ERR_APP);
        }

    }
    
   
    
    /**
     * m_approve_post
     *
     * @param  Object  $oMbqEtForumTopic
     * @param  Integer  $mode
     */
    public function mApprovePost($tlist, $mode) {
        
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
    
}

?>