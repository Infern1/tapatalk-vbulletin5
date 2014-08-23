<?php

defined('MBQ_IN_IT') or exit;

/**
 * common method class
 * 
 * @since  2012-7-2
 * @author Wu ZeTao <578014287@qq.com>
 */
Class MbqCm extends MbqBaseCm {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * transform timestamp to iso8601 format
     *
     * @param  Integer  $timeStamp
     * TODO:need to be made more general.
     */
    public function datetimeIso8601Encode($timeStamp) {
        //return date("c", $timeStamp);
        return date('Ymd\TH:i:s', $timeStamp).'+00:00';
    }
    
    /**
     * get attachment ids from content
     * here return filedataids from content
     *
     * @params  String  $content
     * @return  Array
     */
    public function getAttIdsFromContent($content) {
        if (MbqMain::$oMbqAppEnv->exttOptions['templateversion'] >= '5.0.2') {  //fixed compatible issue in vb5.0.2 when displaying attachments
            return array();
        } else {
            preg_match_all('/\[ATTACH=CONFIG\]n([^\[]*?)\[\/ATTACH\]/i', $content, $mat);
            if ($mat[1]) {
                return $mat[1];
            } else {
                return array();
            }
        }
    }
    
    /**
     * convert app attachment bbcode to vb5 native code
     *
     * @param  String  $content
     * @return  String
     */
    public function exttConvertAppAttBbcodeToNativeCode($content) {
        if (MbqMain::$oMbqAppEnv->exttOptions['templateversion'] >= '5.0.2') {  //fixed compatible issue in vb5.0.2 when saving attachments
            $content = preg_replace('/\[ATTACH\]([^\[]*?)\[\/ATTACH\]/i', '[IMG]'.MbqMain::$oMbqAppEnv->rootUrl.'/filedata/fetch?filedataid=$1[/IMG]', $content);
        } else {
            $content = preg_replace('/\[ATTACH\]([^\[]*?)\[\/ATTACH\]/i', '[ATTACH=CONFIG]n$1[/ATTACH]', $content);
        }
        $content = preg_replace('/\[youtube\](.*?)\[\/youtube\]/i', '[video]$1[/video]', $content);
        $content = preg_replace('/\[url\](.*?)\[\/url\]/i', '[video]$1[/video]', $content);
        $content = preg_replace('/\[vimeo\](.*?)\[\/vimeo\]/i', '[video]$1[/video]', $content);
        return $content;
    }
    
}

?>