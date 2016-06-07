<?php

	include 'rest.php';

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Tela de Compra</title>
	</head>
	<body>

		<p><a href="index.html">Voltar</a></p>

		<?php
		if ($servico=='comprar_voo' || $servico=='comprar_hospedagem') {
			echo "<h4>$resposta</h4>";
		}
		else if (($servico=='consultar_voo' || $servico=='consultar_hospedagem') && empty($resposta)) {
			echo "<h4>Sua busca não trouxe resultados</h4>";
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
			<label>Assentos adulto<select name="assentos_adulto">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select></label>
			<label>Assentos criança<select name="assentos_criança">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select></label>
			<label>Cartão de crédito<input name="cartao" value="8888.9999.7777.5555"></label>
			<label>Parcelas <select name="parcelas">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select></label>
			<input name="servico" value="comprar_voo" hidden="true">
			<button type="submit">Comprar</button>
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
			<label>Quartos<select name="quartos">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select></label>
			<label>Cartão de crédito<input name="cartao" value="8888.9999.7777.5555"></label>
			<label>Parcelas <select name="parcelas">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
			</select></label>
			<input name="servico" value="comprar_hospedagem" hidden="true">
			<button type="submit">Comprar</button>
		</form>

		<?php } ?>

		<script src="js/jquery-2.2.4.min.js"></script>
		<script>
		
		$('td input').on('click', function(event){
			var tr = $(this).parent().parent().parent();	
			tr.find('select').each(function(index){
				$(this).prop('disabled', false);
			});
			tr.siblings().find('select').each(function(index){
				$(this).prop('disabled', true);
			});
		});

		$("input:checkbox").on('click', function() {
			var $box = $(this);
		  	if ($box.is(":checked")) {
		    	var group = "input:checkbox[name='" + $box.attr("name") + "']";
		    	$(group).prop("checked", false);
		    	$box.prop("checked", true);
		  	} else {
		    	$box.prop("checked", false);
		  	}
		});

		</script>

	</body>
</html>