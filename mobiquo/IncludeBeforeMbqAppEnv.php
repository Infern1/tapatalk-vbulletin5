<?php

defined('MBQ_IN_IT') or exit;
/**
 * This file is not needed by default!
 * Run this first before call MbqMain::initAppEnv() when you need!
 * 
 * @since  2012-11-19
 * @author Wu ZeTao <578014287@qq.com>
 */
/* Please write any codes you need in the following area before call MbqMain::initAppEnv()! */

@ ob_start();
/*-------- define global vars begin --------*/
//global $VB_API_REQUESTS,$bootstrap,$actiontemplates,$globaltemplates,$specialtemplates,$permissions,$phrasegroups,$show,$vbulletin,$vbphrase,$imodcache,$accesscache,$postinfo,$userinfo,$vb5_config,$reputation,$colorPickerWidth,$colorPickerType,$numcolors,$ifaqcache,$faqcache,$faqjumpbits,$faqparent,$bgcounter,$parentlist,$parentoptions,$parents,$npermscache,$colspan,$img_per_row,$cats,$DEVDEBUG,$settingphrase,$settingscache,$grouptitlecache,$uniquetables,$tableadded,$stop_file,$stop_args,$vphrase,$vboptions,$session,$stylestuff,$masterset,$only,$SHOWTEMPLATE,$db,$groupcache,$usergroupleaders,$promotions,$usercache,$_HIDDENFIELDS,$helpcache,$level,$uploadform,$iusergroupcache,$_NAVPREFS,$fpermcache,$phrase,$typeoptions,$_NAV,$options,$groupid,$event,$months,$monthdays,$daynames,$template_cache,$LINKEXTRA,$info,$templateid,$replacement_info,$stylevars,$stylevar_info,$css,$readonly,$color,$calendarinfo,$post,$html_allowed,$style,$vbcollapse,$pmbox,$gobutton,$spacer_open,$spacer_close,$notices,$notifications_menubits,$notifications_total,$pagenumber,$headinclude,$headinclude_bottom,$header,$footer,$threadinfo,$foruminfo,$mobile_browser,$pollinfo,$postid,$threadid,$forumid,$pollid,$VB_API_WHITELIST,$perpage,$bgclass,$altbgclass,$onload,$itemid,$selected_tab,$messagearea,$vBeditTemplate,$messageid,$threadcache,$postcache,$templateassoc,$$cache_name,$$varname,$pagetitle,$forumjump,$timezone,$ad_location,$daysprune,$timediff,$datenow,$timenow,$copyrightyear,$forumpermissioncache,$calendarcache,$permscache,$querytime,$ad_cache,$forumrules,$timerange,$holiday,$days,$day,$month,$year,$titlecolor,$date1,$date2,$time1,$time2,$recurcriteria,$allday,$eventdate,$eventcache,$birthdaycache,$cmodcache,$calmod,$serveroffset,$doublemonth,$doublemonth1,$doublemonth2,$today,$monthselected,$_CALENDARHOLIDAYS,$eastercache,$period,$_CALENDAROPTIONS,$smiliebox,$disablesmiliesoption,$checked,$istyles,$fpermscache,$og_array,$bloginfo,$faqbits,$faqlinks,$newthreads,$dotthreads,$ignore,$mod,$lastpostarray,$counters,$inforum,$bb_view_cache,$lastpostinfo,$folderid,$folderselect,$foldernames,$messagecounters,$subscribecounters,$folder,$allforumcache,$bb_cache_forum_view,$replacevar,$findvar,$selectedicon,$forumperms,$rate,$previewpost,$message,$postusername,$phrasequery,$display,$replyscore,$viewscore,$ratescore,$searchtype,$searchforumids,$postparent,$postarray,$ipostarray,$curpostid,$parent_postids,$morereplies,$threadedmode,$postattach,$links,$hybridposts,$postorder,$currentdepth,$cache_postids,$curpostidkey,$optionselected,$jumpforumid,$jumpforumtitle,$jumpforumbits,$curforumid,$navclass,$cpnav,$subscriptioncache,$customfields,$bgclass1,$tempclass,$p_two_linebreak,$cpnavjs,$fr_version,$fr_platform,$frcl_platform,$stylevar,$lang,$charset,$mybb,$settings,$nuke_quotes,$images,$contenttype,$web_colors,$VB_API_PARAMS_TO_VERIFY,$strikes,$sessionhash,$navbar;
//global $VB_API_REQUESTS,$bootstrap,$actiontemplates,$globaltemplates,$specialtemplates,$permissions,$phrasegroups,$show,$vbulletin,$vbphrase,$imodcache,$accesscache,$postinfo,$userinfo,$vb5_config,$reputation,$colorPickerWidth,$colorPickerType,$numcolors,$ifaqcache,$faqcache,$faqjumpbits,$faqparent,$bgcounter,$parentlist,$parentoptions,$parents,$npermscache,$colspan,$img_per_row,$cats,$DEVDEBUG,$settingphrase,$settingscache,$grouptitlecache,$uniquetables,$tableadded,$stop_file,$stop_args,$vphrase,$vboptions,$session,$stylestuff,$masterset,$only,$SHOWTEMPLATE,$db,$groupcache,$usergroupleaders,$promotions,$usercache,$_HIDDENFIELDS,$helpcache,$level,$uploadform,$iusergroupcache,$_NAVPREFS,$fpermcache,$phrase,$typeoptions,$_NAV,$options,$groupid,$event,$months,$monthdays,$daynames,$template_cache,$LINKEXTRA,$info,$templateid,$replacement_info,$stylevars,$stylevar_info,$css,$readonly,$color,$calendarinfo,$post,$html_allowed,$style,$vbcollapse,$pmbox,$gobutton,$spacer_open,$spacer_close,$notices,$notifications_menubits,$notifications_total,$pagenumber,$headinclude,$headinclude_bottom,$header,$footer,$threadinfo,$foruminfo,$mobile_browser,$pollinfo,$postid,$threadid,$forumid,$pollid,$VB_API_WHITELIST,$perpage,$bgclass,$altbgclass,$onload,$itemid,$selected_tab,$messagearea,$vBeditTemplate,$messageid,$threadcache,$postcache,$templateassoc,$pagetitle,$forumjump,$timezone,$ad_location,$daysprune,$timediff,$datenow,$timenow,$copyrightyear,$forumpermissioncache,$calendarcache,$permscache,$querytime,$ad_cache,$forumrules,$timerange,$holiday,$days,$day,$month,$year,$titlecolor,$date1,$date2,$time1,$time2,$recurcriteria,$allday,$eventdate,$eventcache,$birthdaycache,$cmodcache,$calmod,$serveroffset,$doublemonth,$doublemonth1,$doublemonth2,$today,$monthselected,$_CALENDARHOLIDAYS,$eastercache,$period,$_CALENDAROPTIONS,$smiliebox,$disablesmiliesoption,$checked,$istyles,$fpermscache,$og_array,$bloginfo,$faqbits,$faqlinks,$newthreads,$dotthreads,$ignore,$mod,$lastpostarray,$counters,$inforum,$bb_view_cache,$lastpostinfo,$folderid,$folderselect,$foldernames,$messagecounters,$subscribecounters,$folder,$allforumcache,$bb_cache_forum_view,$replacevar,$findvar,$selectedicon,$forumperms,$rate,$previewpost,$message,$postusername,$phrasequery,$display,$replyscore,$viewscore,$ratescore,$searchtype,$searchforumids,$postparent,$postarray,$ipostarray,$curpostid,$parent_postids,$morereplies,$threadedmode,$postattach,$links,$hybridposts,$postorder,$currentdepth,$cache_postids,$curpostidkey,$optionselected,$jumpforumid,$jumpforumtitle,$jumpforumbits,$curforumid,$navclass,$cpnav,$subscriptioncache,$customfields,$bgclass1,$tempclass,$p_two_linebreak,$cpnavjs,$fr_version,$fr_platform,$frcl_platform,$stylevar,$lang,$charset,$mybb,$settings,$nuke_quotes,$images,$contenttype,$web_colors,$VB_API_PARAMS_TO_VERIFY,$strikes,$sessionhash,$navbar;
/*-------- define global vars end --------*/

