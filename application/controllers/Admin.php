<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function index()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else
			$this->load->view('admindex');
	}

	public function login()
	{
		$user = $_POST['user'];
		$senha = $_POST['senha'];
		$_SESSION['useradm'] = '';
		if ($user == 'andreas' || $user == 'bruno' || $user == 'suporte') {
			if ($senha == 'Sucesso*1$0' || 'Suporte@19') {
				$_SESSION['logadm'] = true;
				$_SESSION['useradm'] = $user;
			} else {
				$_SESSION['logadm'] = false;
			}
		} else {
			$_SESSION['logadm'] = false;
		}
		if ($_SESSION['logadm']) {
			redirect("/Admin/index");
		} else {
			$this->load->view('admlogin');
		}
	}

	public function deslogar()
	{
		$_SESSION['logadm'] = false;
		redirect("/Admin/login");
	}

	public function ver($user)
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			$email = $this->db->get_where('usuario', array('user' => $user))->row_array();
			$d = array();
			$d['loja'] = $email;

			if ($_POST['salvar'] == 1) {
				
				if ($_POST['addVenc']  != '') {
					
					if ($email['credVencimento'] < 1) {
						$venc = strtotime("+$_POST[addVenc] $_POST[addTipo]", time());
					} else {
						$venc = strtotime("+$_POST[addVenc] $_POST[addTipo]", $email['credVencimento']);
					} 
					if ($this->db->update('usuario', array('credVencimento' => $venc), array('user' => $user))) {
						$d['sucesso'] = "vencimento em " . date('d/m/Y', $venc);
					} else {
						$d['alerta'] = "erro ao add credito";

					}
				}
				$this->db->update('usuario', array(
					'cortesiaMensal' => $_POST['cortesiaMensal']
				), array('user' => $user));


				if ($_POST['ultimaCortesia'] == 1) {
					$this->db->update('usuario', array(//app_guests
						'ultimaCortesia' => time(),
						'credGratis' => $_POST['cortesiaMensal'],
						'vencimento' => strtotime('+1 month')
					), array('user' => $user));
				}
				if ($_POST['addCredito'] != '') {
					$this->db->update('usuario', array(
						'credAtual' => $_POST['addCredito'] + $email['credAtual']
					), array('user' => $user));
				}
				if ($_POST['planoAtual'] != '') {
					$this->db->update('usuario', array(
						'planoAtual' => $_POST['planoAtual']
					), array('user' => $user));
				}
				if ($_POST['addCortesia'] != '') {
					$this->db->update('usuario', array(
						'credGratis' => $_POST['addCortesia'] + $email['credGratis']
					), array('user' => $user));
				}
				file_put_contents('log/adminUpdate.txt', json_encode(['moderador' => $_SESSION['useradm'], 'USER' => $user, 'POST' => $_POST, 'sucesso' => $d['sucesso'], 'alerta' => $d['alerta']]) . "\r\n", FILE_APPEND);
				$d['loja'] = $this->db->get_where('usuario', array('user' => $user))->row_array();
			}
			$this->load->view('admver', $d);
		}
	}

	public function listar()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			$this->load->view('admlistar');
		}
	}

	public function fornecedores()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			$this->load->view('admfornecedores');
		}
	}

	public function debug()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			$this->load->view('admindebug');
		}
	}

	public function produtos()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			$this->load->view('adminprodutos');
		}
	}

	public function pedidos()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			$this->load->model('Lumise_model', 'Lumise');
			$status = '';

			if (isset($_GET['status'])) {
				$status = $_GET['status'];
			}
			$indice = '';
			$busca = '';

			if (isset($_GET['tipoFiltro']) && isset($_GET['buscapor'])) {
				$indice = $_GET['tipoFiltro'];
				$busca = $_GET['buscapor'];
			}

			$d['limitePagina'] = 30;
			$d['pgAtual'] = (isset($_GET['pag']) ? $_GET['pag'] : 0);
			$d['dadosDimona'] = json_decode(carregar2("https://camisadimona.com.br/api/v2/order/$id", null, null, 'rlhgeQmzfeONGnBWn7QaS0m8ENgZCvps'), true);
			$d['lista'] = $this->Lumise->getAllPedidosAdmin($status, $_GET['pag'], false, $d['limitePagina'], $indice, $busca);
			$d['totalItens'] = count($this->Lumise->getAllPedidosAdmin($status, '', true, '', $indice, $busca));
			$this->load->view('adminpedidos', $d);
		}
	}

	public function abreDetalhes($id)
	{
		iniAjax();
		$this->db->select('*');
		$this->db->where('id_order', $id);
		$query = $this->db->get('pedidos');
		$result = $query->row_array();

		if (is_array($result['dadosEnvio'])) {
			$d['detalhes'] = $result['dadosEnvio'];
		} else {
			$d['detalhes'] = json_decode($result['dadosEnvio'], true);
		}
		$d['frete'] = json_decode($result['shipping_lines'], true);
		$d['id_pedido'] = $result['id_pedido'];
		$d['comentario'] = $result['comentario'];

		$pagina = $this->load->view('admdetalhespedido', $d, true);
		$pagina = str_replace(array("\r", "\n", "\t"), " ", $pagina);

		$this->jphp->replace('#detalhesPedido .modal-content', $pagina);
		$this->jphp->executa("$('#detalhesPedido').modal('show');");

		$this->jphp->send();
	}

	public function salvaComentario($id)
	{
		iniAjax();
		$d['comentario'] = $this->input->post('comentario');

		$this->db->update('pedidos', $d, array('id_pedido' => $id));

		$this->jphp->send();
	}

	public function salvaDias()
	{
		iniAjax();
		$d['diasEnvio'] = $this->input->post('diasEnvio');

		$this->db->update('fornecedor_usuario', $d, array('user' => $this->input->post('id')));
		$this->jphp->redirect();
		$this->jphp->send();
	}

	public function pagamentos($shop)
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {

			$query = $this->db->get_where('usuario', array('shop' => $shop));
			$row = $query->row_array();

			$url = "https://" . $row['shop'] . "/admin/recurring_application_charges.json";

			$pagamento = carregar($url, null, $row['access_token']);

			$this->load->view('admpagamento', array('pagamento' => $pagamento));
		}
	}

	public function cortesia()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			if (trim($_POST['url']) != '') {
				$this->db->insert('cortesia', array(
					'url' => trim($_POST['url']),
					'inclusao' => time(),
					'quem' => $_SESSION['useradm']
				));
			}
			$this->load->view('admcortesia');
		}
	}

	public function notificacao()
	{
		if (!$_SESSION['logadm'])
			redirect("/Admin/login");
		else {
			if (trim($_POST['texto']) != '') {
				$plataformas = array(
					'dropnacional' => array('tabelauser' => 'app_guests', 'tabelanotificacao' => 'notificacoes'),
					'montink' => array('tabelauser' => 'montink_usuario', 'tabelanotificacao' => 'montink_notificacoes'),
				);
				$this->db->insert($plataformas[$_POST['plataforma']]['tabelanotificacao'], array(
					'texto' => $_POST['texto'],
					'data' => dataInt($_POST['data']),
					'tipo' => $_POST['tipo']
				));
				$this->db->update($plataformas[$_POST['plataforma']]['tabelauser'], array(
					'notificacao' => 1,
				));
				redirect(base_url('Admin/notificacao'));
			}

			$this->db->select('*');
			$query = $this->db->get('notificacoes');

			$d['lista'] = $query->result_array();

			$this->load->view('adminnotificacao', $d);
		}
	}

	public function deletaNotificacao($id)
	{
		if (!$_SESSION['logadm']) {
			redirect("/Admin/login");
			exit();
		}
		$this->db->delete('notificacoes', array('id_notificacao' => $id));
		redirect(base_url('Admin/notificacao'));
	}

	public function removercortesia($url)
	{
		if (!$_SESSION['logadm']) {
			redirect("/Admin/login");
			exit();
		}
		$this->db->delete('cortesia', array('url' => $url));
		redirect("/Admin/cortesia");
	}

	public function moderaCatalogo()
	{
		if (!$_SESSION['logadm']) {
			redirect("/Admin/login");
			exit();
		}
		global $categorias;

		$cat = '';
		$title = '';
		$favTrue = false;

		if (isset($_GET['title'])) {
			$title = $_GET['title'];
		};

		if (isset($_GET['fornecedor'])) {
			$fornecedor = $_GET['fornecedor'];
		};


		$this->load->model('Lumise_model', 'Lumise');
		$this->load->model('Pagamentos_model', 'Pagamentos');


		$use = '';

		if (isset($_GET['cat'])) {

			$cat = $_GET['cat'];
		};

		$cartoes = array();
		$pedidos = array();
		$d = array();

		$pga = $_GET['pga'];

		if (isset($_GET['produto'])) {
			$produto = $_GET['produto'];
		} else {
			$produto = '';
		}

		$d['itensPorPagina'] = 50;
		$d['produtos'] = $this->Lumise->getProdutosFornecedor($cat, $title, $pga, $fornecedor, false, $favTrue, $use, true);
		$d['contPg'] = count($this->Lumise->getProdutosFornecedor($cat, $title, '', $fornecedor, true, $favTrue, $use, true));
		$d['contaTodos'] = count($this->Lumise->getProdutosFornecedor('', '', '', '', true));
		$produtosCadas = $this->Lumise->getProdutosImportados($dadosLogin['shop']);
		$d['cadastrado'] = array();
		foreach ($produtosCadas as $row) {

			$d['cadastrado'][$row['product_base']] = $row['product_base'];
		};

		$favoritos = $this->Lumise->getFavoritos($dadosLogin['id']);
		$contCategorias = $this->Lumise->contaCategorias();

		$d['contCats'] = array();
		foreach ($contCategorias as $v) {



			$d['contCats'][$v['indice']] = $v['totalC'];
		}

		//echo "<pre>";
		//print_r($d['produtos']);



		$descontos = $this->Lumise->getDescontoFornecedor();



		$d['favoritos'] = array();

		foreach ($favoritos as $fav) {

			$d['favoritos'][$fav['id_produto_fornecedor']] = $fav['id_produto_fornecedor'];
		}



		$d['descontos'] = array();

		foreach ($descontos as $row) {

			//$d['descontos'][$row['fornecedor']][$row['colecao']]=$row['desconto'];



			if ($row['handle'] == 'Todas' . $row['fornecedor']) {

				$d['descontos'][$row['fornecedor']]['todas'] = $row['desconto'];
			} else {



				$d['descontos'][$row['fornecedor']][$row['id_colecao']] = $row['desconto'];
			}
		};





		$d['pagina'] = 'catalogo';

		$d['icone'] = 'zmdi zmdi-label-alt zmdi-hc-fw';

		$d['page'] = 'pageModera';

		$d['categorias'] = $categorias;



		$this->load->view('newindexext', $d);
	}

	public function moderaProduto($id)
	{
		iniAjax();
		if (!$_SESSION['logadm']) {
			redirect("/Admin/login");
			exit();
		}
		if ($_POST['valor'] == 1) {
			$d['status'] = 0;
		} else {
			$d['status'] = 1;
		}

		$this->db->update('produtos_fornecedor', $d, array('id_produto_fornecedor' => $id));

		$this->jphp->send();
	}

	public function verificaAtraso()
	{
		$this->db->select('*');
		$query = $this->db->get('pedidos');
		$lista = $query->result_array();

		foreach ($lista as $row) {
			$diasEntrega = diferencaDias(dataShopify($row['data']), convertDataSemHora(time()));
			if ($diasEntrega > 5 && $row['id_status_pedido'] < 5 && $row['status'] == 'pago') {
				enviaEmail('desenvolvimento2@anverso.net.br', "O pedido $row[id_fornecedor] está com o prazo de envio atrasado, favor Verificar. Data do Pedido " . dataShopify($row['data'] . ' - Loja: ' . $row['shop']));
			}
		}
	}

	public function atualizaPedido($id)
	{
		iniAjax();
		//$url="https://camisadimona.com.br/api/v2/order/$id/tracking";
		//$id = str_replace('-', '', $id);
		$retorno = carregar2("https://camisadimona.com.br/api/v2/order/$id", null, null, 'rlhgeQmzfeONGnBWn7QaS0m8ENgZCvps');

		echo "<pre>";
		print_r(json_decode($retorno, true));


		$this->jphp->send();
	}

	public function showOrder($id)
	{
		$url = "https://camisadimona.com.br/api/v2/order/$id/tracking";
		//$id = str_replace('-', '', $id);
		$retorno = carregar2($url, null, null, 'rlhgeQmzfeONGnBWn7QaS0m8ENgZCvps');

		echo "<pre>";
		print_r(json_decode($retorno, true));
	}

	public function salvaCores()
	{
		iniAjax();
		$cores = $this->input->post('cores');
		$atualiza_cores = array();
		foreach ($cores as $id => $cor) {
			foreach ($cor as $co => $set) {
				$atualiza_cores[$id][$co] = 1;
			}
		}

		$query = $this->db->query("select * from app_products");
		$res = $query->result_array();

		foreach ($res as $prod) {
			$this->db->update('app_products', array('controle_cores' => json_encode((isset($atualiza_cores[$prod['id']]) ? $atualiza_cores[$prod['id']] : array()))), array('id' => $prod['id']));
		}

		$this->jphp->alert('Dados Alterados com sucesso!');
		$this->jphp->send();
	}

	public function consultaEndereco()
	{
		$ids = str_replace(' ', '', trim(addslashes(urldecode($this->input->get('ids')))));
		if ($ids != '') {
			$ids = explode(',', $ids);

			foreach ($ids as $id) {
				$this->db->or_where('id_fornecedor', $id);
			}
			$d['pedidos'] = $this->db->get('pedidos')->result_array();
		}
		$this->load->view('admdimona', $d);
	}
}

///admin/recurring_application_charges.json
//{
//  "recurring_application_charge": {
//    "name": "Super Duper Plan",
//    "price": 10.0,
//    "return_url": "http://super-duper.shopifyapps.com"
//  }
//}