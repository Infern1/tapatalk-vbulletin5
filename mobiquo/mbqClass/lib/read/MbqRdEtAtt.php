<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtAtt');

/**
 * attachment read class
 * 
 * @since  2012-8-14
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqRdEtAtt extends MbqBaseRdEtAtt {
    
    public function __construct() {
    }
    
    public function makeProperty(&$oMbqEtAtt, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    
    /**
     * get attachment objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byForumPostIds' means get data by forum post ids.$var is the ids.
     * @return  Mixed
     */
    public function getObjsMbqEtAtt($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'byForumPostIds') {
            $postIds = $var;
            $objsMbqEtAtt = array();
            foreach ($postIds as $postId) {
            	try {
                	$result = vB_Api::instanceInternal('node')->getNodeAttachments($postId);
                	if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    	$arrAttRecord = array();
                    	foreach ($result as $record) {
                    	    if ($record['filedataid']) {
                    	        $arrAttRecord[] = $record;
                    	    }
                    	}
                        foreach ($arrAttRecord as $attRecord) {
                            $objsMbqEtAtt[] = $this->initOMbqEtAtt($attRecord, array('case' => 'attRecord'));
                        }
                    } else {
                    	$arrAttRecord = array();
                    }
                } catch (Exception $e) {
                	$arrAttRecord = array();
                }
            }
            return $objsMbqEtAtt;
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    
    /**
     * init one attachment by condition
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'attRecord' means init attachment by attRecord
     * @return  Mixed
     */
    public function initOMbqEtAtt($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'attRecord') {
            $oMbqEtAtt = MbqMain::$oClk->newObj('MbqEtAtt');
            $oMbqEtAtt->attId->setOriValue($var['nodeid']);
            $oMbqEtAtt->postId->setOriValue($var['parentid']);
            $oMbqEtAtt->filtersSize->setOriValue($var['filesize']);
            $oMbqEtAtt->uploadFileName->setOriValue($var['filename']);
            $oMbqEtAtt->attType->setOriValue(MbqBaseFdt::getFdt('MbqFdtAtt.MbqEtAtt.attType.range.forumPostAtt'));
            $ext = strtolower($var['extension']);
            if ($ext == 'jpeg' || $ext == 'gif' || $ext == 'bmp' || $ext == 'png' || $ext == 'jpg') {
                $contentType = MbqBaseFdt::getFdt('MbqFdtAtt.MbqEtAtt.contentType.range.image');
            } elseif ($ext == 'pdf') {
                $contentType = MbqBaseFdt::getFdt('MbqFdtAtt.MbqEtAtt.contentType.range.pdf');
            } else {
                $contentType = MbqBaseFdt::getFdt('MbqFdtAtt.MbqEtAtt.contentType.range.other');
            }  
            if($var['contenttypeid']==16) $id = 'photoid';
            else $id='id';
            $oMbqEtAtt->contentType->setOriValue($contentType);
            $oMbqEtAtt->thumbnailUrl->setOriValue(MbqMain::$oMbqAppEnv->rootUrl.'/filedata/fetch?'.$id.'='.$var['nodeid'].'&type=thumb');
            $oMbqEtAtt->url->setOriValue(MbqMain::$oMbqAppEnv->rootUrl.'/filedata/fetch?'.$id.'='.$var['nodeid'].'&d='.$var['dateline']);
            $oMbqEtAtt->userId->setOriValue($var['userid']);
            $oMbqEtAtt->mbqBind['attRecord'] = $var;
            return $oMbqEtAtt;
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
  
}

?>