/*-------- modified from index.php begin --------*/
/*
if (!defined('VB_ENTRY'))
{
	define('VB_ENTRY', 1);
}

// Check for cached image calls to filedata/fetch?
if (isset($_REQUEST['routestring'])
		AND
	$_REQUEST['routestring'] == 'filedata/fetch'
		AND
	(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) AND !empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])
		OR
	(isset($_SERVER['HTTP_IF_NONE_MATCH']) AND !empty($_SERVER['HTTP_IF_NONE_MATCH']))))
{
	// Don't check modify date as URLs contain unique items to nullify caching
	$sapi_name = php_sapi_name();
	if ($sapi_name == 'cgi' OR $sapi_name == 'cgi-fcgi')
	{
		header('Status: 304 Not Modified');
	}
	else
	{
		header('HTTP/1.1 304 Not Modified');
	}
	exit;
}

require_once('includes/vb5/autoloader.php');
//vB5_Autoloader::register(dirname(__FILE__));
vB5_Autoloader::register(dirname(dirname(__FILE__)));

$app = vB5_Frontend_Application::init('config.php');

//todo, move this back so we can catch notices in the startup code. For now, we can set the value in the php.ini
//file to catch these situations.
// We report all errors here because we have to make Application Notice free
error_reporting(E_ALL | E_STRICT);

$config = vB5_Config::instance();
if (!$config->report_all_php_errors) {
	// Note that E_STRICT became part of E_ALL in PHP 5.4
	error_reporting(E_ALL & ~(E_NOTICE | E_STRICT));
}

$routing = $app->getRouter();
$controller = $routing->getController();
$method = $routing->getAction();
$template = $routing->getTemplate();

switch ($controller) {
	case 'activity':
		$class = 'Activity';
		break;
	case 'admin':
		$class = 'Admin';
		break;
	case 'ajax':
		$class = 'Ajax';
		break;
	case 'auth':
		$class = 'Auth';
		break;
	case 'channel':
		$class = 'Channel';
		break;
	case 'conversation':
		$class = 'Conversation';
		break;
	case 'createcontent':
		$class = 'CreateContent';
		break;
	case 'poll':
		$class = 'Poll';
		break;
	case 'page':
		$class = 'Page';
		break;
	case 'test':
		$class = 'Test';
		break;
	case 'uploader':
		$class = 'Uploader';
		break;
	case 'search':
		$class = 'Search';
		break;
	case 'filedata':
		$class = 'Filedata';
		break;
	case 'registration':
		$class = 'Registration';
		break;
	case 'profile':
		$class = 'Profile';
		break;
	case 'style':
		$class = 'Style';
		break;
	case 'video':
		$class = 'Video';
		break;
	case 'link':
		$class = 'Link';
		break;
	case 'relay':
		$class = 'Relay';
		break;
	case 'privatemessage':
		$class = 'PrivateMessage';
		break;
	case 'report':
		$class = 'Report';
		break;
	case 'hv':
		$class = 'Hv';
		break;
	default:
		$class = 'Main';
		break;
}


$class = 'vB5_Frontend_Controller_' . $class;

if (!class_exists($class))
{
	// @todo - this needs a proper error message
	die("Couldn't find controller file for $class");
}

vB5_Frontend_ExplainQueries::initialize();
$c = new $class($template);
*/

/*
if ($class == 'vB5_Frontend_Controller_Main')
{
	call_user_func_array(array(&$c, 'index'), array($controller, $method, $routing->getArguments()));
}
else
{
	call_user_func_array(array(&$c, $method), $routing->getArguments());
}

vB5_Frontend_ExplainQueries::finish();
*/
/*-------- modified from index.php end --------*/

//new code!!!
define('CSRF_PROTECTION', false);
if (!defined('VB_ENTRY')) { //for vb 5.0.4
	define('VB_ENTRY', 1);
}

require_once('includes/vb5/autoloader.php');
vB5_Autoloader::register(getcwd());
vB5_Frontend_Application::init('config.php');
//vB5_Frontend_ApplicationLight::init('config.php');

@ ob_end_clean();

?>