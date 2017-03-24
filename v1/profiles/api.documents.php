Collections : Users
URL: [host]/api/v1/handler/users.handler.php
Method: GET 
Params:
	{
		"controller": "",
		"filters":{	
			"username":"",
			"_id":""
		}, 
		"datas":{}, 
		"token":""
	}
return:
	{	"status": int,
		"datas":{
			
		}, 
		"errmsg":"", 
		"links":{	
					"self":{"href":""}, 
					"profile":{"href":""}
		}, 
		"msg":"", 
		"responsetime": int
	}

	
Collections : Users
URL: [host]/api/v1/handler/users.handler.php
Method: PATCH 
Params: 
	{
		"controller": "",
		"filters":{
			"_id":"58c8bbf23e7e52ad5831259f"
		}, 
		"datas":{
			"nick":"Boby" //对于内嵌属性的修改请用 {"info.nick": "Bob"}
		}, 
		"token":"111111"
	}
return:
	{	"status": int,
		"datas":{
			
		}, 
		"errmsg":"", 
		"links":{	
					"self":{"href":""}, 
					"profile":{"href":""}
		}, 
		"msg":"", 
		"responsetime": int
	}



Collections : Users
URL: [host]/api/v1/handler/users.handler.php
Method: POST 
Params:
Params: 
	{
		"controller": "setpwd",
		"filters":{},
		"datas":{
			"username": "13336111999",
			"password":"",
			"client":"",
			"code":651898
		}, 
		"token": ""
	}
return:
{	"status": int,
		"datas":{
			
		}, 
		"errmsg":"", 
		"links":{	
					"self":{"href":""}, 
					"profile":{"href":""}
		}, 
		"msg":"", 
		"responsetime": int
	}
	
Collections : Users
URL: [host]/api/v1/handler/users.handler.php
Method: POST 
Params:
Params: 
	{
		"controller": "pwdlogin",
		"filters":{},
		"datas":{
			"username": "13336111999",
			"password":"",
			"client":""
		}, 
		"token": ""
	}
return:
{	"status": int,
		"datas":{
			
		}, 
		"errmsg":"", 
		"links":{	
					"self":{"href":""}, 
					"profile":{"href":""}
		}, 
		"msg":"", 
		"responsetime": int
	}
	
	
Collections : Users
URL: [host]/api/v1/handler/users.handler.php
Method: POST 
Params:
Params: 
	{
		"controller": "tellogin",
		"filters":{},
		"datas":{
			"username": "13336111999",
			"code":379986,
			"client":""
		}, 
		"token": ""
	}
return:
{	"status": int,
		"datas":{
			
		}, 
		"errmsg":"", 
		"links":{	
					"self":{"href":""}, 
					"profile":{"href":""}
		}, 
		"msg":"", 
		"responsetime": int
	}	
	
Collections : Verifications
URL: [host]/api/v1/handler/verifications.handler.php
Method: GET 
Params:
	{
		"controller": "getcode",
		"filters":{	
			"username":"",
			"medium":"tel" //tel or mail
		}, 
		"datas":{}, 
		"token":""
	}
return:
	{	"status": int,
		"datas":{
			
		}, 
		"errmsg":"", 
		"links":{	
					"self":{"href":""}, 
					"profile":{"href":""}
		}, 
		"msg":"", 
		"responsetime": int
	}