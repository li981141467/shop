<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"F:\php\wuif1808\shop\public/../application/admin\view\login\index.html";i:1543904317;}*/ ?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>欢迎你进入后台管理系统</title>
    <link rel="stylesheet" type="text/css" href="/static/login/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/static/login/css/demo.css" />
    <!--必要样式-->
    <link rel="stylesheet" type="text/css" href="/static/login/css/component.css" />
    <!--[if IE]>
    <script src="/static/login/js/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="container demo-1">
    <div class="content">
        <div id="large-header" class="large-header">
            <canvas id="demo-canvas"></canvas>
            <div class="logo_box">
                <h3>欢迎你进入后台管理系统</h3>
                <form action="<?php echo url('Login/check'); ?>" name="f" method="post">
                    <div class="input_outer">
                        <span class="u_user"></span>
                        <input name="username" class="text" style="color: #FFFFFF !important" type="text" placeholder="请输入账户" required >
                    </div>
                    <div class="input_outer">
                        <span class="us_uer"></span>
                        <input name="password" class="text" style="color: #FFFFFF !important; position:absolute; z-index:100;"value="" type="password" placeholder="请输入密码" required>
                    </div>
                    <div class="mb2"><input type="submit" value="登录" style="width: 100%;border: 0;color: white;" class="act-but submit"></div>
                </form>
            </div>
        </div>
    </div>
</div><!-- /container -->
<script src="/static/login/js/TweenLite.min.js"></script>
<script src="/static/login/js/EasePack.min.js"></script>
<script src="/static/login/js/rAF.js"></script>
<script src="/static/login/js/demo-1.js"></script>
</body>
</html>