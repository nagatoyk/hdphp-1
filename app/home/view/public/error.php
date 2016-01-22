<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>温馨提示</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{__ROOT__}}/hdphp/view/css.css"/>
</head>
<body>
<div class="wrap">
    <div class="title">
        温馨提示
    </div>
    <div class="content">
        <div class="icon"></div>
        <div class="message">
            <p>
                {{$message}}
            </p>
            <a href="javascript:{{$url}}" class="btn btn-default btn-sm">
                返回
            </a>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.setTimeout("{{$url}}",{{$time}}*1000);
</script>
</body>
</html>