<?php
/**
 * Created by PhpStorm.
 * User: Apple
 * Date: 2019/5/17
 * Time: PM9:08
 */
session_start();
header("Content-type: text/html; charset=gb2312");  //视学校而定，博主学校是gbk编码，php也采用的gbk编码方式

//将表格转换成数组函数
function get_td_array($table) {
    $table = preg_replace("'<table[^>]*?>'si","",$table);
    $table = preg_replace("'<tr[^>]*?>'si","",$table);
    $table = preg_replace("'<td[^>]*?>'si","",$table);
    $table = str_replace("</tr>","{tr}",$table);
    $table = str_replace("</td>","{td}",$table);
    //去掉 HTML 标记
    $table = preg_replace("'<[/!]*?[^<>]*?>'si","",$table);
    //去掉空白字符
    $table = preg_replace("'([rn])[s]+'","",$table);
    $table = preg_replace('/&nbsp;/',"",$table);
    $table = str_replace(" ","",$table);
    $table = str_replace(" ","",$table);
    $table = explode('{tr}', $table);
    array_pop($table);
    foreach ($table as $key=>$tr) {
        $td = explode('{td}', $tr);
        array_pop($td);
        $td_array[] = $td;
    }
    return $td_array;
}

function login_post($url, $cookie, $post)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //不自动输出数据，要echo才行
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //重要，抓取跳转后数据
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_REFERER, 'http://202.119.225.34/');  //重要，302跳转需要referer，可以在Request Headers找到
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);  //post提交数据
    $result = curl_exec($ch);
//    $content = curl_getinfo($ch);
//    echo json_encode($content);
    curl_close($ch);
    return $result;
}

$_SESSION['xh'] = $_POST['xh'];
$xh = $_POST['xh'];
$pw = $_POST['pw'];
$code = $_POST['code'];
$cookie = dirname(__FILE__) . '/cookie/' . $_SESSION['id'] . '.txt';
$url = "http://202.119.225.34/default2.aspx";
$con1 = login_post($url, $cookie, '');
preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view);
$post = array(
    '__VIEWSTATE' => $view[1][0],
    'txtUserName' => $xh,
    'TextBox2' => $pw,
    'txtSecretCode' => $code,
    'RadioButtonList1' => '%D1%A7%C9%FA',
    'Button1' => '',
    'lbLanguage' => '',
    'hidPdrs' => '',
    'hidsc' => ''
);
$con2 = login_post($url, $cookie, http_build_query($post));
// print_r($con2);
// echo $con2;
preg_match_all('/<span id="xhxm">([^<>]+)/', $con2, $xm);
$xm[1][0] = substr($xm[1][0], 0, -4);
$url2 = "http://202.119.225.34/xskbcx.aspx?xh=" . $_SESSION['xh'] . "&xm=" . $xm[1][0];
$con3 = login_post($url2, $cookie, '');

//echo $con3;
print_r($con3);
//$content = get_td_array($con3);
//preg_match_all('/<table id="Table2" [^>]*>([\s\S]*?)<\/table>/',$content,$table);//用正则表达式将课表的表格取出
//print_r($content);
//echo $content[3][3];


// preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $viewstate, $vs);
// $state=$vs[1][0];
// $post=array(
//     '__EVENTTARGET'=>'',
//     '__EVENTARGUMENT'=>'',
//     '__VIEWSTATE'=>$state,
//     'hidLanguage'=>'',
//     'ddlXN'=>'2015-2016',
//     'ddlXQ'=>'2',
//     'ddl_kcxz'=>'',
//     'btn_xq'=>'%D1%A7%C6%DA%B3%C9%BC%A8'
// );
// $content=login_post($url2,$cookie,http_build_query($post));
// preg_match_all('/<td>([^<>]+)/', $content, $cj);
// $num=count($cj[1]);
// echo "<table>";
// echo "<tr>";
// for($n=0;$n<$num-11;$n++){
//     echo "<td>".$cj[1][$n+11]."</td>";
//     if(($n+1)%13==0&&$n!=0){
//         echo "</tr><tr>";
//     }
// }
// echo "</tr>";
// echo "</table>";

//    $_SESSION['xh']=$_POST['xh'];
//    $xh=$_POST['xh'];
//    $pw=$_POST['pw'];
//    $code= $_POST['code'];
//    $cookie = dirname(__FILE__) . '/cookie/'.$_SESSION['id'].'.txt';
//    $url="http://202.119.225.34/default2.aspx";  //教务处地址
//    $con1=login_post($url,$cookie,'');
//    preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $con1, $view); //获取__VIEWSTATE字段并存到$view数组中
//    $post=array(
//        '__VIEWSTATE'=>$view[1][0],
//        'txtUserName'=>$xh,
//        'TextBox2'=>$pw,
//        'txtSecretCode'=>$code,
//        'RadioButtonList1'=>'%D1%A7%C9%FA',  //“学生”的gbk编码
//        'Button1'=>'',
//        'lbLanguage'=>'',
//        'hidPdrs'=>'',
//        'hidsc'=>''
//    );
//    $con2=login_post($url,$cookie,http_build_query($post)); //将数组连接成字符串
//    echo $con2;
?>