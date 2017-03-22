<!DOCTYPE HTML>
<html>
<head>
<title>Test handlers by post</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../profiles/jquery-3.2.0.min.js"></script>
<script type'text/javascript'>
$.ajax({
    url:'feedbacks.handler.php',
    type:'POST', //GET
	async:true,    //或false,是否异步
	dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
    data:{
        content:'your app is good to use!', contact:'it@gmail.com', id:6
    },
    timeout:5000,    //超时时间
    beforeSend:function(xhr){
        console.log(xhr)
        console.log('发送前')
    },
    success:function(msg){
        console.log(msg)
        console.log(' success ')
		
    },
    error:function(msg){
        console.log('错误')
        console.log(msg)
    },
    complete:function(){
        console.log('结束')
    }
})

</script>
</head>
<body>
<span id='result'>111</span>
</body>
</html>