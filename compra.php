<?php include 'rest.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Tela de Compra</title>

		<link rel="stylesheet" type="text/css" href="css/index.css">
	</head>

	<header><a href="index.html"><img width="340" src="http://www.amanhaeuteconto.com.br/wp-content/uploads/2014/10/logo_decolar.png"></a></header>
	<body>

	<ul class="navigation-menu"> 
    <li class="navigation-menu-item"></li>   
  </ul>

	<div class="principal-compra">
		<?php
		if ($servico=='comprar_voo' || $servico=='comprar_hospedagem') {
			echo "<h4>$resposta</h4>";
		}
		else if (($servico=='consultar_voo' || $servico=='consultar_hospedagem') && empty($resposta)) {
			echo "<h4>Sua busca não trouxe resultados.</h4>";
		}
		?>
		<?php if (!empty($resposta) && $servico=='consultar_voo') { ?>
	
		<form action="compra.php" method="post">
			<table>
				<thead><tr>
					<th>Selecione</th>
					<th>Origem</th>
					<th>Destino</th>
					<th>Data</th>
					<th>Assentos disponíveis</th>
					<th>Preço</th>
				</tr></thead>
				<tbody>
					<?php
						$destino = filter_input(INPUT_POST, 'destino');
						foreach ($resposta as $key => $value) {
							$name = (($destino==$value->destino)?'ida':'volta');
							echo "<tr>";
							echo "<td><label>$name <input name=\"$name\" type=\"checkbox\" value=\"$value->id\"></label></td>";
							echo "<td>$value->origem</td>";
							echo "<td>$value->destino</td>";
							echo "<td>$value->data</td>";
							echo "<td>$value->assentos</td>";
							echo "<td>R$ $value->preco</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<div class="detalhes-compra detalhes-compra-voo">
			<label>Assentos adulto:<select name="assentos_adulto">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select></label>
			<label>Assentos criança:<select name="assentos_criança">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select></label>
			<label>Cartão de crédito:<input name="cartao" value="8888.9999.7777.5555"></label>
			<label>Parcelas:<select name="parcelas">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select></label>
			<input name="servico" value="comprar_voo" hidden="true">
			<button class="botao" type="submit">Comprar</button>
			</div>
		</form>

		<?php } ?>
		<?php if (!empty($resposta) && $servico=='consultar_hospedagem') { ?>
	
		<form action="compra.php" method="post">
			<table>
				<thead><tr>
					<th>Hospedagem</th>
					<th>Datas e quartos</th>
				</tr></thead>
				<tbody>
					<?php
						foreach ($resposta as $key => $value) {
							echo "<tr>";
							echo "<td><label>Destino: $value->destino, Diária: R$ $value->preco <input name=\"destino\" type=\"radio\" value=\"$value->id\"></label></td>";
							$datas = (array) $value->quartosPorData;
							echo "<td><label>Entrada: <select name=\"dataentrada\" disabled=\"true\">";
							foreach ($datas as $k => $v) {
								echo "<option value=\"$k\">Data: $k, Quartos: $v</option>";
							}
							echo "</select></label>";
							echo "<label>Saída: <select name=\"datasaida\" disabled=\"true\">";
							foreach ($datas as $k => $v) {
								echo "<option value=\"$k\">Data: $k, Quartos: $v</option>";
							}
							echo "</select></label></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<div class="detalhes-compra">
			<label>Quartos:<select name="quartos">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select></label>
			<label>Cartão de crédito:<input name="cartao" value="8888.9999.7777.5555"></label>
			<label>Parcelas:<select name="parcelas">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select></label>
			<input name="servico" value="comprar_hospedagem" hidden="true">
			<button class="botao" type="submit">Comprar</button>
			</div>
		</form>

		</div>

		<?php } ?>

		<script src="js/jquery-2.2.4.min.js"></script>
		<script src="js/index.js"></script>

	</body>
</html>