<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>欢迎使用HDPHP框架</title>
    <style type="text/css">
        body{
            background: #efefef;
        }
        div#main {
            padding: 30px 50px;
            font-family: "Microsoft Yahei", Helvetica, arial, sans-serif;
            margin-top: 90px;
        }

        div#main h1 {
            font-size: 200px;
            margin: 0px;
            color:#bbb;
            text-align: center;
        }

        div#main div.hdphp {
            font-size: 38px;
            color:#bbb;
            text-align: center;
        }
        span{
            font-size:16px;
        }
    </style>
</head>
<body>
    <div id="main">
        <h1> √  </h1>
        <div class="hdphp">
            为开发效率而生
            <br/>
            <span>版本: <?php echo HDPHP_VERSION?></span>
        </div>
    </div>
</body>
</html>