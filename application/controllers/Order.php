<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('admin_model');
    }

    public function order_detail(){
        $id = $this -> input -> get('orderId');
//        $admins=$this->admin_model->query_admin_manager();

        admin_log('查询订单'.$id.'信息');
        $order = $this -> order_model ->get_order_by_id($id);

        if ($order) {
//            $order->admins = $admins;
            echo json_encode($order);
        } else {
            echo json_encode(array('err' => '未找到指定信息!'));
        }
    }

    public function order_edit(){
        $id = $this -> input -> get('orderId');
        admin_log('修改订单'.$id.'信息');
        $order = $this -> order_model ->get_order_by_id($id);
        $checkin_user = $this->order_model->get_checkin_user($id);
        $order->user = $checkin_user;
        if ($order) {
            echo json_encode($order);
        } else {
            echo json_encode(array('err' => '未找到指定信息!'));
        }
    }

    public function checkin()
    {
        $this->load->view('checkin_mgr');
    }
    public function index(){
        $this->load->view('order_mgr');
    }
    public function checkin_mgr(){
        admin_log('入住管理');
        $draw = $this->input->get('draw');
        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this -> input -> get('search[value]');
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)
        $order_col = array('0' => 'order_id','1'=>'order_type', '2' => 'title', '3' => 'start_time', '4' => 'end_time', '5' =>'status');
        $recordsTotal = $this->order_model->get_total_checkin_count();
        $recordsFiltered = $this->order_model->get_filterd_checkin_count($search);
        $orders = $this -> order_model ->get_checkin_order($length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($orders as $data) {
            $data->DT_RowData = array('id' => $data->order_id);
        }
        echo json_encode(array(
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $orders
        ), JSON_UNESCAPED_UNICODE);
    }

    public function order_mgr(){
        admin_log('订单管理');
        $status = $this ->input ->get('status');
        $draw = $this->input->get('draw');
        $date_search = $this->input ->get('extra_search');
        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this -> input -> get('search[value]');
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)
        $order_col = array('1' => 'order_id','2'=>'order_type', '3' => 'title', '4' => 'username', '5'=>'tel', '6' => 'start_time', '7' => 'end_time', '8' =>'price', '9' =>'status');
        $recordsTotal = $this->order_model->get_total_count($date_search);
        $recordsFiltered = $this->order_model->get_filterd_count($search,$status,$date_search);
        $orders = $this -> order_model ->get_order($length, $start, $search, $order_col[$order_col_no], $order_col_dir, $status,$date_search);
//        $orderFinished = [];
//        $orderPayed = [];
//        $orderPay = [];
//        $orderCancel = [];

//        if(count($orders) > 0){
            //按状态order_status分组,(已取消3,已完成2,已付款1,未付款0);
            foreach ($orders as $order){
                $order->DT_RowData = array('id' => $order->order_id);
            }
            echo json_encode(array(
                "draw" => intval($draw),
                "recordsTotal" => intval($recordsTotal),
                "recordsFiltered" => intval($recordsFiltered),
                "data" => $orders
            ), JSON_UNESCAPED_UNICODE);
//            $this -> load -> view('order_mgr',array(
//                'orderPay' => $orderPay,
//                'orderPayed' => $orderPayed,
//                'orderFinished' => $orderFinished,
//                'orderCancel' => $orderCancel
//            ));

