<?php 
require_once('config.php');
require_once("{$CFG->libdir}/lib.php");

$pages = query_db("SELECT * FROM pages");
?>
<html>
<head>
</head>
<body>
<h1>Moodle Docs Page Mapper</h1>
<div>
<table>
<tr><th>Page</th><th>Redirected To</th><th>Version</th><th>Language</th><th></tr>

<?php foreach($pages as $p) {
	print_page_table_row($p);
}?>
</table>
</div>
</body>
</html>