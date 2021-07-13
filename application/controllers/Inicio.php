<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {


    public function index()
    {
        $dados['mensagem']='';
        $this->load->view('new_cakeDigital',$dados);
    }

    public function baseCharlie()
    {
        $dados['mensagem']='';
        $this->load->view('new_base_charlie',$dados);
    }

    public function primeiraCompra()
    {
        $dados['mensagem']='';
        $this->load->view('new_primeiracompra',$dados);
    }

    public function personalize()
    {
        $dados['mensagem']='';
        $this->load->view('new_personalize',$dados);
    }

    public function checkout()
    {
        $dados['mensagem']='';
        $this->load->view('new_checkout',$dados);
    }

    public function mensagem()
    {
        iniAjax();
        $dados = array (
            'nome' => $this->input->post('nome'),
            'email'=> $this->input->post('email'),
            'telefone' => $this->input->post('telefone'),
            'mensagem' => $this->input->post('mensagem'),
        );
        $this->db->insert('informacoes',$dados);
        $this->jphp->send();
    }

}
