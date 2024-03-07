<?php
include_once '../interface/common/mssql.php';#自定义数据库连接模块

$fileURI = __FILE__;
$fullName = basename($fileURI);
$mainName = basename($fileURI, ".php");
$db= explode('_',$mainName)[0];
$jump_url="/general/order/$fullName";#本脚本地址
$order_type="p_order";#订单类型

$root_query="
SELECT 
P.BILLID '订单id ',
P.BILLCODE '订单编号 ',
P.BILLDATE '开单日期 ',
(select name from vendor where vendorid=P.vendorid) '供应商名称 ',
(select name from rep where repid=P.repid) '业务员 ',
(select name from store where storeid=P.storeid) '仓库 ',
P.BILLAMT '总数 ',
P.REMARK '备注 ',
P.RCVDATE '收货日期 ',
(select code from goods where goodsid=PD.GOODSID) 货品编号,
(select name from goods where goodsid=PD.GOODSID) 货品名称,
PD.PRICE '价格 ',
PD.TAXPRICE '含税价格 ',
PD.EXEQTY 执行数量
FROM P_ORDER P LEFT JOIN P_ORDERD PD on P.BILLID=PD.BILLID
WHERE P.BILLCODE =";
$input_tips_url=$db."_order_billcode.php";#文件名里的DB2023对应的是数据库名称
$BILLCODE=$_GET['BILLCODE'];#html转入订单号
$res=get_by_billcode("'$BILLCODE'",$db);#执行sql语句，保存到$res里

require_once '../common/html/order.html';#自定义的展示页面

function get_by_billcode($BILLCODE,$db){

// 加一个空格的属于不展示的内容
global $root_query;

$query=$root_query.$BILLCODE;
// 获取数据

$res=mssqlScript_with_db($query,$db);
return $res;

}