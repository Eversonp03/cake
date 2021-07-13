<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		iniAjax();
	}

	public function envcupom()
	{
		header('Access-Control-Allow-Origin: *');
		$this->load->helper("email");
		$emailcliente = $this->input->post("email");
		$shop = $this->input->post("shop");
		if ($emailcliente != "") {
			$existe = $this->db->get_where("costumers", ["email" => addslashes($emailcliente), "total_spent >" => 0, "shop" => $shop])->row_array();
			if (!$existe) {
				$dadosModal = $this->db->get_where("modal_cupom", array("shop" => addslashes($shop)))->row_array();
				$cupom = $this->db->get_where("cupons", array("id_cupom" => $dadosModal['id_cupom']))->row_array();
				$dadosUser = $this->db->get_where("integracoes", ['user_token' => $shop])->row_array();
				$this->db->insert("costumers", ["email" => $emailcliente, "shop" => $shop, "user" => $dadosUser['user'], "apenaslead" => 1, "inclusao" => time()]);
				$imgEmail = "<img src='" . $dadosModal['imgEmail'] . "'>";
				enviaEmail(array($emailcliente, $emailcliente), array("mensagem" => str_replace(["{cupom}", "{imagem}"], [$cupom["code"], $imgEmail], $dadosModal['html']), "assunto" => $dadosModal['assunto']), 'SUPORTE');
				echo json_encode(["sucesso" => 1, "cupom" => $cupom["code"], "msg" => "sucesso"]);
			} else {
				echo json_encode(["sucesso" => 0, "msg" => "E-mail já cadastrado e com a primeira compra já realizada."]);
			}
		} else {
			echo json_encode(["sucesso" => 0, "msg" => "O campo e-mail é obrigatório"]);
		}
	}

	public function verificaCupom()
	{
		header('Access-Control-Allow-Origin: *');
		$cupom = $this->input->post("cupom");
		$loja = $this->input->post("loja");
		$cpf = preg_replace("/[^0-9]/", "", $this->input->post("cpf"));
		$email = $this->input->post("email");
		$retorno = "erro";
		$dadosLoja = $this->db->get_where("integracoes", ["id" => $loja])->row_array();
		$cupom = $this->db->get_where("cupons", ['code' => $cupom, 'user' => $dadosLoja['user']])->row_array();
		file_put_contents('verCupom.txt', print_r([$cupom, $dadosLoja, $cupom, $email, $cpf], true) . "\r\n", FILE_APPEND);
		if ($cupom['primeiracompra']) {
			if ($cpf != "" || $email != "") {
				$loja = addslashes($loja);

				$this->db->where("apenaslead", 0);
				$this->db->where("total_spent >", 0);
				$this->db->where("user", $dadosLoja['user']);
				$this->db->group_start();
				if ($cpf != "") {
					$this->db->where("documento", $cpf);
				}
				if ($email != "") {
					$this->db->or_where("email", $email);
				}
				$this->db->group_end();
				$jausado = $this->db->get("costumers")->result_array();

				if ($jausado) {
					$retorno = "invalido";
				} else {
					$retorno = "00";
				}
			} else {
				$retorno = "dados";
			}
		} elseif ($cupom['compraunica']) {
			if ($cpf != "" || $email != "") {
				$this->db->where("user", $dadosLoja['user']);
				$this->db->group_start();
				if ($cpf != "") {
					$this->db->where("documento", $cpf);
				}
				if ($email != "") {
					$this->db->or_where("email", $email);
				}
				$this->db->group_end();
				$costumer = $this->db->get("costumers")->row_array();
				$jausou = $this->db->get_where("compraunica_usuario",['id_costumer'=>$costumer['id'],'id_cupom'=>$cupom['id'],'user'=>$dadosLoja['user']])->row_array();
				if(!$jausou){
					$retorno = "00";
				}else{
					$retorno = "invalido";
				}
			}else {
				$retorno = "dados";
			}
		} else {
			$retorno = "00";
		}

		echo $retorno;
	}

	public function testeUpdate()
	{
		$dados = array(
			"store_id" => 1338317,
			"event" => "order/updated",
			"id" => 290280913
		);
		echo $env = carregar("https://cupom.cakedigital.com.br/Ajax/updateCliente/1", $dados);
	}

	public function updateCliente($user)
	{
		$this->load->model("Integracao_model", "Integracao");
		$this->load->model("Cupons_model", "Cupons");
		$dados = file_get_contents('php://input');
		$dados = json_decode($dados, true);


		$pedido = $this->Integracao->getOrder("nuvemshop", $user, $dados['id']);
		//file_put_contents('orderWeb.txt', print_r($pedido, true) . "\r\n", FILE_APPEND);
		$this->Cupons->updateCostumerCupom($user, $pedido);
		//file_put_contents('orderWeb.txt', print_r(['pedido' => $pedido, 'user' => $user], true) . "\r\n", FILE_APPEND);		
	}

	public function scriptcupom($id)
	{
		$idStore = $this->input->get("store");
		header("Content-Type: text/javascript");

		echo file_get_contents('https://cakedigital.s3-sa-east-1.amazonaws.com/cupom.js');
	}

	public function sincronizaCupons()
	{
		$this->load->model("Cupons_model", "Cupom");
		$this->load->library('Login', array('login' => true), 'login');
		$dadosUser = $this->login->usuario;

		$result = $this->Cupom->gerenciaCupom($dadosUser);

		if ($result['sucesso']) {
			$this->jphp->sucesso("Foram cadastrados " . $result['cadastrados'] . " e atualizados " . $result['atualizados'] . " cupons.");
			$this->jphp->redirect();
		} else {
			$this->jphp->alert("Houve algum erro.");
		}
		$this->jphp->send();
	}

	public function sincronizaCostumers()
	{
		$this->load->model("Cupons_model", "Cupom");
		$this->load->library('Login', array('login' => true), 'login');
		$dadosUser = $this->login->usuario;

		$result = $this->Cupom->gerenciaCostumers($dadosUser);
		if ($result['sucesso']) {
			$this->jphp->sucesso("Foram cadastrados " . $result['cadastrados'] . " e atualizados " . $result['atualizados'] . " clientes.");
			$this->jphp->redirect();
		} else {
			$this->jphp->alert("Houve algum erro.");
		}
		$this->jphp->send();
	}

	public function cupomPrimeiraCompra($id)
	{
		$this->load->library('Login', array('login' => true), 'login');
		$dadosUser = $this->login->usuario;
		//$primeiracompra = $this->input->post("campo");
		$valor = $this->input->post("valor");
		if ($dadosUser) {
			$this->db->update("cupons", array("primeiracompra" => $valor, "compraunica" => 0), array("id_cupom" => $id));
			$msg = "Cupom primeira compra desativado";
			if ($valor) {
				$msg = "Cupom primeira compra ativado";
			}
			$this->jphp->sucesso($msg);
		}
		$this->jphp->send();
	}

	public function cupomUnicaCompra($id)
	{
		/* Informa o nível dos erros que serão exibidos */
	
		$this->load->library('Login', array('login' => true), 'login');
		$dadosUser = $this->login->usuario;
		//$primeiracompra = $this->input->post("campo");
		$valor = $this->input->post("valor");
		if ($dadosUser) {
			$this->db->update("cupons", array("compraunica" => $valor, "primeiracompra" => 0), array("id_cupom" => $id));
			$msg = "Cupom unica compra desativado";
			if ($valor) {
				$msg = "Cupom unica compra ativado";
			}
			$this->jphp->sucesso($msg);
		}
		$this->jphp->send();
	}

	public function login()
	{
		$this->load->library('Login', NULL, 'login');
		if ($this->login->usuario) {
			$this->jphp->redirect(base_url());
		} else {
			$msg = $this->login->getMensagem();
			$this->jphp->alert($msg);
		}
		$this->jphp->send();
	}

	public function sair()
	{
		$this->load->library('Login', NULL, 'login');
		$this->login->deslogar();
		$this->jphp->redirect('self');
		$this->jphp->send();
	}

	public function salvaImgS3($arquivo)
	{

		//S3 Amazon Config
		$this->load->helper('upload');
		//		$mimeType = array(
		//			'css' => 'text/css',
		//			'js' => 'text/javascript',
		//			'htm' => 'text/html',
		//			'html' => 'text/html',
		//			'png' => 'image/png',
		//			'jpg' => 'image/jpeg',
		//			'pdf' => 'application/pdf',
		//		);
		$mimeType = 'image/jpeg';
		//$tipo = strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION));
		$tipo = 'jpg';
		//S3 Amazon Config Fim
		$caminho = 'perfil_montink/' . uniqid(time()) . '.' . $tipo;
		$retorno = putstrS3($caminho, file_get_contents($arquivo), $mimeType);

		return $retorno;
	}
}
