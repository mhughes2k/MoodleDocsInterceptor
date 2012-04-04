<?php
$DB = null;
$VALID_VERSIONS = array(19,22,23);	//load this from db.
$LANGS = array("en"=>"English");
define('DEFAULT_ADD_VERSION', '');
define('DEFAULT_VERSION', 22);
define('DEFAULT_ADD_LANG', "en");
define('DEFAULT_LANG', "en");

$CFG->libdir = $CFG->dirroot.'/lib';

function query_db($query) {
	global $CFG ,$DB;
	if (is_null($DB)) {
		if(!$DB = mysql_connect('localhost','root',$CFG->dbpass)) {
			die(mysql_error());
		}	
		mysql_select_db('moodledocs');
	}
	
	
	$arr_results = array();
	//echo $query;
	if ($results = mysql_query($query,$DB)) {
		while(null != ($item = mysql_fetch_object($results)) ){
			//print_r($item);
			$arr_results[$item->id] = $item;
		}
	}
	return $arr_results;
	
}
function exec_db($query) {
	global $CFG ,$DB;
	if (is_null($DB)) {
		if(!$DB = mysql_connect('localhost','root',$CFG->dbpass)) {
			die(mysql_error());
		}	
		mysql_select_db('moodledocs');
	}
	
	
	$arr_results = array();
	//echo $query;
	if ($results = mysql_query($query,$DB)) {
		return true;
	}
	return false;
	
}


function validate_request_params($r) {
	global $VALID_VERSIONS;
	$new_r = new stdclass();
	$new_r->page = $r->page;
	$new_r->lang = DEFAULT_LANG;
	$new_r->version = DEFAULT_VERSION;	
	if (isset($r->version) && in_array($r->version,$VALID_VERSIONS)) {
		$new_r->version =$r->version;
	}
	return $new_r;
}
function get_mdoc_url($db_page) {
if ($db_page->language == '') {
	$db_page->language='en';
}
$mdoc_url = "http://docs.moodle.org/$db_page->version/$db_page->language/$db_page->page";
return $mdoc_url;
}
function find_override($page, $version = DEFAULT_VERSION, $lang = DEFAULT_LANG) {
	$query = "SELECT * FROM pages WHERE page='$page' AND ((language='$lang' OR language IS NULL) OR (version=$version OR version IS NULL))" ;
	$items = query_db($query);
	if (count($items) == 0) {
		//redirect to moodle docs
		$mdoc_url = "http://docs.moodle.org/$version/$lang/$page";
		//pre-download the destination page and check for it's existence?
		return $mdoc_url;
		//echo "their page <a href='$mdoc_url'>{$mdoc_url}</a>";
	} else {
		//redirect to the specified page
		//echo 'our page';
		$item = array_pop(array_values($items));
		return $item->url;
	}
}

function print_page_table_row($p,$return = false) {
	$out = '';
	$url =get_mdoc_url($p);
	$out.= "<tr><td>";
//	$out.=   "<a href='$url'>$url</a>";
	$out.= $p->page;
	$out.=  "</td><td><a href='$p->url'>$p->url</a></td><td>{$p->version}</td><td>{$p->language}</td><td></tr>";
	if ($return) {
		return $out;
	}
	else {
		echo $out ;
	}
	
}