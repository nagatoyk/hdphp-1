<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>欢迎使用HDPHP</title>
	<link rel="stylesheet" href="resource/hdjs/css/bootstrap.min.css">
	<script src="resource/hdjs/js/angular.min.js"></script>

	<link href="resource/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="resource/hdjs/css/font-awesome.min.css" rel="stylesheet">
	<script src="resource/hdjs/js/jquery.min.js"></script>
	<script src="resource/hdjs/app/util.js"></script>
	<script src="resource/hdjs/require.js"></script>
	<script src="resource/hdjs/app/config.js"></script>
</head>
<body style="background: #f3f3f3;">
<h1 class="text-center text-info" style="margin-top:200px;font-size:80px;">HDPHP 為效率而生</h1>
<h3 class="text-muted text-center">服务化/组件化/模块化的未来框架产品</h3>

<input type="text" value="2015/11/11 至 2015/12/20" id="daterangepicker" style="margin:10px;" size="200">
<script>
	require(['util'], function (util) {
		util.daterangepicker({
			element:'#daterangepicker',//点击元素
			options:{
				timePicker: true,
				timePickerIncrement: 30,
				"locale": {
					"format": "YYYY/MM/DD h:mm",//YYYY/MM/DD H:m
					"separator": " 至 ",
					"applyLabel": "确定",
					"cancelLabel": "取消",
					"fromLabel": "From",
					"daysOfWeek": [
						"日", "一", "二", "三", "四", "五", "六"
					],
					"monthNames": [
						"一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"
					],
					"firstDay": 0
				}
			},//选项请参考官网
			callback:function (start, end, label) {
				str = start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD');
				$('#daterangepicker').val(str);
			}
		});
	});
</script>
</body>
</html>