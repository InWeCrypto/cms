<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-param" content="_token" />
    <title>InWeCrypto</title>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.bootcss.com/metisMenu/2.7.1/metisMenu.min.css" />

    <link rel="stylesheet" href="{{ url('css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ url('plugins/sidebar-menu/sidebar-menu.css') }}" />

    <link rel="icon" href="{{ url('img/logo.png') }}" type="image/x-icon">
    @stack('style')
</head>
<body class="gray-bg" background="{{ url('img/background.jpg') }}">
    <nav class="navbar navbar-inverse navbar-inner">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <a class="navbar-brand" href="#">
            <img alt="Brand" src="{{ url('img/logo.png') }}" width="94" height="63">
          </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">HI,Wendy</a></li>
            <li><a href="#"><img alt="退出" src="{{ url('img/logout.png') }}"></a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <div class="row">
        <div class="col-md-2">
            <aside class="main-sidebar">
                <section  class="sidebar">
                    <ul class="sidebar-menu">
                      <li class="header">主导航</li>
                      <li class="treeview">
                        <a href="">
                            <img src="{{ url('img/menu_ico/home.png') }}">
                            <span>数据汇总</span>
                        </a>
                      </li>
                      <li class="treeview">
                        <a href="#">
                          <img src="{{ url('img/menu_ico/list.png') }}">
                          <span>项目管理</span>
                          <img class="pull-right right" src="{{ url('img/menu_ico/right.png') }}">
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                          <li><a href="{{ URL::to('/project') }}">项目列表</a></li>
                          <li><a href="{{ URL::to('/project/create') }}">添加项目</a></li>
                        </ul>
                      </li>
                    </ul>
                </section>
            </aside>
        </div>
        <div class="col-md-10" id="container">
            <div class="col-md-11">
              @section('content')
              @show
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-ujs/1.2.2/rails.min.js"></script>

<script src="{{ url('plugins/sidebar-menu/sidebar-menu.js') }}"></script>
<script>
$(function(){
    $.sidebarMenu($('.sidebar-menu'));
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>
@stack('script')
</html>