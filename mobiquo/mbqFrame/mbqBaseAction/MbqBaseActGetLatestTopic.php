<?php

defined('MBQ_IN_IT') or exit;

/**
 * get_latest_topic action
 * 
 * @since  2012-8-27
 * @author Wu ZeTao <578014287@qq.com>
 */
Abstract Class MbqBaseActGetLatestTopic extends MbqBaseAct {
    
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
        if($ioHandleClass = 'MbqIoHandleJson')
        {
            $oMbqDataPage = MbqMain::$oClk->newObj('MbqDataPage');
            $oMbqDataPage->initByPageAndPerPage(MbqMain::$input->page, MbqMain::$input->perPage);
            $filter = array(
                'searchid' => MbqMain::$input->searchId,
                'page' => $oMbqDataPage->curPage,
                'perpage' => $oMbqDataPage->numPerPage
            );
            if (MbqMain::$input->filter && is_array(MbqMain::$input->filter)) {
                $filter = array_merge($filter, MbqMain::$input->filter);
            }
        }
        else
        {
            $startNum = (int) MbqMain::$input[0];
            $lastNum = (int) MbqMain::$input[1];
            $oMbqDataPage = MbqMain::$oClk->newObj('MbqDataPage');
            $oMbqDataPage->initByStartAndLast($startNum, $lastNum);
            $filter = array(
                'searchid' => MbqMain::$input[2],
                'page' => $oMbqDataPage->curPage,
                'perpage' => $oMbqDataPage->numPerPage
            );
            if (MbqMain::$input[3] && is_array(MbqMain::$input[3])) {
                $filter = array_merge($filter, MbqMain::$input[3]);
            }
        }
        $filter['showposts'] = 0;
        $oMbqAclEtForumTopic = MbqMain::$oClk->newObj('MbqAclEtForumTopic');
        if ($oMbqAclEtForumTopic->canAclGetLatestTopic()) {    //acl judge
            $oMbqRdForumSearch = MbqMain::$oClk->newObj('MbqRdForumSearch');
            $oMbqDataPage = $oMbqRdForumSearch->forumAdvancedSearch($filter, $oMbqDataPage, array('case' => 'getLatestTopic'));
            $oMbqRdEtForumTopic = MbqMain::$oClk->newObj('MbqRdEtForumTopic');
            $this->data['result'] = true;
            $this->data['total_topic_num'] = (int) $oMbqDataPage->totalNum;
            $this->data['topics'] = $oMbqRdEtForumTopic->returnApiArrDataForumTopic($oMbqDataPage->datas);
        } else {
            MbqError::alert('', '', '', MBQ_ERR_APP);
        }
    }
  
}

?>