<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdForumSearch');

/**
 * forum search class
 * 
 * @since  2012-8-27
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqRdForumSearch extends MbqBaseRdForumSearch {
    
    public function __construct() {
    }
    
    /**
     * forum advanced search
     *
     * @param  Array  $filter  search filter
     * @param  Object  $oMbqDataPage
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'advanced' means advanced search
     * @return  Object  $oMbqDataPage
     */
    public function forumAdvancedSearch($filter, $oMbqDataPage, $mbqOpt) {
        if ($mbqOpt['case'] == 'getLatestTopic' || $mbqOpt['case'] == 'getUnreadTopic' || $mbqOpt['case'] == 'getParticipatedTopic') {
            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
            if ($mbqOpt['case'] == 'getParticipatedTopic') {
                $top = vB_Api::instance('content_channel')->fetchTopLevelChannelIds();
                $search['channel'] = $top['forum'];
            	$search['authorid'] = MbqMain::$oCurMbqEtUser->userId->oriValue;
            	$search['contenttypeid'] = vB_Api::instanceInternal('contenttype')->fetchContentTypeIdFromClass('Text');
            	$search['depth'] = EXTTMBQ_NO_LIMIT_DEPTH;
	            $search['sort']['publishdate'] = 'desc';
            	$search['exclude'] = MbqMain::$oMbqAppEnv->hideForumIds;
                try {
                    $result = vB_Api::instanceInternal('search')->getInitialResults($search, 100000, 1, true);
                    if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                        $allTopicIds = array();
                        foreach ($result['results'] as $r) {
                            $allTopicIds[$r['content']['starter']] = $r['content']['starter'];
                        }
                        $oMbqDataPage->totalNum = count($allTopicIds);
                        $nodeIds = array();
                        $i = 1;
                        foreach ($allTopicIds as $nodeId) {
                            if (($i >= $oMbqDataPage->startNum) && ($i <= ($oMbqDataPage->startNum + $oMbqDataPage->numPerPage - 1))) {
                                $nodeIds[] = $nodeId;
                            }
                            $i ++;
                        }
                    } else {
                        $oMbqDataPage->totalNum = 0;
                        $nodeIds = array();
                    }
                } catch (Exception $e) {
                    $oMbqDataPage->totalNum = 0;
                    $nodeIds = array();
                }
                /* common begin */
                $mbqOpt['case'] = 'byTopicIds';
                $mbqOpt['oMbqDataPage'] = $oMbqDataPage;
                return $oMbqRdEtForumTopic->getObjsMbqEtForumTopic($nodeIds, $mbqOpt);
                /* common end */
            } elseif ($mbqOpt['case'] == 'getLatestTopic' || $mbqOpt['case'] == 'getUnreadTopic') {
                $top = vB_Api::instance('content_channel')->fetchTopLevelChannelIds();
                $search['channel'] = $top['forum'];
                $search['view'] = vB_Api_Search::FILTER_VIEW_TOPIC;
                $search['depth'] = EXTTMBQ_NO_LIMIT_DEPTH;
                $search['sort']['lastcontent'] = 'desc';
                $search['exclude'] = MbqMain::$oMbqAppEnv->hideForumIds;
                if(!empty($mbqOpt['unread'])) $search['unread_only'] = true;
                try {
                    $result = vB_Api::instanceInternal('search')->getInitialResults($search, $oMbqDataPage->numPerPage, $oMbqDataPage->curPage, true);
                    if($search['unread_only']){
                        foreach ($result['results'] as $nodeid=>$node){
                            if($oMbqRdEtForumTopic->isRead($node)){
                                unset($result['results'][$nodeid]);
                                $result['totalRecords']--;
                            }
                        }
                    }
                    if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                        $oMbqDataPage->totalNum = $result['totalRecords'];
                        $arrTopicRecord = $result['results'];
                    } else {
                        $oMbqDataPage->totalNum = 0;
                        $arrTopicRecord = array();
                    }
                } catch (Exception $e) {
                    $oMbqDataPage->totalNum = 0;
                    $arrTopicRecord = array();
                }
                /* common begin */
                $nodeIds = array();
                foreach ($arrTopicRecord as $topicRecord) {
                    $nodeIds[] = $topicRecord['nodeid'];
                }
                $mbqOpt['case'] = 'byTopicIds';
                $mbqOpt['oMbqDataPage'] = $oMbqDataPage;
                return $oMbqRdEtForumTopic->getObjsMbqEtForumTopic($nodeIds, $mbqOpt);
                /* common end */
            }
        } elseif ($mbqOpt['case'] == 'searchTopic') {
            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
            $top = vB_Api::instance('content_channel')->fetchTopLevelChannelIds();
            $search['channel'] = $top['forum'];
        	$search['contenttypeid'] = vB_Api::instanceInternal('contenttype')->fetchContentTypeIdFromClass('Text');
        	$search['depth'] = EXTTMBQ_NO_LIMIT_DEPTH;
            $search['sort']['publishdate'] = 'desc';
            $search['keywords'] = $filter['keywords'];
            $search['exclude'] = MbqMain::$oMbqAppEnv->hideForumIds;
            try {
                $result = vB_Api::instanceInternal('search')->getInitialResults($search, 100000, 1, true);
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    $allTopicIds = array();
                    foreach ($result['results'] as $r) {
                        $allTopicIds[$r['content']['starter']] = $r['content']['starter'];
                    }
                    $oMbqDataPage->totalNum = count($allTopicIds);
                    $nodeIds = array();
                    $i = 1;
                    foreach ($allTopicIds as $nodeId) {
                        if (($i >= $oMbqDataPage->startNum) && ($i <= ($oMbqDataPage->startNum + $oMbqDataPage->numPerPage - 1))) {
                            $nodeIds[] = $nodeId;
                        }
                        $i ++;
                    }
                } else {
                    $oMbqDataPage->totalNum = 0;
                    $nodeIds = array();
                }
            } catch (Exception $e) {
                $oMbqDataPage->totalNum = 0;
                $nodeIds = array();
            }
            /* common begin */
            $mbqOpt['case'] = 'byTopicIds';
            $mbqOpt['oMbqDataPage'] = $oMbqDataPage;
            return $oMbqRdEtForumTopic->getObjsMbqEtForumTopic($nodeIds, $mbqOpt);
            /* common end */
        } elseif ($mbqOpt['case'] == 'searchPost') {
            $oMbqRdEtForumPost = MbqMain::$oClk->newObj('MbqRdEtForumPost');
            $top = vB_Api::instance('content_channel')->fetchTopLevelChannelIds();
            $search['channel'] = $top['forum'];
        	$search['contenttypeid'] = vB_Api::instanceInternal('contenttype')->fetchContentTypeIdFromClass('Text');
        	$search['depth'] = EXTTMBQ_NO_LIMIT_DEPTH;
            $search['sort']['publishdate'] = 'desc';
            $search['keywords'] = $filter['keywords'];
            $search['exclude'] = MbqMain::$oMbqAppEnv->hideForumIds;
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
            return $oMbqRdEtForumPost->getObjsMbqEtForumPost($nodeIds, $mbqOpt);
            /* common end */
        } elseif ($mbqOpt['case'] == 'advanced') {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_NOT_ACHIEVE);
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
}

?>