<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {


    public function __construct()
	{
		parent::__construct();
        $this->load->model('M_api');
    }

    //-------------------------------------- LOGIN ---------------------------------------------------  

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['user']) && isset($_POST['pass'])) {
                
                $user_login = $this->M_api->proses_login($_POST['user'], $_POST['pass']);
                $result['nik']   = null;

                if ($user_login->num_rows() == 1) {
                    $result['value'] = "1";
                    $result['pesan'] = "sukses login!";
                    $result['nik']   = $user_login->row()->nik;
                } else {
                    $result['value'] = "0";
                    $result['pesan'] = "username / password salah!";
                }
            } else {
                $result['value'] = "0";
                $result['pesan'] = "beberapa inputan masih kosong!";
            }
        } else {
            $result['value'] = "0";
            $result['pesan'] = "invalid request method!";
        }

        echo json_encode($result);
    }

    public function login_admin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['user']) && isset($_POST['pass'])) {
                
                $user_login = $this->M_api->proses_login_admin($_POST['user'], $_POST['pass']);
                $result['id_admin']   = null;

                if ($user_login->num_rows() == 1) {
                    $result['value'] = "1";
                    $result['pesan'] = "sukses login!";
                    $result['id_admin']   = $user_login->row()->id_admin;
                } else {
                    $result['value'] = "0";
                    $result['pesan'] = "username / password salah!";
                }
            } else {
                $result['value'] = "0";
                $result['pesan'] = "beberapa inputan masih kosong!";
            }
        } else {
            $result['value'] = "0";
            $result['pesan'] = "invalid request method!";
        }

        echo json_encode($result);
    }

    //-------------------------------------- REGISTER ---------------------------------------------------  

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['nik']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['cpass'])) {
                if ($this->M_api->cek_nik_register($_POST['nik'])->num_rows() == 0) {
                    $result['value'] = "0";
                    $result['pesan'] = "nik tidak terdaftar!";
                } else if ($this->M_api->cek_if_register($_POST['nik'])->num_rows() == 1) {
                    $result['value'] = "0";
                    $result['pesan'] = "nik sudah ter registrasi!";
                } else if ($this->M_api->cek_user_exist_register($_POST['user'])->num_rows() == 1) {
                    $result['value'] = "0";
                    $result['pesan'] = "username sudah terdaftar!";
                } else {
                    $this->M_api->proses_register();
                    $result['value'] = "1";
                    $result['pesan'] = "registrasi berhasil!";
                }
            } else {
                $result['value'] = "0";
                $result['pesan'] = "beberapa inputan masih kosong!";
            }            
        } else {
            $result['value'] = "0";
            $result['pesan'] = "invalid request method!";
        }

        echo json_encode($result);
    }



     //-------------------------------------- BY ID ---------------------------------------------------

    public function profile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result['hasil'] = null;

            if ($this->M_api->cek_nik_register($_POST['nik'])->num_rows() != 0) {

                $result['value'] = "1";
                $result['pesan'] = "response ok!";
                $result['hasil'] = [
                    'nik'   => $this->M_api->get_profile($_POST['nik'])->nik,
                    'nama'  => $this->M_api->get_profile($_POST['nik'])->nama,
                    'ttl'   => $this->M_api->get_profile($_POST['nik'])->tempat_lahir . ", " . $this->M_api->get_profile($_POST['nik'])->tanggal_lahir,
                    'jk'    => $this->M_api->get_profile($_POST['nik'])->jk == "L" ? "Laki-laki" : "Perempuan",
                    'agama' => $this->M_api->get_profile($_POST['nik'])->nama_agama
                ];
            }
        } else {
            $result['value'] = "0";
            $result['pesan'] = "invalid request method!";
        }

        echo json_encode($result);
    }


    public function readDataAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $result['hasil'] = null;

            if ($this->M_api->cek_nik_register($_POST['nik'])->num_rows() != 0) {

                $result['value'] = "1";
                $result['pesan'] = "response ok!";
                $result['hasil'] = [
                    'nik'   => $this->M_api->readData($_POST['nik'])->nik,
                    'nama'  => $this->M_api->readData($_POST['nik'])->nama
                ];
            }
        } else {
            $result['value'] = "0";
            $result['pesan'] = "invalid request method!";
        }

        echo json_encode($result);
    }

    //-------------------------------------- GET Recycler Penduduk ---------------------------------------------------

    public function recycler()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result['hasil'] = null;

            if ($this->M_api->cek_nik_register($_POST['nik'])->num_rows() != 0) {

                $result['value'] = "1";
                $result['pesan'] = "response ok!";
                $result['hasil'] = $this->M_api->get_aduan_nik($_POST['nik'], "0")->result_array();
                
            } else {
                $result['value'] = "0";
                $result['pesan'] = "nik tidak terdaftar!";
            }
        } else {
            $result['value'] = "0";
            $result['pesan'] = "invalid request method!";
        }

        echo json_encode($result);
    }

    //-------------------------------------- TAMBAH PENDUDUK ------------------------------------------------

    public function add_penduduk()
    {
                if (isset($_POST['nik'])) {
                    $this->M_api->addPenduduk($_POST['nik'], $_POST['nama'], $_POST['tempat_lahir'], $_POST['tanggal_lahir']);
                    $result['value'] = "1";
                    $result['pesan'] = "data penduduk berhasil disimpan!";
                } else {
                    $result['value'] = "0";
                    $result['pesan'] = "beberapa inputan masih kosong!";
                }

        echo json_encode($result);
    }


    //-------------------------------------- UBAH PENDUDUK ------------------------------------------------

    public function ubah_penduduk()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($this->M_api->cek_nik_register($_POST['nik'])->num_rows() != 0) {
                if (isset($_POST['nama'])) {
                    $this->M_api->ubahPenduduk($_POST['nik'], $_POST['nama'], $_POST['alamat']);
                    $result['value'] = "1";
                    $result['pesan'] = "Data Penduduk berhasil diubah!";
                } else {
                    $result['value'] = "0";
                    $result['pesan'] = "beberapa inputan masih kosong!";
                }
            } else {
                $result['value'] = "0";
                $result['pesan'] = "NIK Penduduk tidak tersedia!";
            }
        } else {
            $result['value'] = "0";
            $result['pesan'] = "invalid request method!";
        }

        echo json_encode($result);
    } 
      


    public function get_users()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $result['value'] = "1";
                $result['pesan'] = "response ok!";
                $result['hasil'] = $this->M_api->get_users()->result_array();
                
            } else {
                $result['value'] = "0";
                $result['pesan'] = "invalid request method!";
            }

        echo json_encode($result);
    }
}