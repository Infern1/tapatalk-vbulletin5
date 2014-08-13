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
     * @param  Object  $oMbqEtForumTopic
     * @return  Boolean
     */
    public function canAclReplyPost($oMbqEtForumTopic) {
        if (MbqMain::hasLogin() && $oMbqEtForumTopic->mbqBind['topicRecord']['content']['can_comment'] && vB_Api::instanceInternal('user')->hasPermissions('createpermissions', 'vbforum_text', $oMbqEtForumTopic->topicId->oriValue)) {
            if (
            ($oMbqEtForumTopic->mbqBind['topicRecord']['content']['showopen'] || (!$oMbqEtForumTopic->mbqBind['topicRecord']['content']['showopen'] && $oMbqEtForumTopic->mbqBind['topicRecord']['content']['canmoderate'])) 
            && 
            ($oMbqEtForumTopic->mbqBind['topicRecord']['content']['showapproved'] || (!$oMbqEtForumTopic->mbqBind['topicRecord']['content']['showapproved'] && $oMbqEtForumTopic->mbqBind['topicRecord']['content']['canmoderate'])) 
            && 
            ($oMbqEtForumTopic->mbqBind['topicRecord']['content']['showpublished'] || (!$oMbqEtForumTopic->mbqBind['topicRecord']['content']['showpublished'] && $oMbqEtForumTopic->mbqBind['topicRecord']['content']['canmoderate']))
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
  
}

?>