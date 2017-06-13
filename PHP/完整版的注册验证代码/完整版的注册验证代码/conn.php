<?php

function mysql_open()
{
    $mysql_servername = "localhost"; //服务器名称
    $mysql_username = "root";
    $mysql_password = "";
    $mysql_dbname = "news";

    $conn = mysql_connect($mysql_servername, $mysql_username, $mysql_password);
    mysql_query("set names UTF8"); //指定字符集为UTF8
    mysql_select_db($mysql_dbname, $conn);
    return $conn;
}
