<?php

/*
 * curl实战
 * description:在网络上下载一个网页并把内容中的'百度'替换为'屌丝'后输出
 *  
 */
/* */
$curlObj = curl_init(); //初始化
curl_setopt($curlObj, CURLOPT_URL, 'http://www.baidu.com'); //通过设置参数的方式设置访问网页的url地址
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true); //设置参数,执行后不直接打印出来
$output = curl_exec($curlObj); //执行并将curl执行后的结果存在变量中
curl_close($curlObj); //关闭curl
//将curl爬到的数据写入本地文件中
$handle = @fopen('curl.html', 'r+'); //文件必须存在
$filename = fwrite($handle, $output);
$file = fread($handle, filesize('curl.html'));
//  echo str_replace('百度', '屌丝', $output);




/*
 * curl实战
 * description:通过调用webservice查询北京当天的天气
 * 
 */
/*
  $data = 'theCityName=合肥';
  $curlObj = curl_init();
  curl_setopt($curlObj, CURLOPT_URL, 'http://www.webxml.com.cn/WebServices/WeatherWebService.asmx/getWeatherbyCityName');
  curl_setopt($curlObj, CURLOPT_HEADER, 0);
  curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curlObj, CURLOPT_POST, 1);
  curl_setopt($curlObj, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curlObj, CURLOPT_HTTPHEADER, array(
  'application/x-www-form-urlencoded;charset=utf-8',
  'content-length:' . strlen($data)
  )
  );
  curl_setopt($curlObj, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
  $return = curl_exec($curlObj);
  if (!curl_errno($curlObj))
  {
  //    $info = curl_getinfo($curlObj);
  //    print_r($info);
  echo $return;
  //    file_put_contents('1.txt', $return);
  }
  else
  {
  echo 'Curl error:' . curl_error($curlObj);
  }
  curl_close($curlObj);
 */



/*
 * curl实战
 * description:登录慕课网并下载个人空间页面
 * 
 */

/**
 * 

$data = 'username=demo_peter@126.com&password=123qwe&remember=1';
$curlObj = curl_init(); //初始化curl
curl_setopt($curlObj, CURLOPT_URL, 'http://www.imooc.com/user/login'); //设置访问的url
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //执行之后不要打印出来
//cookie相关设置,
date_default_timezone_set('PRC'); //使用cookie时,必须先设置时区
curl_setopt($curlObj, CURLOPT_COOKIESESSION, 1);
curl_setopt($curlObj, CURLOPT_COOKIEFILE, 'cookiefile');
curl_setopt($curlObj, CURLOPT_COOKIEJAR, 'cookiefile');
curl_setopt($curlObj, CURLOPT_COOKIE, session_name() . '=' . session_id());
curl_setopt($curlObj, CURLOPT_HEADER, 0); //设置curl不要打印header头部信息
curl_setopt($curlObj, CURLOPT_FOLLOWLOCATION, 1); //这样能让curl支持页面链接跳转

curl_setopt($curlObj, CURLOPT_POST, 1);
curl_setopt($curlObj, CURLOPT_POSTFIELDS, $data);
curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded;charset=utf-8', 'content-length:' . strlen($data)));

curl_exec($curlObj); //执行
curl_setopt($curlObj, CURLOPT_URL, 'http://www.imooc.com/space/index');
curl_setopt($curlObj, CURLOPT_POST, 0);
curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('content-type:text/xml'));
$output = curl_exec($curlObj); //执行
curl_close($curlObj); //关闭curl
echo $output;
 */