<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
    }


    public function index()
    {
        admin_log("新闻管理的列表查询");
        $this->load->view('news_mgr');
    }

    public function news_detail()
    {
        admin_log("新闻详情查询");
        $news_id = $this->input->get('newsId');
        $news = $this->news_model->get_by_id($news_id);
        if ($news) {
            echo json_encode($news);
        } else {
            echo json_encode(array('err' => '未找到指定信息!'));
        }
    }

    public function news_mgr()
    {
        admin_log("新闻管理");
        $draw = $this->input->get('draw');

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this->input->get('search[value]');//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)


        $order_col = array('0' => 'news_id', '1' => 'news_title', '2' => 'add_time');
        $recordsTotal = $this->news_model->get_all_count();
        $recordsFiltered = $this->news_model->get_filterd_count($search);


        $datas = $this->news_model->get_paginated_users($length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->news_id);
        }

        echo json_encode(array(
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);
    }

    public function news_del()
    {

        $news_id = $this->input->get("newsId");
        admin_log("删除新闻".$news_id);
        $result=$this->news_model->update_by_del($news_id);
        if($result){
            echo 'success';
        }
        else {
            echo "删除失败";
        }
    }

    public function news_edit(){
        $news_id = $this->input->post("newsId");
        admin_log("修改新闻".$news_id);
        $news_title = htmlspecialchars($this->input->post("newsTitle"));
        $news_content = $this->input->post("content");
        $row = $this->news_model->update_by_id($news_id,array('news_title'=>$news_title,'news_content'=>$news_content));
        if($row){
            redirect('news');
        }
    }

    public function news_add()
    {
        $news_title = htmlspecialchars($this->input->post("newsTitle"));
        admin_log("添加新闻".$news_title);
        $news_content = $this->input->post("content");

        $row = $this -> news_model -> save_news(array(
            "news_title" => $news_title,
            "news_content" => $news_content
        ));
        redirect('news');
    }

}
