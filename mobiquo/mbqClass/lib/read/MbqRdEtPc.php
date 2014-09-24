<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtPc');

/**
 * private conversation read class
 * 
 * @since  2012-11-4
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqRdEtPc extends MbqBaseRdEtPc {
    
    public function __construct() {
    }
    
    public function makeProperty(&$oMbqEtPc, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    
    /**
     * get unread private conversations number
     *
     * @return  Integer
     */
    public function getUnreadPcNum() {
        if (MbqMain::hasLogin()) {
            try {
                $result = vB_Api::instanceInternal('content_privatemessage')->fetchSummary();
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    if ($var = $result['folders'][vB_Library_Content_Privatemessage::MESSAGE_FOLDER]) {
                        return $var['qty'];
                    } else {
                        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not find unread pm number.");
                    }
                } else {
                    MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get unread pm number.");
                }
            }  catch (Exception $e) {
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get unread pm number.");
            }
        } else {
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . 'Need login!');
        }
    }
    
    /**
     * get private conversation objs
     *
     * $mbqOpt['case'] = 'all' means get my all data.
     * $mbqOpt['case'] = 'byConvIds' means get data by conversation ids.$var is the ids.
     * $mbqOpt['case'] = 'byObjsStdPc' means get data by objsStdPc.$var is the objsStdPc.
     * @return  Mixed
     */
    public function getObjsMbqEtPc($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'all') {
            if ($mbqOpt['oMbqDataPage']) {
                $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                $oMbqRdEtPm = MbqMain::$oClk->newObj('MbqRdEtPm');
                $objsMbqEtPmBox = $oMbqRdEtPm->getObjsMbqEtPmBox();
                $rs = array();
                $ids = array();
                foreach ($objsMbqEtPmBox as $oMbqEtPmBox) {
                    try {
                        $result = vB_Api::instanceInternal('content_privatemessage')->listMessages(array(
                            "folderid" => $oMbqEtPmBox->boxId->oriValue,
                            "page" => 1,
                            "perpage" => 100000
                        ));
                        if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                            $rs[] = $result;
                        } else {
                            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get messages from box $oMbqEtPmBox->boxName->oriValue.");
                        }
                    }  catch (Exception $e) {
                        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get messages from box $oMbqEtPmBox->boxName->oriValue.");
                    }
                }
                foreach ($rs as $r) {
                    foreach ($r as $v) {
                        $ids[$v['nodeid']] = $v['nodeid'];
                    }
                }
                $oMbqDataPage->totalNum = count($ids);
                if(MbqMain::$cmd != 'get_conversations') krsort($ids);
                $nodeIds = array();
                $i = 1;
                foreach ($ids as $nodeId) {
                    if (($i >= $oMbqDataPage->startNum) && ($i <= ($oMbqDataPage->startNum + $oMbqDataPage->numPerPage - 1))) {
                        $nodeIds[] = $nodeId;
                    }
                    $i ++;
                }
                /* common begin */
                $mbqOpt['case'] = 'byConvIds';
                $mbqOpt['oMbqDataPage'] = $oMbqDataPage;
                return $this->getObjsMbqEtPc($nodeIds, $mbqOpt);
                /* common end */
            }
        } elseif ($mbqOpt['case'] == 'byConvIds') {
            $objsMbqEtPc = array();
            $userIds = array();
            try {
                $result = vB_Api::instanceInternal('node')->getFullContentforNodes($var);
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    $arrPcRecord = $result;
                    require_once(MBQ_APPEXTENTION_PATH.'ExttMbqVbLibraryContentPrivatemessage.php');
                    $oExttMbqVbLibraryContentPrivatemessage = new ExttMbqVbLibraryContentPrivatemessage();
                    foreach ($arrPcRecord as $pcRecord) {
                        $oMbqEtPc = MbqMain::$oClk->newObj('MbqEtPc');
                        $oMbqEtPc->mbqBind['pcRecord'] = $pcRecord;
                        try {
                            //$resultThread = vB_Api::instanceInternal('content_privatemessage')->getMessage($pcRecord['content']['nodeid']); //can cause mark read
                            $resultThread = $oExttMbqVbLibraryContentPrivatemessage->exttMbqGetMessage($pcRecord['content']['nodeid']);
                            if (!MbqMain::$oMbqAppEnv->exttHasErrors($resultThread)) {
                                $oMbqEtPc->mbqBind['pcThread'] = $resultThread;
                                $oMbqEtPc->mbqBind['participants'] = vB_Api::instanceInternal('content_privatemessage')->fetchParticipants($pcRecord['content']['nodeid']);
                                $oMbqEtPc->convId->setOriValue($pcRecord['content']['nodeid']);
                                $oMbqEtPc->convTitle->setOriValue($pcRecord['content']['title']);
                                $oMbqEtPc->totalMessageNum->setOriValue($pcRecord['content']['startertotalcount']);
                                $oMbqEtPc->participantCount->setOriValue(count($oMbqEtPc->mbqBind['participants']));
                                foreach ($oMbqEtPc->mbqBind['participants'] as $participant) {
                                    $userIds[$participant['userid']] = $participant['userid'];
                                }
                                $oMbqEtPc->startUserId->setOriValue($pcRecord['content']['starteruserid']);
                                $userIds[$pcRecord['content']['starteruserid']] = $pcRecord['content']['starteruserid'];
                                $oMbqEtPc->startConvTime->setOriValue($pcRecord['content']['created']);
                                $oMbqEtPc->lastUserId->setOriValue($pcRecord['content']['lastauthorid']);
                                $userIds[$pcRecord['content']['lastauthorid']] = $pcRecord['content']['lastauthorid'];
                                $oMbqEtPc->lastConvTime->setOriValue($pcRecord['content']['lastcontent']);
                                $oMbqEtPc->newPost->setOriValue($resultThread['message']['msgread'] ? false : true);
                                $oMbqEtPc->firstMsgId->setOriValue($pcRecord['content']['starter']);
                                $oMbqEtPc->deleteMode->setOriValue(MbqBaseFdt::getFdt('MbqFdtPc.MbqEtPc.deleteMode.range.soft-delete'));
                            } else {
                                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get conversation thread.");
                            }
                        } catch (Exception $e) {
                            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get conversation thread.");
                        }
                        $objsMbqEtPc[$oMbqEtPc->convId->oriValue] = $oMbqEtPc;
                    }
                } else {
                    MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get conversation records.");
                }
            } catch (Exception $e) {
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get conversation records.");
            }
            /* load objsRecipientMbqEtUser property and make relative property */
            $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
            $objsRecipientMbqEtUser = $oMbqRdEtUser->getObjsMbqEtUser($userIds, array('case' => 'byUserIds'));
            foreach ($objsMbqEtPc as &$oMbqEtPc) {
                foreach ($objsRecipientMbqEtUser as $oRecipientMbqEtUser) {
                    if (MbqMain::$oCurMbqEtUser->userId->oriValue == $oRecipientMbqEtUser->userId->oriValue) {
                        $oMbqEtPc->objsRecipientMbqEtUser[$oRecipientMbqEtUser->userId->oriValue] = $oRecipientMbqEtUser;
                    }
                    if ($oMbqEtPc->startUserId->oriValue == $oRecipientMbqEtUser->userId->oriValue) {
                        $oMbqEtPc->objsRecipientMbqEtUser[$oRecipientMbqEtUser->userId->oriValue] = $oRecipientMbqEtUser;
                    }
                    foreach ($oMbqEtPc->mbqBind['participants'] as $participant) {
                        if ($participant['userid'] == $oRecipientMbqEtUser->userId->oriValue) {
                            $oMbqEtPc->objsRecipientMbqEtUser[$oRecipientMbqEtUser->userId->oriValue] = $oRecipientMbqEtUser;
                        }
                    }
                }
            }
            if(MbqMain::$cmd != 'get_conversations') krsort($objsMbqEtPc);
            if ($mbqOpt['oMbqDataPage']) {
                $oMbqDataPage = $mbqOpt['oMbqDataPage'];
                $oMbqDataPage->datas = $objsMbqEtPc;
                return $oMbqDataPage;
            } else {
                return $objsMbqEtPc;
            }
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
  
}

?>