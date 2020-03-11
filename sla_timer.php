<?php

try{
	
	$feriados = array(
		"01-01-2020",
		"24-02-2020",
		"25-02-2020",
		"26-02-2020",
		"10-04-2020",
		"20-04-2020",
		"21-04-2020",
		"01-05-2020",
		"11-06-2020",
		"12-06-2020",
		"07-09-2020",
		"12-10-2020",
		"19-10-2020",
		"02-11-2020",
		"15-11-2020",
		"20-11-2020",
		"25-12-2020"
	);
	
	if(isset($_POST["data_inicial"]) && isset($_POST["sla"]) && isset($_POST["sla_opt"])){
		$data_inicial = $_POST["data_inicial"];
		$sla = $_POST["sla"];
		$sla_opt = $_POST["sla_opt"];
		
		$periodos = array(
			0 => array(
				// array(
					// "inicio" => 0,
					// "fim" => 0
				// ) //set_auto
			),
			1 => array(
				// array(
					// "inicio" => 0,
					// "fim" => 0
				// ) //set_auto
			),
			2 => array(
				// array(
					// "inicio" => 0,
					// "fim" => 0
				// ) //set_auto
			),
			3 => array(
				// array(
					// "inicio" => 0,
					// "fim" => 0
				// ) //set_auto
			),
			4 => array(
				// array(
					// "inicio" => 0,
					// "fim" => 0
				// ) //set_auto
			),
			5 => array(
				// array(
					// "inicio" => 0,
					// "fim" => 0
				// ) //set_auto
			),
			6 => array(
				// array(
					// "inicio" => 0,
					// "fim" => 0
				// ) //set_auto
			)
		);
		
		for($i = 0; $i<=40; $i++){
			if(isset($_POST["weekday".$i])){
				$weekday = $_POST["weekday".$i];
				$h_init = $_POST["h_init_".$i];
				$m_init = $_POST["m_init_".$i];
				$h_end = $_POST["h_end_".$i];
				$m_end = $_POST["m_end_".$i];
				$time_init = $h_init*60 + $m_init;
				$time_end = $h_end*60 + $m_end;
				set_worktime($weekday, $time_init, $time_end, $i);
			}
		}
		
		$data_fim = calcular($data_inicial, $sla_opt, $sla);
		
	}
	else{
		$data_inicial = "";
		$sla = "";
		$sla_opt = "";
	}
}
catch(Exception $e){
	echo $err = "Erro: ".$e->getMessage()."<br>";
}


function calcular($data_inicial, $sla_opt, $sla){
	//Transformar em minutos
	$tempo = ($sla_opt == "h") ? $sla*60 : $sla;
	$data_fim = iterate_worktime($data_inicial, $tempo);
	return date("d/m/Y H:i:s", strtotime($data_fim));
}

function iterate_worktime($date, $time){
	
	global $feriados;
	
	while($time > 0){
		
		$string_date = date('d-m-Y', strtotime($date));

		foreach($feriados as $feriado){
			if($feriado == $string_date){
				$minutes = date('i', strtotime($date));
				$hours = date('H', strtotime($date));
				$current = 1440 - (intval($minutes) + intval($hours)*60);				
				$date = date("d-m-YTH:i:s", strtotime('+'.$current.' minutes', strtotime($date)));
			}
		}
		
		$weekday = get_weekday($date);
		$worktime = get_worktime($weekday);
		
		$minutes = date('i', strtotime($date));
		$hours = date('H', strtotime($date));
		$current = intval($minutes) + intval($hours)*60;
		
		foreach($worktime as $weekday_iterator){
			if(($weekday_iterator['fim'] - $weekday_iterator['inicio']) > 0){
				$fim = $weekday_iterator['fim'];
				$inicio = $weekday_iterator['inicio'];
			}
			else{
				continue;
			}
			
			if($current >= $inicio && $current < $fim){
				$time--;
			}
		}
		
		$date = date("d-m-YTH:i:s", strtotime('+1 minutes', strtotime($date)));
	}
	return $date;	
}

function get_weekday($date){
	$weekday = date('w', strtotime($date));
	$weekday = ($weekday > 0) ? $weekday-1 : 6;
	return $weekday;
}

function set_worktime($weekday, $time_init, $time_end, $i){
	$array = array(
		'inicio' => $time_init,
		'fim' => $time_end,
		'position' => $i
	);
	array_push($GLOBALS['periodos'][$weekday], $array);
}

