<?php
//echo $_POST["note"];
$sqlite=new SQLite3('wsn_db.sqlite');

/*$sqlite->query("Create Table 'Notes' (
'note' TEXT(10000) not NULL,
'date' TIMESTAMP)
");*/
//$sqlite->query("Insert into Notes ('note','date') VALUES ('Hello!',datetime('now'))");
$values=$sqlite->query("Select * from Notes");
$row=$values->fetchArray(SQLITE3_ASSOC);
//var_dump($row);
$sqlite->close();
header("Location: http://127.0.0.2/index.html");
die();
?>