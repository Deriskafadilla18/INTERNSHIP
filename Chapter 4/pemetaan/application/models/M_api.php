<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_api extends CI_Model {

    //-------------------------------------- LOGIN ---------------------------------------------------   

    public function proses_login($user, $pass)
    {
        return $this->db->query("SELECT nik FROM penduduk WHERE username = '$user' AND password = MD5('$pass')");
    }

    public function proses_login_admin($username, $password)
    {
        return $this->db->query("SELECT id_admin FROM admin WHERE username = '$username' AND password = MD5('$password')");
    }

}