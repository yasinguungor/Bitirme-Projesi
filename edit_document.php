<?php
include 'db_connect.php';
$sorgu = $baglan->query("SELECT * FROM documents where id = ".$_GET['id'])->fetch_array();
foreach($sorgu as $k => $v){
	if($k == 'title')
		$k = 'ftitle';
	$$k = $v;
}
include 'new_document.php';
?>