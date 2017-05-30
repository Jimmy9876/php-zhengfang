<?php
//header("Content-type: text/html; charset=gb2312");
session_start();
$id=session_id();
$_SESSION['id']=$id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
/**
 * @author Jimmy
 * Create 2015/05/04
 * CQUTRobot 模拟登陆教务系统及抓取页面内容
 **/

$cookie = dirname(__FILE__) . '/cookie/'.$_SESSION['id'].'.txt'; //cookie路径
$verify_code_url = "http://202.119.225.34/CheckCode.aspx"; //验证码地址
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $verify_code_url);
curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);  //保存cookie
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$img = curl_exec($curl);  //执行curl
curl_close($curl);
$fp = fopen("verifyCode.jpg","w");  //文件名
fwrite($fp,$img);  //写入文件
fclose($fp);
?>
<form name="form1" method="post" action="kebiao.php" >
    用户名:<input type="text" name="xh" /><!--普通文本框-->
    密码:<input type="password" name="pw" /><!--密码框-->
    验证码:<input type="text" name="code" /><img src="verifyCode.jpg">
    <input type="submit" value="提交信息" />
</form>

</body>
</html>