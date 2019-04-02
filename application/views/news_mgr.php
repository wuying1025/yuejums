<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'meta.php'; ?>

    <title>悦居后台管理系统 - 新闻管理</title>

    <base href="<?php echo site_url(); ?>">

    <!--bootstrap-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">

    <!--jQuery UI-->
    <link href="css/jquery-ui-1.10.3.css" rel="stylesheet">

    <!--font awesome-->
    <link href="css/font-awesome.min.css" rel="stylesheet">

    <!--datatables-->
    <link href="js/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/bootstrap-fileupload.min.css">

    <!--gritter-->
    <link href="js/gritter/css/jquery.gritter.css" rel="stylesheet"/>

    <!--pickers css-->
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker-custom.css" />


    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>

<body class="sticky-header">

<section>
    <!-- left side start-->
    <?php include 'sidebar.php'; ?>
    <!-- left side end-->

    <!-- main content start-->
    <div class="main-content">

        <!-- header section start-->
        <?php include 'header.php'; ?>
        <!-- header section end-->

        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                新闻管理
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">首页</a>
                </li>
                <li>
                    <a href="#">新闻管理</a>
                </li>
                <li class="active"> 新闻管理</li>
            </ul>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading">
                            新闻数据列表
                            <span class="tools pull-right">
                                <a href="javascript:;" class="fa fa-chevron-down"></a>
                             </span>
                        </header>
                        <div class="panel-body" id="my-tab-pane">
                            <div class="btn-group">
                                <button id="btn-new" class="btn btn-primary">
                                    添加 <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div class="adv-table">
                                <div></div>
                                <table class="table table-striped table-bordered" id="news-table" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>标题</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <?php include 'footer.php'; ?>
        <!--footer section end-->


    </div>
    <!-- main content end-->
</section>
<!-- templates -->
<script id="news-tpl" type="text/html">
    <?php include 'tpls/news_tpl.html'; ?>
</script>
<script id="new-news-tpl" type="text/html">
    <?php include 'tpls/new_news_tpl.html'; ?>
</script>
<script id="edit-news-tpl" type="text/html">
    <?php include 'tpls/edit_news_tpl.html'; ?>
</script>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<!--validate script-->
<script src="js/jquery.validate.min.js"></script>

<!--datatables script-->
<script src="js/datatables/js/jquery.dataTables.min.js"></script>
<script src="js/datatables/js/dataTables.bootstrap.js"></script>


<script src="js/gritter/js/jquery.gritter.js"></script>

<script src="js/template.js"></script>
<script src="js/jquery.sidepanel.js"></script>

<!--pickers plugins-->
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<!--common scripts for all pages-->
<script src="js/scripts.js"></script>
<script src="js/bootstrap-fileupload.min.js"></script>
<!-- ueditor 配置文件 -->
<script src="js/ueditor/ueditor.config.js"></script>
<!-- ueditor 编辑器源码文件 -->
<script src="js/ueditor/ueditor.all.min.js"></script>
<script>
    $(function(){

        var table = $('#news-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "news/news_mgr",
            "columns": [
                {"data": "news_id"},
                {"data": "news_title"},
                {"data": "add_time"},
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<a href="javascript:;" class="btn-edit">编辑 <i class="fa fa-edit"></i></a> | <a href="javascript:;" class="btn-del" data-id="' + row.news_id + '">删除 <i class="fa fa-times"></i></a>';
                    },
                }
            ],
            "columnDefs": [
                {"orderable": false, "targets": [1, 3]},
                {"className": "clicked-cell", "targets": [0,1,2]},
            ],
            "order": [[0, 'desc']]
        });

        //增加按钮
        $('#btn-new').on('click', function () {
            $.sidepanel({
                width: 700,
                title: '添加新闻',
                tpl: 'new-news-tpl',
                callback: function () {

                    //实例化编辑器
                    UE.delEditor('new-container');
                    var ue = UE.getEditor('new-container',{
                        initialFrameWidth:470  //初始化编辑器宽度
                        ,initialFrameHeight:200  //初始化编辑器高度
                    });
                }
            });
        });
        $('#my-tab-pane').on('click', '.btn-edit', function () {

            var dataId = $(this).parents('tr').data('id');

            $.sidepanel({
                width: 700,
                title: '编辑房源',
                tpl: 'edit-news-tpl',
                dataSource: 'news/news_detail',
                data: {
                    newsId: dataId
                },
                callback: function () {//sidepanel显示后的后续操作，主要是针对sidepanel中的元素的dom操作

                    UE.delEditor('container');
                    //实例化编辑器
                    var ue = UE.getEditor('container',{
                        initialFrameWidth:470  //初始化编辑器宽度
                        ,initialFrameHeight:200  //初始化编辑器高度
                    });

                    ue.ready(function() {
                        //设置编辑器的内容
                        ue.setContent($('#news-content').val());
                    });
                }


            });
        });

        $('#my-tab-pane').on('click', '.btn-del', function () {
            if (confirm('是否删除该记录')) {
                var dataId = $(this).data('id');
                $.get('news/news_del', {newsId: dataId}, function (data) {
                    if (data == 'success') {
                        table.ajax.reload(null, true);//重新加载数据
                        $.gritter.add({
                            title: '信息提示!',
                            text: '记录删除成功!'
                        });
                    }
                }, 'text');
            }
            return false;
        });


        /*****点击表格显示记录查情*****/
        $('#my-tab-pane').on('click', '.clicked-cell', function () {
            var dataId = $(this).parent().data('id');

            $.sidepanel({
                width: 700,
                title: '新闻详情',
                tpl: 'news-tpl',
                dataSource: 'news/news_detail',
                data: {
                    newsId: dataId
                },
                callback: function () {//sidepanel显示后的后续操作，主要是针对sidepanel中的元素的dom操作

                }
            });
        });


    });
</script>
</body>
</html>
