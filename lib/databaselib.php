<?php
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