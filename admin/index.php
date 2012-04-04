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
require_once('../config.php');
require_once("{$CFG->libdir}/lib.php");

if (isset($_POST['submit'])) {
	//probably handling post back
	$r = new stdclass();
	$r->page = mysql_real_escape_string($_POST['page']);
	$r->lang = mysql_real_escape_string($_POST['lang']);
	$r->version = mysql_real_escape_string($_POST['version']);
	print_r($r);
	$r = validate_request_params($r);
	if (!isset($_POST['url']) | $_POST['url'] =='') {
		die('invalid url');
	}
	else {
		$r->url = mysql_real_escape_string($_POST['url']);
	}
	print_r($r);
	
	$ins = "INSERT INTO pages (page,version,language,url) VALUES('$r->page',$r->version,'$r->lang','$r->url')";
	echo $ins;
	if (!exec_db($ins)) {
		echo 'failed to insert';
		echo mysql_error();
	}
	die();
}
$pages = query_db("SELECT * FROM pages");
$page='';
$add_v = '';
$add_lang = DEFAULT_ADD_LANG;
if (isset($_GET['page']) && $_GET['page'] != '') {
	$page = $_GET['page'];
}
if (isset($_GET['version']) && $_GET['version'] != '' && in_array($_GET['version'],$VALID_VERSIONS)) {
	$add_v= $_GET['version'];
}
if (isset($_GET['lang']) && $_GET['lang'] != '') {
	$add_lang = $_GET['lang'];
}

?>
<html>
<head>
</head>
<body>
<h1>Moodle Docs Page Mapper</h1>
<form action="index.php" method="post">
<h2>Override a Moodle Docs link</h2>
<label for="page">Page</label>
<input type="text" name="page" value='<?php echo $page?>' style='width:400px;'/> <br />
<label for="version">Version</label>
<select name="version">
<option value="">All Versions</option>
<?Php
foreach($VALID_VERSIONS as $v) {
	echo "<option value='$v'";
	if ($v == DEFAULT_ADD_VERSION | $v == $add_v) {
		echo " selected";
	}
	echo ">$v</option>";
}
?>
</select>
<label for="lang">Language</label>
<select name="lang">
<?Php
foreach($LANGS as $key=>$lang) {
	echo "<option value='$key'";
	if ($key == DEFAULT_ADD_LANG | $key == $add_l) {
		echo " selected";
	}
	echo ">$lang</option>";
}
?>
</select>
<br />
<label for="page">Destination</label><input type="text" name="url" value='' style='width:400px;'/> <br />
<input type='submit' name='submit' value='Add Override' />
</form>
<div>
<h2>Currently Overridden Moodle Docs Links</2h>
<table>
<tr><th>Page</th><th>Redirected To</th><th>Version</th><th>Language</th><th></tr>

<?php foreach($pages as $p) {
	print_page_table_row($p);
}?>
</table>
</div>
</body>
</html>