<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends CI_Model
{
    public function get_all_count()
    {
        $this->db->select('*');
        $this->db->from('t_news');
        $this->db->where('is_delete', 0);
        $this->db->where(array(
            'is_delete' => 0
        ));


        return $this->db->count_all_results();
    }

    public function update_by_del($id)
    {
        $this->db->where('news_id', $id);
        return $this->db->update('t_news', array(
            'is_delete' => 1
        ));
    }
    
    public function update_by_id($news_id,$data){
        $this->db->where('news_id', $news_id);
        return $this->db->update('t_news', $data);
    }

    public function save_news($data)
    {

        $this -> db -> insert('t_news',$data);
        return $this -> db -> affected_rows();
    }

    public function get_paginated_users($limit, $offset, $search, $order_col, $order_col_dir)
    {
        $sql = "SELECT * FROM t_news WHERE is_delete=0";
        if (strlen($search) > 0) {
            $sql .= " and (news_title LIKE '%" . $search . "%' or add_time LIKE '%" . $search . "%')";
        }
        $sql .= " order by $order_col $order_col_dir";
        $sql .= " limit $offset, $limit";

        return $this->db->query($sql)->result();
    }

    public function get_filterd_count($search)
    {
        $sql = "SELECT * FROM t_news WHERE is_delete=0";
        if (strlen($search) > 0) {
            $sql .= " and (news_title LIKE '%" . $search . "%' or add_time LIKE '%" . $search . "%')";
        }
        return $this->db->query($sql)->num_rows();
    }
    public function get_by_id($news_id){
        $this->db->select('*');
        $this->db->from('t_news');
        $this->db->where('news_id', $news_id);
        return $this->db->get()->row();
    }

}