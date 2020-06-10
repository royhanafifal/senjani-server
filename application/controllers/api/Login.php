<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Login extends CI_Controller{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('Login_model', 'login');
    }

    public function register_post()
    {
        $data = [
            'nama_lengkap' => $this->post('nama_lengkap'),
            'email' => $this->post('email'),
            'password' => password_hash($this->post('password'), PASSWORD_DEFAULT, ['cost' => 10]),
            'alamat_text' => $this->post('alamat_text'),
            'no_hp_wa' => $this->post('no_hp_wa'),
            'alergi_makanan' => $this->post('alergi_makanan'),
            'sumber_informasi' => $this->post('sumber_informasi')

        ];

        if($this->login->create_pelanggan($data) > 0){
            $this->response([
                'status' => true,
                'message' => 'Pelanggan berhasil ditambahkan'
            ], 201);//HTTP_CREATED
        }else{
            $this->response([
                'status' => false,
                'message' => 'Gagal menambahkan data pelanggan baru'
            ], 400);//HTTP_NO_CONTENT
        }
    }

    public function index_get()
    {
        $email = $this->get('email');
        $password = $this->get('password');
        $pelanggan = $this->login->get_pelanggan($email);
        if($pelanggan){
            $pelanggan = $pelanggan[0];
            if(password_verify($password, $pelanggan['password'])){
                if($pelanggan['waktu_hapus'] === null){
                    $this->response([
                        'status' => true,
                        'message' => 'Data ditemukan',
                        'data' => $pelanggan
                    ], 200);//HTTP_OK
                }else{
                    $this->response([
                        'status' => false,
                        'message' => 'Pelanggan tidak aktif'
                    ], 302);//HTTP_FOUND
                }
            }else{
                echo 'password salah';
                $this->response([
                    'status' => false,
                    'message' => 'Password Salah'
                ], 302);//HTTP_FOUND
            }
        }else{
            $this->response([
                'status' => false,
                'message' => 'Data pelanggan tidak ditemukan'
            ], 404);//HTTP_NOT_FOUND
        }
    }
}