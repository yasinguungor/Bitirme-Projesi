<?php
include 'db_connect.php';
$sorgu = $baglan->query("SELECT * FROM users where id = ".$_GET['id'])->fetch_array();
foreach($sorgu as $k => $v){
	$$k = $v;
}
include 'new_user.php';
?>