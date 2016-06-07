<?php

	$servico = filter_input(INPUT_POST, 'servico');// consultar ou comprar (voos ou hospedagens).
	$resposta;

	require_once 'unirest-php/src/Unirest.php';// Biblioteca para requisições REST.

	// chamada dos métodos REST de acordo com o pedido.
	if ($servico=='consultar_voo') {
		$origem = filter_input(INPUT_POST, 'origem');
		$destino = filter_input(INPUT_POST, 'destino');
		$dataIda = filter_input(INPUT_POST, 'dataida');
		$dataVolta = filter_input(INPUT_POST, 'datavolta');
		$volta = filter_input(INPUT_POST, 'volta');
		$resposta = get_voo($origem, $destino, $dataIda, $dataVolta, $volta);
	}
	else if ($servico=='consultar_hospedagem') {
		$destino = filter_input(INPUT_POST, 'destino');
		$dataEntrada = filter_input(INPUT_POST, 'dataentrada');
		$dataSaida = filter_input(INPUT_POST, 'datasaida');
		$resposta = get_hospedagem($destino, $dataEntrada, $dataSaida);
	}
	else if ($servico=='comprar_voo') {
		$ida = filter_input(INPUT_POST, 'ida');
		$volta = filter_input(INPUT_POST, 'volta');
		$assentos_adulto = filter_input(INPUT_POST, 'assentos_adulto');
		$assentos_criança = filter_input(INPUT_POST, 'assentos_criança');
		$assentos = $assentos_adulto + $assentos_criança;
		$resposta = post_voo($ida, $volta, $assentos);
	}
	else if ($servico=='comprar_hospedagem') {
		$destino = filter_input(INPUT_POST, 'destino');
		$dataEntrada = filter_input(INPUT_POST, 'dataentrada');
		$dataSaida = filter_input(INPUT_POST, 'datasaida');
		$quartos = filter_input(INPUT_POST, 'quartos');
		$resposta = post_hospedagem($destino, $dataEntrada, $dataSaida, $quartos);
	}

	/**
	 * Faz um requisição REST, utilizando o método GET, para consultar os voos disponíveis.
	 * A resposta da requisição é um JSON, mas o método retorna um array associativo.
	 * @param  String
	 * @param  String
	 * @param  String
	 * @param  String
	 * @param  integer
	 * @return Array
	 */
	function get_voo($origem, $destino, $dataIda=null, $dataVolta=null, $volta=1) {
		
		$headers = array('Accept' => 'application/json');
		$query = array_filter(array("origem" => $origem, "destino" => $destino,
					   "dataida" => $dataIda, "datavolta" => $dataVolta, "volta" => $volta));

		// requisição REST/GET
		$response = Unirest\Request::get('http://localhost:8080/ServidorREST/rest/serv/consultarvoo', $headers, $query);

		// se houve algum erro, retorna um array vazio.
		if ($response->code==400) {
			return array();
		}
		// retorna o JSON como array.
		return (array) $response->body->voos;
	}
	
	/**
	 * Faz um requisição REST, utilizando o método POST, para comprar os voos selecionados.
	 * @param  String
	 * @param  String
	 * @param  integer
	 * @return String
	 */
	function post_voo($ida, $volta=null, $assentos=0) {

		$headers = array('Accept' => 'application/json');
		$query = http_build_query(array_filter(array("ida" => $ida, "volta" => $volta, "assentos" => $assentos)));

		// Requisição REST/POST
		$response = Unirest\Request::post("http://localhost:8080/ServidorREST/rest/serv/comprarvoo?$query", $headers);

		// Tratamento da resposta adequada.
		if ($response->code==400) {
			return "Não foi possível comprar. Alguns dos valores podem estar incorretos.";
		}
		else if ($response->code==404) {
			return "Não foi possível comprar. O voo não existe ou não há mais assentos disponíveis.";
		}
		return "Sua compra foi realizada com sucesso.";
	}

	/**
	 * Faz um requisição REST, utilizando o método GET, para consultar as hospedagens disponíveis.
	 * A resposta da requisição é um JSON, mas o método retorna um array associativo.
	 * @param  String
	 * @param  String
	 * @param  String
	 * @return array
	 */
	function get_hospedagem($destino, $dataEntrada=null, $dataSaida=null) {
		
		$headers = array('Accept' => 'application/json');
		$query = array_filter(array("destino" => $destino, "dataentrada" => $dataEntrada, "datasaida" => $dataSaida));

		// Requisição REST/GET
		$response = Unirest\Request::get('http://localhost:8080/ServidorREST/rest/serv/consultarhospedagem', $headers, $query);

		// Se houve algum erro retorna um array vazio
		if ($response->code==400) {
			return array();
		}
		// Retorna o JSON como array
		return $response->body->hospedagens;
	}

	/**
	 * Faz um requisição REST, utilizando o método POST, para comprar as hospdagens selecionadas.
	 * @param  String
	 * @param  String
	 * @param  String
	 * @param  integer
	 * @return String
	 */
	function post_hospedagem($destino, $dataEntrada, $dataSaida, $quartos=0) {

		$headers = array('Accept' => 'application/json');
		$query = http_build_query(array_filter(array('destino' => $destino,
			'dataentrada' => $dataEntrada, 'datasaida' => $dataSaida, 'quartos' => $quartos)));

		// Requisição REST/POST
		$response = Unirest\Request::post("http://localhost:8080/ServidorREST/rest/serv/comprarhospedagem?$query", $headers);

		// Tratamento para a resposta adequada
		if ($response->code==400) {
			return "Não foi possível comprar. Alguns dos valores podem estar incorretos.";
		}
		else if ($response->code==404) {
			return "Não foi possível comprar. A hospedagem não existe ou não há mais quartos disponíveis.";
		}
		return "Sua compra foi realizada com sucesso.";
	}

