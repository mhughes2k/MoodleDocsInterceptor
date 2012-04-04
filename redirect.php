<?php
ini_set('display_errors',1);
/**
 * Moodle docs redirector for Myplace
 */
 /*
 Used to intercept URLS like
 http://<docsite>/<version>/<language>/<page> 
 from a moodle site.
 Checks for a local override to the page
 
 if no overide then pass through to moodle docs
 http://docs.moodle.org/22/en/Main_page
  */
require_once('config.php');
require_once("{$CFG->libdir}/lib.php");
//print_r($_REQUEST);	
$r = (object)$_REQUEST;

$r = validate_request_params($r);
$url = find_override($r->page,$r->version,$r->lang);
//fetch the moodle docs page
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_HEADER,1);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_NOBODY,1);
$result = curl_exec($ch);
curl_close($ch);
//var_dump($result);
if (stripos($result,"404 not")) {
	echo "<html><head></head><body>";
	echo "<p>Page doesn't exist on moodle docs</p>";
	echo "<P>You have two options:</p><ul>";
	echo "<li><a href='{$CFG->wwwroot}/admin/index.php?page=$r->page&version=$r->version&lang=$r->lang'>Override this page locally</a></li>";
	echo "<li><a href='$url'>Write this page on Moodle docs</a></li>";	
	echo "</ul>";
	echo "</body></html>";
}
else {
//	header("Location:$url:");
echo 'page ofund';
}