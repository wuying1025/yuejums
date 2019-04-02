<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Developer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('developer_model');
    }

    public function index()
    {
        admin_log("开发商管理的列表查询");
        $this->load->view('developer_mgr');
    }

    public function developer_mgr()
    {
        admin_log("开发商管理");
        $draw = $this->input->get('draw');

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this->input->get('search[value]');//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)


        $order_col = array('0' => 'developer_id', '1' => 'logo', '2' => 'developer_name', '3' => 'address', '4' => 'telephone');
        $recordsTotal = $this->developer_model->get_all_count();
        $recordsFiltered = $this->developer_model->get_filterd_count($search);


        $datas = $this->developer_model->get_paginated_developers($length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->developer_id);
        }

        echo json_encode(array(
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);

    }

    public function developer_add()
    {
        $developer_id = $this->input->post("developer_id");
        $developer_name = htmlspecialchars($this->input->post("developer_name"));
        $founding_time = htmlspecialchars($this->input->post("founding_time"));
        $telephone = htmlspecialchars($this->input->post("telephone"));
        $address = htmlspecialchars($this->input->post("address"));
        $description = $this->input->post("content");
        $upload_img = $this->input->post("upload_img");
        $data = array(
            "developer_id" => $developer_id,
            "developer_name" => $developer_name,
            "description" => $description,
            "telephone" => $telephone,
            "address" => $address,
            "founding_time" => $founding_time,

        );
        //"logo" => $img
        if($developer_id){
            admin_log("开发商修改");
            if($upload_img == "" || $upload_img == null){
                $developer = $this->developer_model->get_by_id($developer_id, array('inc_house'=>TRUE));
                $data['logo'] = $developer->logo;
            }else{
                $data['logo'] = $upload_img;
            }

        }else{
            admin_log("开发商添加");
            $img = $upload_img == "" || $upload_img == null ? 'images/head-default.png' : $upload_img;
            $data['logo'] = $img;
        }

        $this -> developer_model -> save_developer($data);
        redirect('developer');
    }

    public function developer_check_name()
    {
        $developer_name = $this->input->get("developer_name");
        $row = $this -> developer_model -> get_by_developername($developer_name);
        if($row){
            echo "fail";
        }else{
            echo "success";
        }
    }

    public function developer_detail()
    {
        admin_log("开发商详情查询");
        $developerId = $this->input->get('developerId');
        $developer = $this->developer_model->get_by_id($developerId, array('inc_house'=>TRUE));
        if ($developer) {
            echo json_encode($developer);
        } else {
            echo json_encode(array('err' => '未找到指定开发商信息!'));
        }
    }

    public function developer_house()
    {
        admin_log("查询开发商信息");
        $developerId = $this->input->get('developerId');

        $draw = $this->input->get('draw');//jquery.datatables用到的数据，类似一个计数器，必须要用到

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this->input->get('search[value]');//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)

        //定义前台datatables中要显示和排序的列出数据库中字段的关系
        $order_col = array('0' => 'title', '1' => 'price','2' => 'area');

        $recordsTotal = $this->developer_model->get_total_developer_house_count($developerId);//获取所有记录数，必须要用到
        $recordsFiltered = $this->developer_model->get_filterd_developer_house_count($developerId, $search);//获取搜索过滤后的记录数，必须要用到

        //获取要分页的数据
        $datas = $this->developer_model->get_paginated_developer_house($developerId, $length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->developer_id);//jquery.datatables插件要用DT_RowData属性来为每一个tr绑定自定义data-*属性
        }
        echo json_encode(array(//返回的数据，下面这几个参数draw、recordsTotal、recordsFiltered都是jquery.datatables要求必须要传的
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);
    }

    public function developer_del_all()
    {
        admin_log("开发商删除");
        $ids = $this -> input -> get("ids");
        $result = $this -> developer_model -> update_by_del_all($ids);
        if($result){
            echo "success";
        }
        else {
            echo "fail";
        }
    }

    public function developer_edit()
    {
        admin_log("开发商修改查询");
        $developerId = $this->input->get('developerId');
        $developer = $this->developer_model->get_by_developer_id($developerId);
        if ($developer) {
            echo json_encode($developer);
        } else {
            echo json_encode(array('err' => '未找到指定开发商信息!'));
        }
    }

    public function img_upload()
    {
        $typeArr = array("jpg", "png", "ico", "gif", "jpeg");
        //允许上传文件格式
        $path = "uploads/developer/";//

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