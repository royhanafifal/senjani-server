<?php
class Login_model extends CI_Model 
{
    public function create_pelanggan($data)
    {
        $this->db->insert('pelanggan', $data);
        return $this->db->affected_rows();
    }

    public function get_pelanggan($email)
    {
        return $this->db->get_where('pelanggan', ['email' => $email])->result_array();
    }
}