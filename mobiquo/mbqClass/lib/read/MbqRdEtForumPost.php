<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtForumPost');

/**
 * forum post read class
 * 
 * @since  2012-8-13
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqRdEtForumPost extends MbqBaseRdEtForumPost {
    
    public function __construct() {
    }
    
    public function makeProperty(&$oMbqEtForumPost, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    
    /**
     * get forum post position
     *
     * @param  Object  $oMbqEtForumPost
     *
     * @return  Integer
     */
    public function exttGetForumPostPosition($oMbqEtForumPost) {
        //ref $this->getObjsMbqEtForumPost(),case = 'topic'
        $search['channel'] = $oMbqEtForumPost->topicId->oriValue;
    	$search['view'] = vB_Api_Search::FILTER_VIEW_CONVERSATION_THREAD;
    	$search['depth'] = 1;
        $search['include_starter'] = true;
        $search['sort']['created'] = 'asc';
    	try {
        	$result = vB_Api::instanceInternal('search')->getInitialResults($search, 1000000, 1, true); //get all the posts to use
        	if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
            	$arrPostRecord = $result['results'];
            } else {
            	$arrPostRecord = array();
            }
        } catch (Exception $e) {
        	$arrPostRecord = array();
        }
        $ret = 1;
        foreach ($arrPostRecord as $postRecord) {
            if ($postRecord['content']['nodeid'] == $oMbqEtForumPost->postId->oriValue) {
                return $ret;
            }
            $ret ++;
        }
        //not found
        return 1;
    }
    
    /**
     * get forum post objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byTopic' means get data by forum topic obj.$var is the forum topic obj.
     * $mbqOpt['case'] = 'byPostIds' means get data by post ids.$var is the ids.
     * $mbqOpt['case'] = 'byArrPostRecord' means get data by byArrPostRecord.$var is the arrPostRecord.
     * $mbqOpt['case'] = 'byReplyUser' means get data by reply user.$var is the MbqEtUser obj.
     * @return  Mixed
     */
    public function getObjsMbqEtForumPost($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'byTopic') {
            $oMbqEtForumPost = $var;
            if ($mbqOpt['oMbqDataPage']) {
                $oMbqDataPage = $mbqOpt['oMbqDataPage'];
            	$search['channel'] = $oMbqEtForumPost->topicId->oriValue;
            	//$search['contenttypeid'] = vB_Api::instanceInternal('contenttype')->fetchContentTypeIdFromClass('Text');
            	$search['view'] = vB_Api_Search::FILTER_VIEW_CONVERSATION_THREAD;
            	//$search['depth'] = EXTTMBQ_NO_LIMIT_DEPTH;
            	$search['depth'] = 1;
	            $search['include_starter'] = true;
	            //$search['sort']['publishdate'] = 'asc';
	            $search['sort']['created'] = 'asc';
            	try {
                	$result = vB_Api::instanceInternal('search')->getInitialResults($search, $oMbqDataPage->numPerPage, $oMbqDataPage->curPage, true);
                	if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    	$oMbqDataPage->totalNum = $result['totalRecords'];
                    	$arrPostRecord = $result['results'];
                    } else {
                    	$oMbqDataPage->totalNum = 0;
                    	$arrPostRecord = array();
                    }
                } catch (Exception $e) {
                	$oMbqDataPage->totalNum = 0;
                	$arrPostRecord = array();
                }
                $nodeIds = array();
                foreach ($arrPostRecord as $postRecord) {
                    $nodeIds[] = $postRecord['nodeid'];
                }
                /* common begin */
                $mbqOpt['case'] = 'byPostIds';
                $mbqOpt['oMbqDataPage'] = $oMbqDataPage;
                return $this->getObjsMbqEtForumPost($nodeIds, $mbqOpt);
                /* common end */
            }
        } elseif ($mbqOpt['case'] == 'byPostIds') {
        	try {
            	$result = vB_Api::instanceInternal('node')->getFullContentforNodes($var);
            	if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                	$arrPostRecord = $result;
                } else {
                	$arrPostRecord = array();
                }
            } catch (Exception $e) {
            	$arrPostRecord = array();
            }
            /* common begin */
            $mbqOpt['case'] = 'byArrPostRecord';
            return $this->getObjsMbqEtForumPost($arrPostRecord, $mbqOpt);
            /* common end */
        } elseif ($mbqOpt['case'] == 'byReplyUser') {
            if ($mbqOpt['oMbqDataPage']) {
                $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                $top = vB_Api::instance('content_channel')->fetchTopLevelChannelIds();
                $search['channel'] = $top['forum'];
            	$search['authorid'] = $var->userId->oriValue;
            	$search['contenttypeid'] = vB_Api::instanceInternal('contenttype')->fetchContentTypeIdFromClass('Text');
            	$search['depth'] = EXTTMBQ_NO_LIMIT_DEPTH;
	            $search['sort']['publishdate'] = 'desc';
            	try {
                	$result = vB_Api::instanceInternal('search')->getInitialResults($search, $oMbqDataPage->numPerPage, $oMbqDataPage->curPage, true);
                	if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    	$oMbqDataPage->totalNum = $result['totalRecords'];
                    	$arrPostRecord = $result['results'];
                    } else {
                    	$oMbqDataPage->totalNum = 0;
                    	$arrPostRecord = array();
                    }
                } catch (Exception $e) {
                	$oMbqDataPage->totalNum = 0;
                	$arrPostRecord = array();
                }
                $nodeIds = array();
                foreach ($arrPostRecord as $postRecord) {
                    $nodeIds[] = $postRecord['nodeid'];
                }
                /* common begin */
                $mbqOpt['case'] = 'byPostIds';
                $mbqOpt['oMbqDataPage'] = $oMbqDataPage;
                return $this->getObjsMbqEtForumPost($nodeIds, $mbqOpt);
                /* common end */
            }
        } elseif ($mbqOpt['case'] == 'byArrPostRecord') {
            $arrPostRecord = $var;
            /* common begin */
            $objsMbqEtForumPost = array();
            $authorUserIds = array();
            $topicIds = array();
            foreach ($arrPostRecord as $postRecord) {
                $objsMbqEtForumPost[] = $this->initOMbqEtForumPost($postRecord, array('case' => 'postRecord'));
            }
            foreach ($objsMbqEtForumPost as $oMbqEtForumPost) {
                $authorUserIds[$oMbqEtForumPost->postAuthorId->oriValue] = $oMbqEtForumPost->postAuthorId->oriValue;
                $topicIds[$oMbqEtForumPost->topicId->oriValue] = $oMbqEtForumPost->topicId->oriValue;
            }
            /* load oMbqEtForumTopic property and oMbqEtForum property */
            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
            $objsMbqEtFroumTopic = $oMbqRdEtForumTopic->getObjsMbqEtForumTopic($topicIds, array('case' => 'byTopicIds'));
            foreach ($objsMbqEtFroumTopic as $oNewMbqEtFroumTopic) {
                foreach ($objsMbqEtForumPost as &$oMbqEtForumPost) {
                    if ($oNewMbqEtFroumTopic->topicId->oriValue == $oMbqEtForumPost->topicId->oriValue) {
                        $oMbqEtForumPost->oMbqEtForumTopic = $oNewMbqEtFroumTopic;
                        if ($oMbqEtForumPost->oMbqEtForumTopic->oMbqEtForum) {
                            $oMbqEtForumPost->oMbqEtForum = $oMbqEtForumPost->oMbqEtForumTopic->oMbqEtForum;
                            $oMbqEtForumPost->forumId->setOriValue($oMbqEtForumPost->oMbqEtForum->forumId->oriValue);
                        }
                    }
                }
            }
            /* load post author */
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            $objsAuthorMbqEtUser = $oMbqRdEtUser->getObjsMbqEtUser($authorUserIds, array('case' => 'byUserIds'));
            $postIds = array();
            foreach ($objsMbqEtForumPost as &$oMbqEtForumPost) {
                $postIds[] = $oMbqEtForumPost->postId->oriValue;
                foreach ($objsAuthorMbqEtUser as $oAuthorMbqEtUser) {
                    if ($oMbqEtForumPost->postAuthorId->oriValue == $oAuthorMbqEtUser->userId->oriValue) {
                        $oMbqEtForumPost->oAuthorMbqEtUser = $oAuthorMbqEtUser;
                        if ($oMbqEtForumPost->oAuthorMbqEtUser->isOnline->hasSetOriValue()) {
                            $oMbqEtForumPost->isOnline->setOriValue($oMbqEtForumPost->oAuthorMbqEtUser->isOnline->oriValue ? MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumPost.isOnline.range.yes') : MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumPost.isOnline.range.no'));
                        }
                        if ($oMbqEtForumPost->oAuthorMbqEtUser->iconUrl->hasSetOriValue()) {
                            $oMbqEtForumPost->authorIconUrl->setOriValue($oMbqEtForumPost->oAuthorMbqEtUser->iconUrl->oriValue);
                        }
                        break;
                    }
                }
            }
            /* load attachment */
            $oMbqRdEtAtt =  MbqMain::$oClk->newObj('MbqRdEtAtt');
            $objsMbqEtAtt = $oMbqRdEtAtt->getObjsMbqEtAtt($postIds, array('case' => 'byForumPostIds'));
            foreach ($objsMbqEtAtt as $oMbqEtAtt) {
                foreach ($objsMbqEtForumPost as &$oMbqEtForumPost) {
                    if ($oMbqEtForumPost->postId->oriValue == $oMbqEtAtt->postId->oriValue) {
                        $oMbqEtForumPost->objsMbqEtAtt[] = $oMbqEtAtt;
                    }
                }
            }
            /* load objsNotInContentMbqEtAtt */
            foreach ($objsMbqEtForumPost as &$oMbqEtForumPost) {
                $filedataids = MbqMain::$oMbqCm->getAttIdsFromContent($oMbqEtForumPost->postContent->oriValue);
                foreach ($oMbqEtForumPost->objsMbqEtAtt as $oMbqEtAtt) {
                    if (!in_array($oMbqEtAtt->mbqBind['attRecord']['filedataid'], $filedataids)) {
                        $oMbqEtForumPost->objsNotInContentMbqEtAtt[] = $oMbqEtAtt;
                    }
                }
            }
            /* load objsMbqEtThank property and make related properties/flags */
            //
            /* make other properties */
            $oMbqAclEtForumPost = MbqMain::$oClk->newObj('MbqAclEtForumPost');
            foreach ($objsMbqEtForumPost as &$oMbqEtForumPost) {
                if ($oMbqAclEtForumPost->canAclSaveRawPost($oMbqEtForumPost)) {
                    $oMbqEtForumPost->canEdit->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumPost.canEdit.range.yes'));
                } else {
                    $oMbqEtForumPost->canEdit->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumPost.canEdit.range.no'));
                }
            }
            /* common end */
            if ($mbqOpt['oMbqDataPage']) {
                $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                $oMbqDataPage->datas = $objsMbqEtForumPost;
                return $oMbqDataPage;
            } else {
                return $objsMbqEtForumPost;
            }
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    
    /**
     * init one forum post by condition
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'postRecord' means init forum post by postRecord
     * $mbqOpt['case'] = 'byPostId' means init forum post by post id
     * @return  Mixed
     */
    public function initOMbqEtForumPost($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'postRecord') {
            $nodeid = $var['content']['nodeid'];
            $result = vB_Api::instanceInternal('content_text')->getDataForParse(array($nodeid));
            //the $result[$nodeid]['bbcodeoptions'] caused guest can see limited content for example:image,so removed it
            $macro = vB5_Template_NodeText::instance()->register($nodeid);
		
            //$macro = vB5_Template_NodeText::instance()->register($nodeid, $result[$nodeid]['bbcodeoptions']);
            vB5_Template_NodeText::instance()->replacePlaceholders($macro);
			
            $macro .= $this->getDataLink($var);
            $oMbqEtForumPost = MbqMain::$oClk->newObj('MbqEtForumPost');
            $oMbqEtForumPost->postId->setOriValue($var['content']['nodeid']);
            $oMbqEtForumPost->forumId->setOriValue($var['content']['channelid']);
            $oMbqEtForumPost->topicId->setOriValue($var['content']['starter']);
            $oMbqEtForumPost->postTitle->setOriValue($var['content']['title']);
            $oMbqEtForumPost->postAuthorId->setOriValue($var['content']['userid']);
            $oMbqEtForumPost->postTime->setOriValue($var['content']['created']);
            $oMbqEtForumPost->mbqBind['postRecord'] = $var;
            $oMbqEtForumPost->mbqBind['bbcodeoptions'] = $result[$nodeid]['bbcodeoptions'];
            $oMbqEtForumPost->postContent->setOriValue($var['content']['rawtext']);
            $oMbqEtForumPost->postContent->setAppDisplayValue($macro);
            $oMbqEtForumPost->postContent->setTmlDisplayValue(htmlspecialchars_decode($this->processContentForDisplay($macro, true, $oMbqEtForumPost)));
            $oMbqEtForumPost->postContent->setTmlDisplayValueNoHtml(htmlspecialchars_decode($this->processContentForDisplay($macro, false, $oMbqEtForumPost)));
            $oMbqEtForumPost->shortContent->setOriValue(MbqMain::$oMbqCm->getShortContent($oMbqEtForumPost->postContent->tmlDisplayValue));
            
                        
            
           
            
            /* add info theard */
           
            if (!$var['showpublished'] && $var['deleteuserid']) {
                $oMbqEtForumPost->isDeleted->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.isDeleted.range.yes'));
            } else {
                $oMbqEtForumPost->isDeleted->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.isDeleted.range.no'));
            }

            if ($var['approved'] == 1) {
                $oMbqEtForumPost->isApproved->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.isApproved.range.yes'));
            } else {
                $oMbqEtForumPost->isApproved->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.isApproved.range.no'));
            }
            
            if ($var['content']['approved']) {
                $oMbqEtForumPost->state->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumPost.state.range.postOk'));
            } else {
                $oMbqEtForumPost->state->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumPost.state.range.postOkNeedModeration'));
            }
            
            
             if (MbqMain::hasLogin()) {
                
                /* add moderation */
                $oCurJUser = (object) MbqMain::$oMbqAppEnv->currentUserInfo;
                $moderatorperms = (object) $var['content']['moderatorperms'];
               
                
                if ($oMbqEtForumPost->isDeleted->oriValue) {
                    if ($moderatorperms->candeleteposts) {
                        $oMbqEtForumPost->canDelete->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canDelete.range.yes'));
                    } else {
                        $oMbqEtForumPost->canDelete->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canDelete.range.no'));
                    }
                } else {
                    if ($moderatorperms->candeleteposts) {
                        $oMbqEtForumPost->canDelete->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canDelete.range.yes'));
                    } else {
                        $oMbqEtForumPost->canDelete->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canDelete.range.no'));
                    }
                }
                
                
                if ($moderatorperms->canmove) {
                    $oMbqEtForumPost->canApprove->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canApprove.range.yes'));
                } else {
                    $oMbqEtForumPost->canApprove->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canApprove.range.no'));
                }
                if ($moderatorperms->canmove) {
                    $oMbqEtForumPost->canMove->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canClose.range.yes'));
                } else {
                    $oMbqEtForumPost->canMove->setOriValue(MbqBaseFdt::getFdt('MbqFdtForum.MbqEtForumTopic.canClose.range.no'));
                }
                
                /* end moderation */
                
            }
            
            
            
            return $oMbqEtForumPost;
        } elseif ($mbqOpt['case'] == 'byPostId') {
            $objsMbqEtForumPost = $this->getObjsMbqEtForumPost(array($var), array('case' => 'byPostIds'));
            if ($objsMbqEtForumPost) {
                return $objsMbqEtForumPost[0];
            } else {
                return false;
            }
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    
    public function getDataLink($node){
        $link = '<br />';
        $contenttype = vB_Types::instance()->getContentTypes();
        foreach ($contenttype as $type){
            if(isset($node['contenttypeid']) and $node['contenttypeid']==$type['id'] ) $link = $this->get($type['class'], $node);
        }
        return $link;
    }

    public function get($class, $node){
        $fn = "get$class";
        $data = '';
        $classes = array('Photo','Link','Video');
        if(in_array($class, $classes)){
            $data =  $this->$fn($node);
            $data .= '<br />'. $this->getText($node);
        }
        return $data ;
    }
    
    public function getPhoto($node){
        return $img = '[img]' . MbqMain::$oMbqAppEnv->rootUrl.'/filedata/fetch?photoid='.$node['link_nodeid'] . '[/img]';
    }
    
    public function getPicture($node){
        return $img = '[img]' . MbqMain::$oMbqAppEnv->rootUrl.'/filedata/fetch?linkid='.$node['link_nodeid'] . '[/img]';
    }
    
    function processContentAttachFile($post, $node){
        $attach = $node['content']['attach'];
        if($attach){
            preg_match_all('/\[img\].*?\/fetch\?filedataid=([0-9]{1,10}).*?\[\/img\]/i', $post, $math);
            if($math[1]) foreach ($attach as $file){
               foreach ($math[1] as $fid){
                   if($file['filedataid'] == $fid){
                       $post = preg_replace("/\[img\].*\/fetch\?filedataid=$fid.*\[\/img\]/i", '[IMG]'.MbqMain::$oMbqAppEnv->rootUrl.'/filedata/fetch?id='.$file['nodeid'].'[/IMG]', $post);		
                   }
               }
            }
        }
       return $post;
    }
    
    public function getVideo($node){
        $providers = array(
            'hulu' => 'http://www.hulu.com/embed/videocode',
            'youtube' => 'http://www.youtube.com/v/kXYiU_JCYtU?fs=1&hd=0&rel=1&cc_load_policy=1',
            'youtube_share' => 'http://www.youtube.com/embed/videocode?wmode={vb:raw vboptions.player_wmode}&autoplay=1',
            'vimeo' => 'http://vimeo.com/moogaloop.swf?clip_id=videocode',
            'dailymotion' => 'http://www.dailymotion.com/swf/videocode',
            'metacafe' => 'http://www.metacafe.com/fplayer/videocode.swf',
            'google' => 'http://video.google.com/googleplayer.swf?docid=videocode',
            'facebook' => 'http://www.facebook.com/v/videocode',
        );
        $items = array();
        foreach ($node['items'] as $video){
            $url = str_replace('videocode', $video['code'], $providers[$video['provider']]);
            $items[] = '[url=' . $url . ']' . $url . '[/url]';
        }
        return implode('<br />', $items);
    }
    
    public function getLink($node){
        if(isset($node['link_nodeid'])) return $img = '[img]' . MbqMain::$oMbqAppEnv->rootUrl.'/filedata/fetch?linkid='.$node['link_nodeid'] . '[/img]';
        return $link = '[url=' . $node['url'] . ']' . $node['url'] . '[/url]';
    }
    
    public function getText($node){
        return $node['meta'];
    }
    
    /**
     * process content for display in mobile app
     *
     * @params  String  $content
     * @params  Boolean  $returnHtml
     * @params  Object  $obj($oMbqEtForumPost/$oMbqEtPcMsg)
     * @return  String
     */
    public function processContentForDisplay($content, $returnHtml, $obj) {
        /*
        support bbcode:url/img/quote
        support html:br/i/b/u/font+color(red/blue)
        <strong> -> <b>
        attention input param:return_html
        attention output param:post_content
        */
		
        $post = $content;


        if ($returnHtml) {
            //MbqCm::writeLog($content."\n\n\n\n--------------------------------------------------------\n\n\n\n", true);
            if ($obj->mbqBind['bbcodeoptions']['allowsmilies']) {
                /* directly display smillies picture before app implement it for vb5
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/biggrin.png"[^>]*?\/>/i', ':D', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/frown.png"[^>]*?\/>/i', ':(', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/mad.png"[^>]*?\/>/i', ':mad:', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/tongue.png"[^>]*?\/>/i', ':p', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/redface.png"[^>]*?\/>/i', ':o', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/confused.png"[^>]*?\/>/i', ':confused:', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/wink.png"[^>]*?\/>/i', ';)', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/smile.png"[^>]*?\/>/i', ':)', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/rolleyes.png"[^>]*?\/>/i', ':rolleyes:', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/cool.png"[^>]*?\/>/i', ':cool:', $post);
            	$post = preg_replace('/<img [^>]*?src="[^>]*?\/core\/images\/smilies\/eek.png"[^>]*?\/>/i', ':eek:', $post);
            	*/
            } else {
            }

            
            if ($obj->mbqBind['bbcodeoptions']['allowbbcode']) {
    	        $post = preg_replace('/<div class="bbcode_container">.*?<div class="bbcode_quote">.*?<div class="quote_container">.*?<div class="bbcode_quote_container vb-icon vb-icon-quote-large"><\/div>.*?<div class="bbcode_postedby">.*?<strong>(.*?)<\/strong>.*?<\/div>.*?<div class="message">(.*?)<\/div>.*?<\/div>.*?<\/div>.*?<\/div>/is', '$1 wrote:[quote]$2[/quote]', $post);    //quote no quoted content
    	        $post = preg_replace('/<div class="bbcode_container">.*?<div class="bbcode_quote">.*?<div class="quote_container">.*?<div class="bbcode_quote_container vb-icon vb-icon-quote-large"><\/div>.*?<div class="bbcode_postedby">.*?<strong>(.*?)<\/strong>.*?<\/div>.*?<div class="message"><!-- ##.*?## -->(.*?)<\/div>.*?<\/div>.*?<\/div>.*?<\/div>/is', '$1 wrote:[quote]$2[/quote]', $post);    //quote another quoted content
    	        $post = preg_replace('/<div class="bbcode_container">.*?<div class="bbcode_quote">.*?<div class="quote_container">.*?<div class="bbcode_quote_container vb-icon vb-icon-quote-large"><\/div>(.*?)<\/div>.*?<\/div>.*?<\/div>/is', '[quote]$1[/quote]', $post);    //simple quote,for example:[quote]anything[/quote]
    	        $post = preg_replace_callback('/<font color=\"(\#.*?)\">(.*?)<\/font>/is', create_function('$matches','return MbqMain::$oMbqCm->mbColorConvert($matches[1], $matches[2]);'), $post);
            	$post = str_ireplace('<strong>', '<b>', $post);
            	$post = str_ireplace('</strong>', '</b>', $post);
    	        $post = preg_replace('/<img .*?src="(.*?)" .*?\/>/i', '[img]$1[/img]', $post);
    	        $post = preg_replace('/<a .*?href="mailto:(.*?)".*?>(.*?)<\/a>/i', '[url=$1]$2[/url]', $post);
    	        $post = preg_replace('/<a .*?href="(.*?)".*?>(.*?)<\/a>/i', '[url=$1]$2[/url]', $post);
                $post = preg_replace('/<a.*?href="(.*?)".*?\s+>\s+.*?\s+(<img[^>]+src\s*=\s*"(.*?)"[^>].*?\s+\/?\>)?\s+.*\s+.*\s+<\/a>/i', '[url=$1]$1[/url]', $post);
                //preg_match ("/<img[^>]+src\s*=\s*[\"']\/?([^\"']+)[\"'][^>]*\>/", $msg, $m);
                //preg_match('/<a .*?href="(.*?)".*?\s+>\s+.*?\s+<img[^>]+src\s*=\s*"(.*?)"[^>]*\>.*?<\/a>/i',$post, $x );
		
    	        $post = preg_replace('/<div class="bbcode_container">[^<]*?<div class="bbcode_description">PHP Code\:<\/div>[^<]*?<div class="bbcode_code"[^>]*?><code><code>(.*?)<\/code><\/code><\/div>[^<]*?<\/div>/is', 'PHP Code:[quote]$1[/quote]', $post);    //php
    	        $post = preg_replace('/<div class="bbcode_container">[^<]*?<div class="bbcode_description">Code\:<\/div>[^<]*?<pre class="bbcode_code"[^>]*?>(.*?)<\/pre>[^<]*?<\/div>/is', 'Code:[quote]$1[/quote]', $post);    //code
    	        $post = preg_replace('/<div class="bbcode_container">[^<]*?<div class="bbcode_description">HTML Code\:<\/div>[^<]*?<pre class="bbcode_code"[^>]*?>(.*?)<\/pre>[^<]*?<\/div>/is', 'HTML Code:[quote]$1[/quote]', $post);    //html
    	        //remove Attached Files html code
				
    	        $post = preg_replace('/<div class="attachment-list">[^<]*?Attached Files[^<]*?<ul>.*?<\/ul>[^<]*?<\/div>/is', '', $post);
    	        $post = preg_replace('/<div class="b-post-attachments">[^<]*?Attached Files[^<]*?<ul>.*?<\/ul>[^<]*?<\/div>/is', '', $post);
                $post = preg_replace('/<object .*?>.*?<embed src="(.*?)".*?\><\/object>/is', '[url=$1]$1[/url]', $post); /* for youtube content etc. */
                $post = str_ireplace('<hr />', '<br />____________________________________<br />', $post);
        	    
                $post = str_ireplace('<li>', "\t\t<li>", $post);

                $post = str_ireplace('</li>', "</li><br />", $post);
                $post = str_ireplace('</tr>', '</tr><br />', $post);
                $post = str_ireplace('</td>', "</td>\t\t", $post);
    	        $post = str_ireplace('</div>', '</div><br />', $post);
    	        $post = str_ireplace('&nbsp;', ' ', $post);
				
    	        $post = strip_tags($post, '<br><i><b><u><font>');
				
            } else {
            }
        } else {
    	    $post = strip_tags($post);
        }
	$post = $this->processContentAttachFile($post, $obj->mbqBind['postRecord']);	
        $post = trim($post);
        //MbqCm::writeLog($post."\n\n\n\n--------------------------------------------------------\n\n\n\n", true);
    	return $post;
    }
    
    /**
     * return quote post content
     *
     * @param  Object  $oMbqEtForumPost
     * @return  String
     */
    public function getQuotePostContent($oMbqEtForumPost) {
        $oldContent = preg_replace('/\[quote.*?\].*?\[\/quote\]/is', '', $oMbqEtForumPost->postContent->oriValue);
        $ret = '[QUOTE='.$oMbqEtForumPost->oAuthorMbqEtUser->getDisplayName().';'.$oMbqEtForumPost->postId->oriValue.']'.$oldContent.'[/QUOTE]';
        return $ret;
    }
  
}

?>