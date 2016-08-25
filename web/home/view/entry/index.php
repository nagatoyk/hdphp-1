<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>欢迎使用HDPHP</title>
	<link rel="stylesheet" href="resource/hdjs/css/bootstrap.min.css">
	<script src="resource/hdjs/js/angular.min.js"></script>
</head>
<body style="background: #f3f3f3;">
<h1 class="text-center text-info" style="margin-top:200px;font-size:80px;">HDPHP 為效率而生</h1>
<h3 class="text-muted text-center">服务化/组件化/模块化的未来框架产品</h3>

<bootstrap/>
<foreach from="$data" value="$v">
	{{$v['name']}}
</foreach>
<hr>
<list from='$data' name='$d' row='2' start='1' empty='没有数据'>
	{{$d['name']}}
</list>
<hr>
<list from='$data' name='$n'>
	<if value="$hd['list']['n']['first']">
		{{$hd['list']['n']['index']}}: 这是第一条数据<br/>
		<elseif value="$hd['list']['n']['last']">
			{{$hd['list']['n']['index']}}: 最后一条记录<br/>
			<else>
				{{$hd['list']['n']['index']}}:{{$n['name']}}<br/>
	</if>
</list>
<hr/>
共有: {{$hd['list']['n']['total']}} 条记录数<br/>
是否为第 1 条记录: {{$hd['list']['n']['first']}} <br/>
是否为最后一条记录: {{$hd['list']['n']['last']}} <br/>
总记录数: {{$hd['list']['n']['total']}} <br/>
当前循环是第几条: {{$hd['list']['n']['index']}} <br/>
<hr>
<php>if(true){</php>
后盾网
<php>}</php>
</body>
</html>