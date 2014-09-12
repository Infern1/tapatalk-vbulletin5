<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseAclEtForumPost');

/**
 * forum post acl class
 * 
 * @since  2012-8-20
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqAclEtForumPost extends MbqBaseAclEtForumPost {
    
    public function __construct() {
    }
    
    /**
     * judge can get_user_reply_post
     *
     * @return  Boolean
     */
    public function canAclGetUserReplyPost() {
        if (MbqMain::$oMbqConfig->getCfg('user.guest_okay')->oriValue == MbqBaseFdt::getFdt('MbqFdtConfig.user.guest_okay.range.support')) {
            return true;
        } else {
            return MbqMain::hasLogin();
        }
    }
    
    /**
     * judge can reply post
     *
     * @param  Object  $oMbqEtForumPost
     * @return  Boolean
     */
    public function canAclReplyPost($oMbqEtForumPost) {
        
        if ( MbqMain::hasLogin() && ((MbqMain::$oMbqAppEnv->exttOptions['templateversion'] < '5.1.0' && $oMbqEtForumPost->mbqBind['topicRecord']['content']['can_comment']) || (MbqMain::$oMbqAppEnv->exttOptions['templateversion'] >= '5.1.0' && $oMbqEtForumPost->mbqBind['topicRecord']['content']['canreply']))
        &&
        vB_Api::instanceInternal('user')->hasPermissions('createpermissions', 'vbforum_text', $oMbqEtForumPost->topicId->oriValue)
        ) {
        //if (MbqMain::hasLogin() && $oMbqEtForumPost->mbqBind['topicRecord']['content']['can_comment'] && vB_Api::instanceInternal('user')->hasPermissions('createpermissions', 'vbforum_text', $oMbqEtForumPost->topicId->oriValue)) {
            if (
            ($oMbqEtForumPost->mbqBind['topicRecord']['content']['showopen'] || (!$oMbqEtForumPost->mbqBind['topicRecord']['content']['showopen'] && $oMbqEtForumPost->mbqBind['topicRecord']['content']['canmoderate'])) 
            && 
            ($oMbqEtForumPost->mbqBind['topicRecord']['content']['showapproved'] || (!$oMbqEtForumPost->mbqBind['topicRecord']['content']['showapproved'] && $oMbqEtForumPost->mbqBind['topicRecord']['content']['canmoderate'])) 
            && 
            ($oMbqEtForumPost->mbqBind['topicRecord']['content']['showpublished'] || (!$oMbqEtForumPost->mbqBind['topicRecord']['content']['showpublished'] && $oMbqEtForumPost->mbqBind['topicRecord']['content']['canmoderate']))
            )  {
                return true;
            }
        }
        return false;
    }
    
    /**
     * judge can get quote post
     *
     * @param  Object  $oMbqEtForumPost
     * @return  Boolean
     */
    public function canAclGetQuotePost($oMbqEtForumPost) {
        return $this->canAclReplyPost($oMbqEtForumPost->oMbqEtForumTopic);
    }
    
    /**
     * judge can get_raw_post
     *
     * @param  Object  $oMbqEtForumPost
     * @return  Boolean
     */
    public function canAclGetRawPost($oMbqEtForumPost) {
        return $this->canAclSaveRawPost($oMbqEtForumPost);
    }
    
    /**
     * judge can save_raw_post
     *
     * @param  Object  $oMbqEtForumPost
     * @return  Boolean
     */
    public function canAclSaveRawPost($oMbqEtForumPost) {
        /* modified from vB_Api_Content::validate() begin */
        $data['title'] = $oMbqEtForumPost->postTitle->oriValue;
        $data['parentid'] = $oMbqEtForumPost->topicId->oriValue;
        $data['rawtext'] = $oMbqEtForumPost->postContent->oriValue;
        $userContext = vB::getUserContext();
        $limits = $userContext->getChannelLimits($oMbqEtForumPost->postId->oriValue);
        if ((!$limits OR empty($limits['edit_time'])) || ($oMbqEtForumPost->mbqBind['postRecord']['publishdate'] + ($limits['edit_time'] * 3600) >= vB::getRequest()->getTimeNow())) {
        } else {
            return false;
        }
        /* modified from vB_Api_Content::validate() end */
        if (MbqMain::hasLogin() && ($oMbqEtForumPost->mbqBind['postRecord']['content']['canedit'] || $oMbqEtForumPost->mbqBind['postRecord']['content']['canremove'])) {
            if (
                (
                ($oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['showopen'] || (!$oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['showopen'] && $oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['canmoderate'])) 
                && 
                ($oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['showapproved'] || (!$oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['showapproved'] && $oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['canmoderate'])) 
                && 
                ($oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['showpublished'] || (!$oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['showpublished'] && $oMbqEtForumPost->oMbqEtForumTopic->mbqBind['topicRecord']['content']['canmoderate']))
                ) 
            && 
                (
                ($oMbqEtForumPost->mbqBind['postRecord']['content']['showopen'] || (!$oMbqEtForumPost->mbqBind['postRecord']['content']['showopen'] && $oMbqEtForumPost->mbqBind['postRecord']['content']['canmoderate'] && $oMbqEtForumPost->mbqBind['postRecord']['content']['moderatorperms']['caneditposts'])) 
                && 
                ($oMbqEtForumPost->mbqBind['postRecord']['content']['showapproved'] || (!$oMbqEtForumPost->mbqBind['postRecord']['content']['showapproved'] && $oMbqEtForumPost->mbqBind['postRecord']['content']['canmoderate'] && $oMbqEtForumPost->mbqBind['postRecord']['content']['moderatorperms']['caneditposts'])) 
                && 
                ($oMbqEtForumPost->mbqBind['postRecord']['content']['showpublished'] || (!$oMbqEtForumPost->mbqBind['postRecord']['content']['showpublished'] && $oMbqEtForumPost->mbqBind['postRecord']['content']['canmoderate'] && $oMbqEtForumPost->mbqBind['postRecord']['content']['moderatorperms']['caneditposts']))
                
                )
            )  {
                return true;
            }
        }
        return false;
    }
    
    /**
     * judge can search_post
     *
     * @return  Boolean
     */
    public function canAclSearchPost() {
        if (MbqMain::$oMbqConfig->getCfg('forum.guest_search')->oriValue == MbqBaseFdt::getFdt('MbqFdtConfig.forum.guest_search.range.support')) {
            return true;
        } else {
            return MbqMain::hasLogin();
        }
    }
    
     /**
     * judge can m_delete_post
     *
     * @return  Boolean
     */
    public function canAclMDeletePost($oMbqEtForumPost, $mode) {
        if ($mode == 1) {   //soft-delete
            if (!$oMbqEtForumPost->isDeleted->oriValue && $oMbqEtForumPost->canDelete->oriValue) {
                return true;
            }
        } elseif ($mode == 2) { //hard-delete
            //not support
        }
        return false;
    }
    
    /**
     * judge can m_undelete_post
     *
     * @return  Boolean
     */
    public function canAclMUndeletePost($oMbqEtForumPost) {
        if ($oMbqEtForumPost->isDeleted->oriValue && $oMbqEtForumPost->canDelete->oriValue) {
            return true;
        }
        return false;
    }
    
    
    /**
     * judge can m_move_post
     *
     * @param  Object  $oMbqEtForumPost
     * @param  Object  $oMbqEtForum
     * @return  Boolean
     */
    public function canAclMMovePost($oMbqEtForumPost, $oMbqEtForum) {
        if ($oMbqEtForumPost->canMove->oriValue) {
            return true;
        }
        return false;
    }
    
   
    
    /**
     * judge can m_approve_post
     *
     * @param  Object  $oMbqEtForumPost
     * @param  Integer  $mode
     * @return  Boolean
     */
    public function canAclMApprovePost($oMbqEtForumPost, $mode) {
        if ($mode == 1) {   //approve
            if ($oMbqEtForumPost->canApprove->oriValue && !$oMbqEtForumPost->isApproved->oriValue) {
                return true;
            }
        } elseif ($mode == 2) { //unapprove
            if ($oMbqEtForumPost->canApprove->oriValue && $oMbqEtForumPost->isApproved->oriValue) {
                return true;
            }
        }
        return false;
    }
  
    
    /**
     * judge can m_merge_post
     *
     * @return  Boolean
     */
    public function canAclMMergePost($oMbqEtForumPost) {
        if ($oMbqEtForumPost->canMove->oriValue) {
            return true;
        }
        return false;
    }
    
    
    
}

?>