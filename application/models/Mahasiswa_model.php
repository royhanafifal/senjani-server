<?php
class Mahasiswa_model extends CI_Model 
{
    public function getMahasiswa($id = null)
    {
        if($id === null){
            return $this->db->get('mahasiswa')->result_array();
        }else{
            return $this->db->get_where('mahasiswa', ['id' => $id])->result_array();
        }
    }

    public function deleteMahasiswa($id)
    {
        $this->db->delete('mahasiswa', ['id' => $id]);
        return $this->db->affected_rows();//Mengecek berapa data terpengaruh
    }

    public function createMahasiswa($data)      
    {
        $this->db->insert('mahasiswa', $data); 
        return $this->db->affected_rows();//Mengecek berapa data terpengaruh
    }

    public function updateMahasiswa($data, $id)      
    {
        $this->db->update('mahasiswa', $data, ['id' => $id]); 
        return $this->db->affected_rows();//Mengecek berapa data terpengaruh
    }

    public function searchMahasiswa($keyword)
    {
        $this->db->like('nama', $keyword);
        $this->db->or_like('jurusan', $keyword);
        $this->db->or_like('nrp', $keyword);
        $this->db->or_like('email', $keyword);
        return $this->db->get('mahasiswa')->result_array();
    }
}
