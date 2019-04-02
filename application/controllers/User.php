<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }


    public function index()
    {
        admin_log("用户管理的列表查询");
        $this->load->view('user_mgr');
    }

    public function user_detail()
    {
        admin_log("用户详情查询");
        $user_id = $this->input->get('userId');
        $user = $this->user_model->get_by_id($user_id, array('inc_orders'=>TRUE, 'inc_messages'=>TRUE));
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(array('err' => '未找到指定用户信息!'));
        }
    }

    public function user_mgr()
    {
        admin_log("用户管理");
        $draw = $this->input->get('draw');

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this->input->get('search[value]');//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)


        $order_col = array('0' => 'user_id', '1' => 'portrait', '2' => 'username', '3' => 'rel_name', '4' => 'tel', '5' => 'email');
        $recordsTotal = $this->user_model->get_all_count();
        $recordsFiltered = $this->user_model->get_filterd_count($search);


        $datas = $this->user_model->get_paginated_users($length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->user_id);
        }

        echo json_encode(array(
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);
    }

    public function user_del()
    {
        admin_log("用户删除");
        $id=$this->uri->segment(3);
        $result=$this->user_model->update_by_del($id);
        if($result){
            redirect('user');
            //$this->userall();
        }
        else {
            echo "删除失败";
        }
    }

    public function user_del_all()
    {
        admin_log("用户删除全部");
        $ids = $this -> input -> get("ids");
        $result = $this -> user_model -> update_by_del_all($ids);
        if($result){
            echo "success";
        }
        else {
            echo "fail";
        }
    }

    public function user_check_name()
    {

        $username = $this->input->get("username");
        $row = $this -> user_model -> get_by_username($username);
        if($row){
            echo "fail";
        }else{
            echo "success";
        }
    }

    public function user_add()
    {
        $user_id = $this->input->post("user_id");
        $username = htmlspecialchars($this->input->post("uname"));
        $password = htmlspecialchars($this->input->post("password"));
        $relname = htmlspecialchars($this->input->post("relname"));
        $sex = $this->input->post("sex");
        $birthday = $this->input->post("birthday");
        $email = htmlspecialchars($this->input->post("email"));
        $tel = htmlspecialchars($this->input->post("tel"));
        $upload_img = htmlspecialchars($this->input->post("upload_img"));

        $data = array(
            "user_id" => $user_id,
            "username" => $username,
            "password" => md5($password),
            "tel" => $tel,
            "email" => $email,
            "rel_name" => $relname,
            "sex" => $sex,
            "birthday" => $birthday,
            "is_delete" => 0
        );

        if($user_id){
            admin_log("用户修改");
            if($upload_img == "" || $upload_img == null){
                $user = $this->user_model->get_by_id($user_id, array('inc_house'=>TRUE));
                $data['portrait'] = $user->portrait;
            }else{
                $data['portrait'] = $upload_img;
            }
        }else{
            admin_log("用户添加");
            $img = $upload_img == "" || $upload_img == null ? 'images/head-default.png' : $upload_img;
            $data['portrait'] = $img;
        }
        $this -> user_model -> save_user($data);
        redirect('user');
    }

    public function user_orders()
    {
        $user_id = $this->input->get('userId');
        admin_log('查询用户'.$user_id.'的订单');
        $draw = $this->input->get('draw');//jquery.datatables用到的数据，类似一个计数器，必须要用到

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this->input->get('search[value]');//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)

        //定义前台datatables中要显示和排序的列出数据库中字段的关系
        $order_col = array('0' => 'order_id', '1' => 'title','2' => 'start_time', '3' => 'end_time', '4' => 'price', '5' => 'status');

        $recordsTotal = $this->user_model->get_total_user_orders_count($user_id);//获取所有记录数，必须要用到
        $recordsFiltered = $this->user_model->get_filterd_user_orders_count($user_id, $search);//获取搜索过滤后的记录数，必须要用到

        //获取要分页的数据
        $datas = $this->user_model->get_paginated_user_orders($user_id, $length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->order_id);//jquery.datatables插件要用DT_RowData属性来为每一个tr绑定自定义data-*属性
        }

        echo json_encode(array(//返回的数据，下面这几个参数draw、recordsTotal、recordsFiltered都是jquery.datatables要求必须要传的
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);

    }

    public function user_messages()
    {
        $user_id = $this->input->get('userId');
        admin_log('查询用户'.$user_id.'的留言');
        $draw = $this->input->get('draw');//jquery.datatables用到的数据，类似一个计数器，必须要用到

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this->input->get('search[value]');//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)

        //定义前台datatables中要显示和排序的列出数据库中字段的关系
        $order_col = array('0' => 'username', '1' => 'add_time','2' => 'content');

        $recordsTotal = $this->user_model->get_total_user_messages_count($user_id);//获取所有记录数，必须要用到
        $recordsFiltered = $this->user_model->get_filterd_user_messages_count($user_id, $search);//获取搜索过滤后的记录数，必须要用到

        //获取要分页的数据
        $datas = $this->user_model->get_paginated_user_messages($user_id, $length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->message_id);//jquery.datatables插件要用DT_RowData属性来为每一个tr绑定自定义data-*属性
        }

        echo json_encode(array(//返回的数据，下面这几个参数draw、recordsTotal、recordsFiltered都是jquery.datatables要求必须要传的
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);
    }

    public function user_send_message()
    {

        $user_id = $this->input->get('userId');
        admin_log("用户'.$user_id.'发送消息");
        $user = $this->user_model->get_by_id($user_id, array('inc_orders'=>FALSE, 'inc_messages'=>FALSE));
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(array('err' => '未找到指定用户信息!'));
        }
    }

    //订单管理-添加新用户
    public function add_new_user()
    {
        admin_log("用户添加--订单管理");
        $name = $this->input->get("name");
        $pwd = $this->input->get("pwd");
        $tel = $this->input->get("tel");
        $row = $this->user_model->add_new_user($name,md5($pwd),$tel);
        if($row){
            echo "success";
        }else{
            echo "file";
        }
    }
    public function add_new_user_get_id()
    {
        admin_log("用户添加--订单管理");
        $name = $this->input->get("name");
        $pwd = $this->input->get("pwd");
        $tel = $this->input->get("tel");
        $row = $this->user_model->add_new_user_get_id($name,md5($pwd),$tel);
        if($row){
            echo $row;
        }else{
            echo "fail";
        }
    }


    //用户名查重
    public function user_help()
    {
        $name = $this->input->get("name");
        $row = $this->user_model->user_help($name);
        if($row){
            echo "yes";
        }else{
            echo "no";
        }
    }
    //桉手机号搜索用户
    public function tel_search(){
        $tel = $this->input->get('tel');
        $row = $this->user_model->tel_search($tel);
        echo json_encode($row);
    }

    public function img_upload()
    {
        $typeArr = array("jpg", "png", "ico", "gif", "jpeg");
        //允许上传文件格式
        $path = "../uploads/headImg/";//

        if (isset($_POST)) {
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $name_tmp = $_FILES['file']['tmp_name'];
            if (empty($name)) {
                echo json_encode(array("error" => "您还未选择图片"));
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1));
            //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error" => "请上传jpg,png,ico,gif,jpeg类型的图片！"));
                exit;
            }
            if ($size > (500 * 1024)) {
                echo json_encode(array("error" => "图片大小已超过500KB！"));
                exit;
            }
            $pic_name = time() . rand(10000, 99999) . "." . $type;
            $pic_url =  $path . $pic_name;

            //上传后图片路径+名称
            if (move_uploaded_file($name_tmp, $pic_url)) {//临时文件转移到目标文件夹
                echo json_encode(array("error" => "0", "pic" => $pic_url, "name" => $pic_url));

            } else {
                echo json_encode(array("error" => "上传有误，请稍后重试！"));
            }
        }
    }



}
