<?php

defined('MBQ_IN_IT') or exit;

MbqMain::$oClk->includeClass('MbqBaseWrEtUser');

/**
 * user write class
 * 
 * @since  2012-9-28
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqWrEtUser extends MbqBaseWrEtUser {
    
    public function __construct() {
    }
  
    /**
     * m_ban_user
     */
    public function mBanUser($oMbqEtUser, $mode, $reasonText) {
        $bannedusergroups = vB_Api::instanceInternal('usergroup')->fetchBannedUsergroups();
        $banusergroupid = key($bannedusergroups);
	$user = vB_Api::instance('user')->banUsers(array($oMbqEtUser->userId->oriValue), $banusergroupid, $period = 'PERMANENT', $reasonText);
	if ($user === null || isset($user['errors'])) {
            MbqError::alert('', "User bans faild!", '', MBQ_ERR_APP);
	}
        if($mode == 2){
            $search_api = vB_Api::instanceInternal('search');
            $search_json = json_encode(array(
                    'authorid'		=> (array)$oMbqEtUser->userId->oriValue,
                    'ignore_cache'	=> true,
                    'exclude_type' => array(
                            'vBForum_Channel',
                            'vBForum_PrivateMessage',
                            'vBForum_Report',
                            'vBForum_Infraction',
                    )
            ));
            $result = $search_api->getSearchResult($search_json);
            $othernodeids = array();
            do {
                $othernodes = $search_api->getMoreNodes($result['resultId']);
                if ($othernodeids == array_values($othernodes['nodeIds'])) {
                    break;
                }
                $othernodeids = array_values($othernodes['nodeIds']);
                if (!empty($othernodeids)) {
                    vB_Api::instance('node')->deleteNodes($othernodeids, true, $reasonText, true);
                }
            } while (!empty($othernodeids));
        }
    }
    
     /**
     * m_mark_as_spam
     */
    public function mMarkAsSpam($oMbqEtUser) {
        $userinfo = $oMbqEtUser->mbqBind['userRecord'];
        $stopForumSpam = vB_StopForumSpam::instance();
        $stopForumSpam->markAsSpam($userinfo['username'], $userinfo['ipaddress'], '' , $userinfo['email']);
    }
    
}

?>