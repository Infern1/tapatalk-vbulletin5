<?php

defined('MBQ_IN_IT') or exit;

/**
 * get_user_info action
 * 
 * @since  2012-8-16
 * @author Wu ZeTao <578014287@qq.com>
 */
Abstract Class MbqBaseActAvatar extends MbqBaseAct {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * action implement
     */
    protected function actionImplement() {
        if (!MbqMain::$oMbqConfig->moduleIsEnable('user')) {
            MbqError::alert('', "Not support module user!", '', MBQ_ERR_NOT_SUPPORT);
        }
        $userName = $_GET['username'];
        $userId = $_GET['user_id'];
        $oMbqRdEtUser = MbqMain::$oClk->newObj('MbqRdEtUser');
        if ($userId) {
            $oMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($userId, array('case' => 'byUserId'));
        } else {
            $oMbqEtUser = $oMbqRdEtUser->initOMbqEtUser($userName, array('case' => 'byLoginName'));
        }
        if ($oMbqEtUser) {
            $userInfo = $oMbqRdEtUser->returnApiDataUser($oMbqEtUser);
            $url = '';
            if(isset($userInfo['icon_url']))
            {
                $url = $userInfo['icon_url'];
            }
            header("Location: $url", 0, 303);
        } else {
            MbqError::alert('', "User not found!", '', MBQ_ERR_APP);
        }
    }
  
}

?>