//        }

    }
    public function update_order_status(){
        $order_id = $this->input->get('orderId');
        $status = $this ->input->get('status');
        admin_log('修改订单'.$order_id.'状态为'.$status);
        $rows = $this->order_model->update_order_by_id($order_id,$status);
        if($rows>0){
            echo 'success';
        }else{
            echo 'error';
        }
    }
    public function order_del()
    {
        $order_id = $this->input->get('orderId');
        admin_log('删除订单'.$order_id);
        $row =  $this->order_model->delete_order($order_id);
        if($row > 0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
    public function del_all(){
        $namearr=$this->input->post('name');
        admin_log('管理员删除订单');
        //var_dump($namearr);
        $result=$this->order_model->del_all_name($namearr);
        if($result>0){
            echo "success";
        }else{
            echo "error";
        }
    }
    public function order_recover(){
        $order_id = $this ->input ->get('orderId');
        $row =  $this->order_model->recover_order($order_id);
        if($row > 0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
    //入住操作start
    //查询所有的负责人
    public function enter_manage()
    {
        admin_log('查看房源负责人');
        $lev = 1;
        $manage = $this->order_model->enter_manage($lev);
        if($manage){
            echo json_encode(array('data' => $manage));
        }
    }
    //添加入住信息
    public function add_checkin()
    {
        $orderId = $this -> input -> post("order_id");
        admin_log('添加订单'.$orderId.'的入住信息');
        $arrayName = $this -> input -> post("arrayName");
        $arrayPhone = $this -> input -> post("arrayPhone");
        $arrayCard = $this -> input -> post("arrayCard");
        $arr = array();
        for($i=0; $i<count($arrayName); $i++){
            array_push($arr, array(
                "order_id"=>"$orderId",
                "name"=>$arrayName[$i],
                "tel"=>$arrayPhone[$i],
                "id_card"=>$arrayCard[$i]
            ));
        }
//        var_dump($arr);
//        die();
        $row = $this->order_model->add_checkin($arr);
        if($row>0){
            echo "success";
        }else{
            echo "fail";
        }
    }
    //办理入住，更改order表信息
    public function manage_enter()
    {

        $id = $this->input->get("id");
        admin_log('添加订单'.$id.'的入住');
        $pledge = $this->input->get("pledge");
        $enter_mask = $this->input->get("enter_mask");
        $pay = $this->input->get("pay");

        $array_name = $this -> input -> get('arrayName');
        $array_phone = $this -> input -> get('arrayPhone');
        $array_card = $this -> input -> get('arrayCard');
        $data_arr = [];

        for($i=0;$i<count($array_name);$i++){
            $data_arr[] = array('order_id'=>$id,'name'=>$array_name[$i],'tel'=>$array_phone[$i],'id_card'=>$array_card[$i]);
        }
        $row = $this->order_model->add_enter($data_arr);
        if($row > 0){
            $result = $this->order_model->manage_enter($id,$pledge,$enter_mask,$pay);
            if($result>0){
                echo "success";
            }else{
                echo "fail";
            }
        }else{
            echo 'fail';
        }


    }
    //入住操作end
    private function is_free_house($houseId, $startTime, $endTime){
        $now = date('y-m-d');
        if(strtotime($startTime)<strtotime($now) || strtotime($endTime)<strtotime($now)){
            return false;
        }else{
            $result = $this->order_model->is_free_house($houseId, $startTime, $endTime);
            if (count($result) > 0) {
                //不能订房
                return false;
            } else {
                return true;
            }
        }
    }

    private function is_free_house_unorder($houseId, $startTime, $endTime,$order_id){
        $now = date('y-m-d');
        if(strtotime($startTime)<strtotime($now) || strtotime($endTime)<strtotime($now)){
            return false;
        }else{
            $result = $this->order_model->is_free_house_unorder($houseId, $startTime, $endTime,$order_id);
            if (count($result) > 0) {
                //不能订房
                return false;
            } else {
                return true;
            }
        }
    }
    // 添加订单
    public function add_order(){
        $house_id = $this->input->get('house_id');
        admin_log('添加房源'.$house_id.'的订单');
        $user_id = $this->input->get('user_id');
        $dpd1 = $this->input->get('dpd1');
        $dpd2 = $this ->input->get('dpd2');
        $status = $this ->input->get('status');
        $price = $this->input->get('price');
        $pay = $this->input->get('pay');
        $order_no = date("YmdHis") . rand(10000, 99999);
        //根据时间判断房子是否空闲
        admin_log('添加房源'.$house_id.'的订单,入住时间为'.$dpd1);
        $isFree = $this->is_free_house($house_id,$dpd1,$dpd2);
        if(!$isFree){
            echo 'not-free';
        }else{
            $row = $this->order_model->add_order($order_no,$price,$status,$house_id,$user_id,$dpd1,$dpd2,$pay);
            if($row>0){
                echo 'success';
            }else{
                echo 'fail';
            }
        }
    }
    //编辑订单
    public function edit_order(){
        $house_id = $this->input->get('house_id');
        $user_id = $this->input->get('user_id');
        $start_time = $this->input->get('start_time');
        $end_time = $this ->input->get('end_time');
        $status = $this ->input->get('status');
        $price = $this->input->get('price');
        $order_no = $this->input->get('order_no');
        $urgency_concat = $this->input->get('urgency_concat');
        $urgency_contact_tel = $this->input->get('urgency_contact_tel');
        $cash_pledge = $this->input->get('cash_pledge');
        $return_cash_pledge = $this->input->get('return_cash_pledge');
        $cash_pledge_pay_way = $this->input->get('cash_pledge_pay_way');
        $add_time = $this->input->get('add_time');
        $order_type = $this->input->get('order_type');
        $order_memo = $this->input->get('order_memo');
        $checkout_mark = $this->input->get('checkout_mark');
        $checkin_memo = $this->input->get('checkin_memo');
        $checkout_has_problem = $this->input->get('checkout_has_problem');
        $order_id = $this->input->get('order_id');
        $array_name = $this->input->get('arrayName');
        $array_phone = $this->input->get('arrayPhone');
        $array_card = $this->input->get('arrayCard');

        admin_log('修改订单'.$order_id);
        $data = array(
            'house_id'=>$house_id,
            'user_id'=>$user_id,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
            'status'=>$status,
            'price'=>$price,
            'urgency_concat'=>$urgency_concat,
            'urgency_contact_tel'=>$urgency_contact_tel,
            'cash_pledge'=>$cash_pledge,
            'return_cash_pledge'=>$return_cash_pledge,
            'cash_pledge_pay_way'=>$cash_pledge_pay_way,
            'add_time'=>$add_time,
            'order_type'=>$order_type,
            'order_memo'=>$order_memo,
            'checkout_mark'=>$checkout_mark,
            'checkin_memo'=>$checkin_memo,
            'checkout_has_problem'=>$checkout_has_problem
            );

        $this->order_model->del_check($order_id);
        $data_arr = [];
        for($i=0;$i<count($array_name);$i++){
            $data_arr[] = array('order_id'=>$order_id,'name'=>$array_name[$i],'tel'=>$array_phone[$i],'id_card'=>$array_card[$i]);
        }
        if(count($data_arr) >0 ){
            $row = $this->order_model->add_enter($data_arr);
        }
        //需要判断是不是更改时间  如果是更改时间的话 判断是否空闲
        $order = $this -> order_model ->get_order_by_id($order_id);
        $data['house_id'] = $house_id==null || $house_id==''?$order->house_id:$house_id;
        $data['user_id'] = $user_id==null || $user_id==''?$order->user_id:$user_id;

        if($order->start_time != $start_time || $order->end_time != $end_time){
            //更改了时间,需要做房屋空闲验证,这个验证房屋还需要将当前订单这条去掉
            $isFree = $this->is_free_house_unorder($data['house_id'],$start_time,$end_time,$order_id);
            if(!$isFree){
                echo 'not-free';
            }else{
                $row = $this->order_model->order_update($order_id,$data);
                echo 'success';
            }
        }else{
            $row = $this->order_model->order_update($order_id,$data);
            echo 'success';
        }


    }

    public function order_end(){
        $id = $this -> input -> get('orderId');
        admin_log('结束订单'.$id);
        $order = $this -> order_model ->get_order_by_id($id);
        $order->checkout_charge = $this->session->userdata('admininfo');
        $order->admin_user = $this -> admin_model ->query_admin_manager();
        if ($order) {
            echo json_encode($order);
        } else {
            echo json_encode(array('err' => '未找到指定信息!'));
        }
    }
    //退房
    public function order_end_info(){
        $orderId = $this -> input -> get('orderId');
        admin_log('办理订单'.$orderId.'的退房');
        $name = $this -> input -> get('name');
        $house_exam = $this -> input -> get('house_exam');
        $money = $this -> input -> get('money');
        $return_way = $this -> input -> get('return_way');
        $checkout_mark = $this -> input -> get('checkout_mark');
        $is_invoice = $this -> input -> get('is_invoice');
        $invoice_title = $this -> input -> get('invoice_title');
        $invoice_address = $this -> input -> get('invoice_address');
        $invoice_tel = $this -> input -> get('invoice_tel');
        $data = array(
            'checkout_charge'=>$name,
            'checkout_has_problem'=>$house_exam,
            'return_cash_pledge'=>$money,
            'return_way'=>$return_way,
            'checkout_mark'=>$checkout_mark,
            'is_invoice'=>$is_invoice,
            'invoice_title'=>$invoice_title,
            'invoice_address'=>$invoice_address,
            'invoice_person_tel'=>$invoice_tel,
            'status'=>'已完成'
            );
        $row = $this -> order_model ->order_update($orderId,$data);
        if($row>0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
    public function order_cancel(){
        $orderId = $this -> input -> get('orderId');
        admin_log('取消订单'.$orderId);
        $pledge = $this -> input -> get('pledge');
        $mask = $this -> input -> get('mask');
        $data = array(
            "return_cash_pledge"=>$pledge,
            "checkout_mark"=>$mask,
            'status'=>'用户取消'
        );
        $row = $this -> order_model ->order_update($orderId,$data);
        if($row>0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
    public function checkin_detail(){
        $orderId = $this -> input -> get('orderId');
        admin_log('查看订单'.$orderId.'的入住详情');
        $order = $this -> order_model ->checkin_detail($orderId);
        echo json_encode($order);
    }

    public function order_detail_keep()
    {
        $order_id = $this -> input -> get('orderId');
        $order = $this -> order_model ->get_order_by_id($order_id);
        admin_log('办理订单'.$order_id.'的续租');
        $result_houseTime = $this -> order_model -> get_date_by_house($order->house_id);

        $dataArr = "";
        foreach ($result_houseTime as $o){
            $start = strtotime($o->start_time);
            $end = strtotime($o->end_time);
            while ($start<=$end){
                $dataArr .= date('Y-m-d',$start) . ",";
                $start = strtotime('+1 day',$start);
            }
        }
        $order->datearr = $dataArr;

        if ($order) {
            echo json_encode($order);
        } else {
            echo json_encode(array('err' => '未找到指定信息!'));
        }
    }

    public function order_keep()
    {
        $order_id = $this -> input -> get('order_id');
        admin_log('办理订单'.$order_id.'的续租');
        $order = $this -> order_model ->get_only_order_by_id($order_id);
        $randNum = '';
        for ($i = 0; $i < 5; $i++) {
            $randNum .= rand(0, 9);
        }
        $order_num = date("YmdHis") . $randNum;
        $order->order_id = null;
        $order->order_no = $order_num;
        $order->price = $this -> input -> get('price');
        $order->return_way = $this -> input -> get('return_way');
        $order->start_time = $this->input->get('start_time');
        $order->end_time = $this ->input->get('end_time');

        $row = $this->order_model->add_order_keep($order);
        if($row>0){
            echo 'success';
        }else{
            echo 'fail';
        }

    }

    public function cancel_order()
    {
        $order_id = $this -> input -> get('orderId');
        admin_log('取消订单'.$order_id);

        $pledge = $this -> input -> get('pledge');
        $cancelMemo = $this -> input -> get('cancelMemo');
        $row = $this->order_model->cancel_order($order_id, $pledge, $cancelMemo);
        if($row>0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
    //添加入住人
    public function add_enter(){
        $order_id = $this -> input -> get('order_id');
        admin_log('添加订单'.$order_id.'的入住人');
        $array_name = $this -> input -> get('arrayName');
        $array_phone = $this -> input -> get('arrayPhone');
        $array_card = $this -> input -> get('arrayCard');
        $data_arr = [];
        for($i=0;$i<count($array_name);$i++){
            $data_arr[] = array('order_id'=>$order_id,'name'=>$array_name[i],'tel'=>$array_phone[i],'id_card'=>$array_card[i]);
        }
        $row = $this->order_model->add_enter($data_arr);
        if($row > 0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }
}