function get_worktime($index){
	return $GLOBALS['periodos'][$index];
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>SLA TIMER</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	</head>
	<style>
		.main{
			width: 480px;
			height: 650px;
			overflow: hidden;
			border: 2px solid;
			float: left;
		}
		
		.periodos{
			width: 800px;
			height: 650px;
			overflow: hidden;
			border: 2px solid;
			float: right;
		}
		
		p#header{
			margin-top: 3px;
			border: 1px solid;
			font-weight: bold;
		}
		p#alert{
			color: red;
			font-weight: bold;
			display: none;
		}
		.field{
			width: 90%;
			float: left;
		}
		.content{
			margin: 0;
			padding-bottom: 2px;
			border: 1px solid;
		}
		input#submit{
			width: 90%;
			height: 30px;
			text-align: center;
			font-weight: bold;
			margin-top: 4px;
			float: left;
			margin-top: 4px;
			border-radius: 4px;
		}
		.campo{
			margin-bottom: 12%;
			padding: 1%;
		}
		
		.result{
			margin-top: 22%;
			margin-bottom: 12%;
			padding: 1%;
		}
		fieldset{
			margin: 20px;
			padding: 20px;
		}
		
		.container{
			height: 280px;
			width: 850px;
			
		}
		
		#addPeriod{
			margin-left: 50%; 
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 5px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 12px;
			cursor: pointer;
			width: 15px;
		}
		
		.removePeriod{
			margin-left: 20%; 
			background-color: red;
			border: 1px solid;
			border-radius: 4px;
			color: white;
			padding: 5px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 12px;
			cursor: pointer;
			width: 15px;
		}
		
		.header{
			font-weight: bold;
			margin-bottom: 10px;
		}
		
		.instances{
			white-space: nowrap;
			margin-left: 1%;
			overflow: auto;
		}
		
		#customers {
		  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		  border-collapse: collapse;
		  width: 60%;
		}

		#customers td, #customers th {
		  border: 1px solid #ddd;
		  padding: 4px;
		}

		#customers tr:nth-child(even){background-color: #f2f2f2;}

		#customers tr:hover {background-color: #ddd;}

		#customers th {
		  padding-top: 6px;
		  padding-bottom: 6px;
		  text-align: left;
		  background-color: #4CAF50;
		  color: white;
		}
		
	</style>
	<body>
		<form method="POST" action="">
			<fieldset class="main">
				<legend>Calcular</legend>
				<center><p id="header">SLA TIMER</p></center>
				<fieldset class="content">
					
					<p id="alert">Preencha todos os campos.</p>
					
						<div class="campo">
							<label for="data_inicial">Data Inicial:</label>
							<input type="datetime-local" id="data_inicial" value="<?= isset($data_inicial) ? $data_inicial : null ?>" class="field" name="data_inicial"></input>
						</div>
						
						<div class="campo">
							<label for="sla">SLA:</label>
							<input type="radio" value="h" <?= (isset($sla_opt) && $sla_opt == "h") ? "checked" : null?> name="sla_opt">Horas</input>
							<input type="radio" value="m" <?= (isset($sla_opt) && $sla_opt == "m") ? "checked" : null?> name="sla_opt">Minutos</input><br>
							<input type="number" id="sla" value="<?= (isset($sla)) ? $sla : null?>" class="field" name="sla"></input>
						</div>
						
						<div class="campo">
							
							<input type="submit" id="submit" value="Submeter"></input>
						</div>
						
					
					
					<div class="result">
						<label for="data_fim">Data Fim:</label><br>
						<input type="text" value="<?= isset($data_fim) ? $data_fim : null ?>" class="field" name="data_fim" disabled="true"></input>
					</div>
					
				</fieldset>
			</fieldset>
			
			<fieldset class="periodos">
				<legend>Períodos úteis</legend>
				<div class="container">
					<div class="header">
						Períodos
						<div id="addPeriod" style="margin-left: 50%; border-radius: 4px;">+</div>
					</div>
					
					<div class="instances">
						<table id="customers">
							<tr>
								<th>Dia</th>
								<th>Hora Inicial</th>
								<th>Hora Final</th>
								<th></th>
							</tr>
							<div class="periods">
							<?php
							if(!isset($GLOBALS['periodos'])){
								$position = 0;
							?>
							
							<tr id="TRweekday<?= $position;?>">
								<td>
									<select name="weekday0" required>
										<option></option>
										<option value="0">Segunda-feira</option>
										<option value="1">Terça-feira</option>
										<option value="2">Quarta-feira</option>
										<option value="3">Quinta-feira</option>
										<option value="4">Sexta-feira</option>
										<option value="5">Sábado</option>
										<option value="6">Domingo</option>
									</select>
								</td>
								<td>
									<input name="h_init_0" type="number" min="0" max="23" style="width: 40px;" required></input> :
									<input name="m_init_0" type="number" min="0" max="59" style="width: 40px;" required></input>
								</td>
								<td>
									<input name="h_end_0" type="number" min="0" max="23" style="width: 40px;" required></input> :
									<input name="m_end_0" type="number" min="0" max="59" style="width: 40px;" required></input>
								</td>
								<td>
									<div id="nTRweekday<?= $position?>" class="removePeriod">x</div>
								</td>
							</tr>
							
								<?php
							}
							else{
								foreach($GLOBALS['periodos'] as $key => $weekdays){
									$weekday = $key;
									foreach($weekdays as $periods){
										$position = $periods['position'];
										$horas_init = intval($periods['inicio']/60);
										$minutos_init = $periods['inicio']%60;
										$horas_fim = intval($periods['fim']/60);
										$minutos_fim = $periods['fim']%60;
									?>
									<div>
										<tr id="TRweekday<?= $position;?>">
											<td>
												<select name="weekday<?= $position;?>" required>
													<option></option>
													<option <?php if($weekday == 0){ echo "selected";} ?> value="0">Segunda-feira</option>
													<option <?php if($weekday == 1){ echo "selected";} ?> value="1">Terça-feira</option>
													<option <?php if($weekday == 2){ echo "selected";} ?> value="2">Quarta-feira</option>
													<option <?php if($weekday == 3){ echo "selected";} ?> value="3">Quinta-feira</option>
													<option <?php if($weekday == 4){ echo "selected";} ?> value="4">Sexta-feira</option>
													<option <?php if($weekday == 5){ echo "selected";} ?> value="5">Sábado</option>
													<option <?php if($weekday == 6){ echo "selected";} ?> value="6">Domingo</option>
												</select>
											</td>
											<td>
												<input name="h_init_<?= $position;?>" type="number" value="<?= $horas_init ?>" min="0" max="23" style="width: 40px;" required></input> :
												<input name="m_init_<?= $position;?>" type="number" value="<?= $minutos_init ?>" min="0" max="59" style="width: 40px;" required></input>
											</td>
											<td>
												<input name="h_end_<?= $position;?>" type="number" value="<?= $horas_fim ?>" min="0" max="23" style="width: 40px;" required></input> :
												<input name="m_end_<?= $position;?>" type="number" value="<?= $minutos_fim ?>" min="0" max="59" style="width: 40px;" required></input>
											</td>
											<td>
												<div id="nTRweekday<?= $position?>" class="removePeriod <?= $position?>">x</div>
											</td>
										</tr>
									</div>
							<?php
									}
								}
							}
							?>
								
							</div>
							
						</table>
						
					</div>
				</div>
				
				
			</fieldset>
			
			
		</form>
		
	</body>
	<script>
		$(document).ready(function(){
			
			var pos = <?= $position+1; ?>;
		
			$('form').on('submit', function(e) {
				var dI = $('#data_inicial').val();
				var sla = $('#sla').val();
				var sla_opt1 = $('#horas').prop("checked");
				var sla_opt2 = $('#minutos').prop("checked");
				
				if(dI == "" || sla == "" || (sla_opt1 == false && sla_opt2 == false)){
					e.preventDefault();
					$("#alert").show();
				}
				else{
					$("#alert").hide();
				}
			});
			
			$('#addPeriod').on('click', function(){
				$('#customers').append('<tr id="TRweekday'+pos+'"><td><select name="weekday'+pos+'" required><option></option><option value="0">Segunda-feira</option><option value="1">Terça-feira</option><option value="2">Quarta-feira</option><option value="3">Quinta-feira</option><option value="4">Sexta-feira</option><option value="5">Sábado</option><option value="6">Domingo</option></select></td><td><input type="number" name="h_init_'+pos+'" min="0" max="23" style="width: 40px; " required></input> : <input name="m_init_'+pos+'" type="number" min="0" max="59" style="width: 40px;" required></input></td><td><input name="h_end_'+pos+'" type="number" min="0" max="23" style="width: 40px;" required></input> : <input name="m_end_'+pos+'" type="number" min="0" max="59" style="width: 40px;" required></input></td><td><div id="nTRweekday'+pos+'" class="removePeriod '+pos+'">x</div></td></tr>');
				pos++;
			});
			
			$(document).on('click', '.removePeriod', function(){
				var id = $(this).prop('id');
				str = id.substr(1,11);
				$('#'+str).remove();
			});

		});
	</script>
</html>