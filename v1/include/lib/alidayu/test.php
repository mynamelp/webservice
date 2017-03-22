<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    //$httpdns = new HttpdnsGetRequest;
    //$client = new ClusterTopClient("4272","0ebbcccfee18d7ad1aebc5b135ffa906");
    //$client->gatewayUrl = "http://api.daily.taobao.net/router/rest";
    //var_dump($client->execute($httpdns,"6100e23657fb0b2d0c78568e55a3031134be9a3a5d4b3a365753805"));
	$c = new TopClient;
	$c->appkey = $appkey;
	$c->secretKey = $secret;
	$req = new AlibabaAliqinFcSmsNumSendRequest;
	$req->setExtend("123456");
	$req->setSmsType("normal");
	$req->setSmsFreeSignName("阿里大于");
	$req->setSmsParam("{\"code\":\"1234\",\"product\":\"alidayu\"}");
	$req->setRecNum("13236911650");
	$req->setSmsTemplateCode("SMS_585014");
	$resp = $c->execute($req);
?>