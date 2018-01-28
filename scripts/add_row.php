<?php
$document_root = $_SERVER['DOCUMENT_ROOT'];
require($document_root.'/../config/dbopen.php');
$table = $_GET['table'];
mysql_query("insert $table set title=''");
echo mysql_insert_id();
?>