<?php

include("conn.php");
//设置页面编码
header("Content-type:text/html;charset=UTF-8");
$username = trim($_POST["username"]);
$conn = mysql_open();
$sql = "select * from admin where name='$username'";
$query = mysql_query($sql);
$rst = mysql_fetch_object($query);
mysql_close($conn);
if ($rst == false)
{
    echo 'false';
}
else
{
    echo 'true';
}
