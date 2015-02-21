<?php
if($_SERVER['APPLICATION'] == "dev")
{
  $mysql_user="dev_netlogbook";
  $mysql_pass="Qrnpa4fyPztLYcL8";
  $mysql_host="localhost";
  $mysql_db="dev_netlogbook";
}
else
{
  $mysql_user="netlogbook";
  $mysql_pass="blafaselpeng";
  $mysql_host="localhost";
  $mysql_db="netlogbook";
}
$qrzcom_cachetime='864000'; // 1 Week
?>
