<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseRdEtUser');

/**
 * user read class
 * 
 * @since  2012-8-6
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqRdEtUser extends MbqBaseRdEtUser {
    
    public function __construct() {
    }
    
    public function makeProperty(&$oMbqEtUser, $pName, $mbqOpt = array()) {
        switch ($pName) {
            default:
            MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_PNAME . ':' . $pName . '.');
            break;
        }
    }
    
    /**
     * get user objs
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'byUserIds' means get data by user ids.$var is the ids.
     * @mbqOpt['case'] = 'online' means get online user.
     * @return  Array
     */
    public function getObjsMbqEtUser($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'byUserIds') {
            $arrUserRecord = array();
            foreach ($var as $userId) {
                try {
                    $result = vB_Api::instanceInternal('user')->fetchProfileInfo($userId);
                    if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                        $arrUserRecord[] = $result;
                    } else {
                        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get user profile.");
                    }
                } catch (Exception $e) {
                    MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get user profile.");
                }
            }
            $objsMbqEtUser = array();
            foreach ($arrUserRecord as $userRecord) {
                $objsMbqEtUser[] = $this->initOMbqEtUser($userRecord, array('case' => 'userRecord'));
            }
            return $objsMbqEtUser;
        } elseif ($mbqOpt['case'] == 'online') {
            try {
                $result = vB_Api::instance('wol')->fetchAll();
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    $userIds = array();
                    foreach ($result as $record) {
                        if ($record['userid']) {
                            $userIds[] = $record['userid'];
                        }
                    }
                    return $this->getObjsMbqEtUser($userIds, array('case' => 'byUserIds'));
                } else {
                    MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get online user.");
                }
                /*
                require_once(MBQ_APPEXTENTION_PATH.'ExttMbqFunctions.php');
                $oExttMbqFunctions = new ExttMbqFunctions();
                $result = $oExttMbqFunctions->exttMbqFetchAll();
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    $userIds = array();
                    foreach ($result as $record) {
                        if ($record['userid']) {
                            $userIds[] = $record['userid'];
                        }
                    }
                    return $this->getObjsMbqEtUser($userIds, array('case' => 'byUserIds'));
                } else {
                    MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get online user.");
                }
                */
            } catch (Exception $e) {
                MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . "Can not get online user.");
            }
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    
    /**
     * init one user by condition
     *
     * @param  Mixed  $var
     * @param  Array  $mbqOpt
     * $mbqOpt['case'] = 'userRecord' means init user by userRecord.$var is userRecord.
     * $mbqOpt['case'] = 'byUserId' means init user by user id.$var is user id.
     * $mbqOpt['case'] = 'byLoginName' means init user by login name.$var is login name.
     * @return  Mixed
     */
    public function initOMbqEtUser($var, $mbqOpt) {
        if ($mbqOpt['case'] == 'userRecord') {
            $oMbqEtUser = MbqMain::$oClk->newObj('MbqEtUser');
            $oMbqEtUser->userId->setOriValue($var['userid']);
            $oMbqEtUser->loginName->setOriValue($var['username']);
            $oMbqEtUser->userName->setOriValue($var['username']);
            $oMbqEtUser->userGroupIds->setOriValue(array($var['usergroupid']));
            $iconUrl = vB_Api::instanceInternal('user')->fetchAvatar($var['userid'], true);
            $iconUrl = $iconUrl['avatarpath'];
            $iconUrl = MbqMain::$oMbqAppEnv->baseUrlCore . '/' . $iconUrl;
            $oMbqEtUser->iconUrl->setOriValue($iconUrl);
            $oMbqEtUser->canSearch->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canSearch.range.yes'));
            $oMbqEtUser->postCount->setOriValue($var['posts']);
            if ($var['status']) {
                $displayText = $var['status'];
            } else {
                $displayText = $var['usertitle'];
            }
            $oMbqEtUser->displayText->setOriValue($displayText);
            $oMbqEtUser->regTime->setOriValue($var['joindate']);
            $oMbqEtUser->lastActivityTime->setOriValue($var['lastactivity']);
            $oMbqEtUser->canWhosonline->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canWhosonline.range.yes'));
            if (MbqMain::$oMbqAppEnv->exttOptions['enablepms']) {
                $oMbqEtUser->canPm->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canPm.range.yes'));
                if ($var['receivepm']) {
                    $oMbqEtUser->acceptPm->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.acceptPm.range.yes'));
                }
                $oMbqEtUser->canSendPm->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canSendPm.range.yes'));
            }
            if(vB::getUserContext()->isModerator()) $oMbqEtUser->canModerate->setOriValue(MbqBaseFdt::getFdt('MbqFdtUser.MbqEtUser.canSendPm.range.yes'));
            $oMbqEtUser->maxAttachment->setOriValue(MbqMain::$oMbqAppEnv->exttOptions['maximages']);
            $oMbqEtUser->maxPngSize->setOriValue(MbqMain::$oMbqAppEnv->exttAttachmentcache['png']['size']);
            $jpgMaxSize = MbqMain::$oMbqAppEnv->exttAttachmentcache['jpg']['size'];
            $jpegMaxSize = MbqMain::$oMbqAppEnv->exttAttachmentcache['jpeg']['size'];
            $oMbqEtUser->maxJpgSize->setOriValue($jpgMaxSize > $jpegMaxSize ? $jpgMaxSize : $jpegMaxSize);
            $oMbqEtUser->mbqBind['userRecord'] = $var;
            return $oMbqEtUser;
        } elseif ($mbqOpt['case'] == 'byUserId') {
            $userIds = array($var);
            $objsMbqEtUser = $this->getObjsMbqEtUser($userIds, array('case' => 'byUserIds'));
            if (is_array($objsMbqEtUser) && (count($objsMbqEtUser) == 1)) {
                return $objsMbqEtUser[0];
            }
            return false;
        } elseif ($mbqOpt['case'] == 'byLoginName') {
            try {
                $result = vB_Api::instanceInternal('user')->fetchByUsername($var);
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    if ($result) {
                        return $this->initOMbqEtUser($result['userid'], array('case' => 'byUserId'));
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        MbqError::alert('', __METHOD__ . ',line:' . __LINE__ . '.' . MBQ_ERR_INFO_UNKNOWN_CASE);
    }
    
    /**
     * get user display name
     *
     * @param  Object  $oMbqEtUser
     * @return  String
     */
    public function getDisplayName($oMbqEtUser) {
        //return $oMbqEtUser->loginName->oriValue;
        return htmlspecialchars_decode($oMbqEtUser->loginName->oriValue);
    }
    
    /**
     * login
     *
     * @param  String  $loginName
     * @param  String  $password
     * @return  Boolean  return true when login success.
     */
    public function login($loginName, $password) {
        try {
            $result = vB_Api::instance('user')->login($loginName, $password);
            if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                if ($result['userid']) {
                    $newResult = vB_Api::instance('user')->fetchCurrentUserinfo();
                    if ($newResult['userid']) {
                        vB5_Cookie::set('cpsession', $result['cpsession'], 30);
                        vB5_Cookie::set('sessionhash', $result['sessionhash'], 30);
                        vB5_Cookie::set('password', $result['password'], 30);
                        vB5_Cookie::set('userid', $result['userid'], 30);
                        MbqMain::$oMbqAppEnv->currentUserInfo = $newResult;
                        $this->initOCurMbqEtUser();
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * logout
     *
     * @return  Boolean  return true when logout success.
     */
    public function logout() {
        $newResult = vB_Api::instance('user')->fetchCurrentUserinfo();
        if ($newResult['userid']) {
            try {
                $result = vB_Api::instance('user')->logout($newResult['logouthash']);
                if (!MbqMain::$oMbqAppEnv->exttHasErrors($result)) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
    
    /**
     * init current user obj if login
     */
    public function initOCurMbqEtUser() {
        if (MbqMain::$oMbqAppEnv->currentUserInfo) {
            MbqMain::$oCurMbqEtUser = $this->initOMbqEtUser(MbqMain::$oMbqAppEnv->currentUserInfo['userid'], array('case' => 'byUserId'));
        }
    }
  
}

?>