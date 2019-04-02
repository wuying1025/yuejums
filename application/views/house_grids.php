<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'meta.php'; ?>

    <title>悦居后台管理系统 - 房源管理</title>

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
                房源管理
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">首页</a>
                </li>
                <li>
                    <a href="#">房源管理</a>
                </li>
                <li class="active"> 房态图</li>
            </ul>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading custom-tab">
                            <!-- 房源数据列表-->
                            <ul class="nav nav-tabs " id="my-tabs">
                                <li class="active">
                                    <a href="#all-houses" data-toggle="tab" class="all-orders">全部房源</a>
                                </li>
                                <li>
                                    <a href="#free" data-status="1" data-toggle="tab">空闲</a>
                                </li>
                                <li>
                                    <a href="#living" data-status="2" data-toggle="tab">入住中</a>
                                </li>
                                <li>
                                    <a href="#reserved" data-status="3" data-toggle="tab">已预订</a>
                                </li>
                        </header>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="all-houses">
                                    <div class="panel-body">
                                        <div class="adv-table">
                                            <table id="example" class="table table-house table-striped table-bordered"
                                                   cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>编号</th>
                                                    <th>名称</th>
                                                    <th>小区</th>
                                                    <th>位置</th>
                                                    <th>价格</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="free">
                                    <table id="free-table" class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>编号</th>
                                            <th>名称</th>
                                            <th>小区</th>
                                            <th>位置</th>
                                            <th>价格</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="living">
                                    <table id="living-table" class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>编号</th>
                                            <th>名称</th>
                                            <th>小区</th>
                                            <th>位置</th>
                                            <th>价格</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="reserved">
                                    <table id="reserved-table" class="table table-striped table-bordered"
                                           cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>编号</th>
                                            <th>名称</th>
                                            <th>小区</th>
                                            <th>位置</th>
                                            <th>价格</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
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

<!-- Placed js at the end of the document so the pages load faster -->
<script src="js/jquery-1.12.4.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="js/jquery-ui-1.10.3.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>


<script src="js/jquery.validate.min.js"></script>

<!--datatables script-->
<script src="js/datatables/js/jquery.dataTables.min.js"></script>
<script src="js/datatables/js/dataTables.bootstrap.js"></script>

<!--gritter script-->
<script src="js/gritter/js/jquery.gritter.js"></script>

<!--plupload script-->
<script src="js/plupload.full.min.js"></script>

<!--validate script-->
<script src="js/jquery.validate.min.js"></script>

<script src="js/template.js"></script>
<script src="js/jquery.sidepanel.js"></script>
<script src="js/city.js"></script>
<!--pickers plugins-->
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<!-- ueditor 配置文件 -->
<script src="js/ueditor/ueditor.config.js"></script>
<!-- ueditor 编辑器源码文件 -->
<script src="js/ueditor/ueditor.all.min.js"></script>

<!--common scripts for all pages-->
<script src="js/scripts.js"></script>
<script>
    $(function(){
        $('#my-tabs a:not(".all-orders")').click(function (e) {
            //数据需要ajax操作，可以直接在这里$.get(...);
            var recover = '',
                untreated = "",
                underway = "",
                done = "",
                orderCancel = "",
                refund = "";
            var status = $(this).attr('data-status');
            datatable = $(this.hash + '-table').DataTable({
                "aLengthMenu": [20, 25, 50, -1],
                "processing": true,
                "serverSide": true,
                "retrieve": true,
                "ajax": {
                    "url": "house/house_list",
                    "data": function (d) {
                        d.status = status;
                    }
                },
                "columns": [
                    {"width": "30px", "data": "house_id", "className": "text-center"},
                    {"data": "title"},
                    {"data": "plot_name"},
                    {
                        "width": "350px",
                        "data": "street"
                    },
                    {"data": "price"}
                ],
                "columnDefs": [
                    {"orderable": false, "targets": [1, 2, 3]},
                    {"className": "clicked-cell", "targets": [0, 1, 2, 3, 4]}
                ],

                "order": [[0, 'desc']]
            });
            datatable.ajax.reload();//重新加载数据，防止在操作过程中数据库中的记录发生变化
            $(this).tab('show');

            e.preventDefault();
        });


        //列表
        var table = $('#example').DataTable({
            "aLengthMenu": [20, 25, 50, -1],
            "processing": true,
            "serverSide": true,
            "ajax": "house/house_list",
            "columns": [
                {"width": "30px", "data": "house_id", "className": "text-center"},
                {"data": "title"},
                {"data": "plot_name"},
                {
                    "width": "350px",
                    "data": "street"
                },
                {"data": "price"}
            ],
            "columnDefs": [
                {"orderable": false, "targets": [1, 2, 3]},
                {"className": "clicked-cell", "targets": [0, 1, 2, 3, 4]}
            ],
            "order": [[0, 'desc']]
        });

    })
</script>