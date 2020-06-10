<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Mahasiswa extends CI_Controller{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('Mahasiswa_model', 'mhs');

        $this->methods['index_get']['limit'] = 100;
    }

    public function index_get(){

        //Untuk cek apakah ID ada atau tidak
        $id = $this->get('id');
        if($id === null){
            $mahasiswa = $this->mhs->getMahasiswa();
        }else{
            $mahasiswa = $this->mhs->getMahasiswa($id);
        }

        if($mahasiswa){
            $this->response([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $mahasiswa
            ], 200);//HTTP_OK
        }else{
            $this->response([
                'status' => false,
                'message' => 'Data Tidak ditemukan'
            ], 404);//HTTP_NOT_FOUND
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        if($id === null){
            $this->response([
                'status' => false,
                'message' => 'Masukkan Id'
            ], 400);//HTTP_BAD_REQUEST
        }else{
            if($this->mhs->deleteMahasiswa($id) > 0){
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'Data terhapus'
                ], 200);//HTTP_OK
            }else{
                $this->response([
                    'status' => false,
                    'message' => 'Id tidak ditemukan'
                ], 204);//HTTP_NO_CONTENT
            }
        }
    }

    public function index_post()
    {
        $data = [
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan')

        ];

        if($this->mhs->createMahasiswa($data) > 0){
            $this->response([
                'status' => true,
                'message' => 'Data berhasil ditambahkan'
            ], 201);//HTTP_CREATED
        }else{
            $this->response([
                'status' => false,
                'message' => 'Gagal menambahkan data baru'
            ], 400);//HTTP_NO_CONTENT
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $data = [
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan')

        ];

        if($this->mhs->updateMahasiswa($data, $id) > 0){
            $this->response([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ], 200);
        }else{
            $this->response([
                'status' => false,
                'message' => 'Gagal update data'
            ], 400);//HTTP_BAD_REQUEST
        }
    }

    public function search_get()
    {   
        $keyword = $this->get('keyword');
        $mahasiswa = $this->mhs->searchMahasiswa($keyword);

        if($mahasiswa){
            $this->response([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $mahasiswa
            ], 200);//HTTP_OK
        }else{
            $this->response([
                'status' => false,
                'message' => 'Data Tidak ditemukan'
            ], 404);//HTTP_NOT_FOUND
        }
    }

}