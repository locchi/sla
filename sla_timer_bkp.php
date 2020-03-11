<?php

try{
	$data_atual = date("d/m/Y H:i:s", time());
	
	if(isset($_POST["data_inicial"]) && isset($_POST["sla"]) && isset($_POST["sla_opt"])){
		$data_inicial = $_POST["data_inicial"];
		$sla = $_POST["sla"];
		$sla_opt = $_POST["sla_opt"];
		$data_fim = calcular($data_inicial, $sla_opt, $sla);
		foreach($_POST as $key => $val){
			echo "<script>console.log('".$key.": ".$val."');</script>";
		}
		
		
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
	//Transformar em segundos
	$tempo = ($sla_opt == "h") ? $sla*60 : $sla;
	$data_fim = iterate_worktime($data_inicial, $tempo);
	return date("d/m/Y H:i:s", strtotime($data_fim));
}

function iterate_worktime($date, $time){
	while($time > 0){
		$weekday = get_weekday($date);
		$worktime = get_worktime($weekday);
		
		$minutes = date('i', strtotime($date));
		$hours = date('H', strtotime($date));
		$day_minutes = intval($minutes) + intval($hours)*60;
		
		foreach($worktime as $weekday_iterator){
			if(($weekday_iterator['fim'] - $weekday_iterator['inicio']) > 0){
				$fim = $weekday_iterator['fim'];
				$inicio = $weekday_iterator['inicio'];
			}
			else{
				continue;
			}
			if($day_minutes >= $inicio && $day_minutes < $fim){
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

function get_worktime($index){
	
	$seg = array(
		0 => array(
			"inicio" => 540,
			"fim" => 720
		),
		1 => array(
			"inicio" => 780,
			"fim" => 1080
		)
	);
	
	$ter = array(
		0 => array(
			"inicio" => 540,
			"fim" => 720
		),
		1 => array(
			"inicio" => 780,
			"fim" => 1080
		)
	);
	
	$qua = array(
		0 => array(
			"inicio" => 540,
			"fim" => 720
		),
		1 => array(
			"inicio" => 780,
			"fim" => 1080
		)
	);
	
	$qui = array(
		0 => array(
			"inicio" => 540,
			"fim" => 720
		),
		1 => array(
			"inicio" => 780,
			"fim" => 1080
		)
	);
	
	$sex = array(
		0 => array(
			"inicio" => 540,
			"fim" => 720
		),
		1 => array(
			"inicio" => 780,
			"fim" => 1080
		)
	);
	
	$sab = array(
		0 => array(
			"inicio" => 0,
			"fim" => 0
		)
	);
	
	$dom = array(
		0 => array(
			"inicio" => 0,
			"fim" => 0
		)
	);
	
	$periodos = array(
		0 => $seg,
		1 => $ter,
		2 => $qua,
		3 => $qui,
		4 => $sex,
		5 => $sab,
		6 => $dom
	);
	
	return $periodos[$index];
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
			width: 550px;
			height: 650px;
			overflow: hidden;
			border: 2px solid;
			float: left;
		}
		
		.periodos{
			width: 950px;
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
		
		#removePeriod{
			margin-left: 20%; 
			background-color: red; /* Green */
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
							<label for="data_atual">Data Atual:</label>
							<input type="text" class="field" name="data_atual" value="<?php echo $data_atual ?>" disabled="true"></input>
						</div>
						<div class="campo">
							<label for="data_inicial">Data Inicial:</label>
							<input type="datetime-local" id="data_inicial" value="<?php echo isset($data_inicial) ? $data_inicial : null ?>" class="field" name="data_inicial"></input>
						</div>
						
						<div class="campo">
							<label for="sla">SLA:</label>
							<input type="radio" value="h" <?php echo (isset($sla_opt) && $sla_opt == "h") ? "checked" : null?> name="sla_opt">Horas</input>
							<input type="radio" value="m" <?php echo (isset($sla_opt) && $sla_opt == "m") ? "checked" : null?> name="sla_opt">Minutos</input><br>
							<input type="number" id="sla" value="<?php echo (isset($sla)) ? $sla : null?>" class="field" name="sla"></input>
						</div>
						
						<div class="campo">
							
							<input type="submit" id="submit" value="Submeter"></input>
						</div>
						
					
					
					<div class="result">
						<label for="data_fim">Data Fim:</label><br>
						<input type="text" value="<?php echo isset($data_fim) ? $data_fim : null ?>" class="field" name="data_fim" disabled="true"></input>
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
								<tr>
									<td>
										<select name="weekday0" required>
											<option></option>
											<option value="0">Segunda-Feira</option>
											<option value="1">Terça-Feira</option>
											<option value="2">Quarta-Feira</option>
											<option value="3">Quinta-Feira</option>
											<option value="4">Sexta-Feira</option>
											<option value="5">Sábado</option>
											<option value="6">Domingo</option>
										</select>
									</td>
									<td>
										<input name="h_init_0" type="number" min="0" max="23" style="width: 30px;" required></input> :
										<input name="m_init_0" type="number" min="0" max="59" style="width: 30px;" required></input>
									</td>
									<td>
										<input name="h_end_0" type="number" min="0" max="23" style="width: 30px;" required></input> :
										<input name="m_end_0" type="number" min="0" max="59" style="width: 30px;" required></input>
									</td>
									<td>
										<div id="removePeriod">X</div>
									</td>
								</tr>
								
							</div>
							
						</table>
						
					</div>
				</div>
			</fieldset>
		</form>
	</body>
	<script>
		$(document).ready(function(){
			$('form').on('submit', function(event) {
				var dI = $('#data_inicial').val();
				var sla = $('#sla').val();
				var sla_opt1 = $('#horas').prop("checked");
				var sla_opt2 = $('#minutos').prop("checked");
				
				if(dI == "" || sla == "" || (sla_opt1 == false && sla_opt2 == false)){
					event.preventDefault();
					$("#alert").show();
				}
				else{
					$("#alert").hide();
				}
			});
			
			var int_periods = 1;
			
			$('#addPeriod').click(function(){
				$('#customers').append('<tr><td><select name="weekday'+int_periods+'" required><option></option><option value="0">Segunda-Feira</option><option value="1">Terça-Feira</option><option value="2">Quarta-Feira</option><option value="3">Quinta-Feira</option><option value="4">Sexta-Feira</option><option value="5">Sábado</option><option value="6">Domingo</option></select></td><td><input type="number" name="h_init_'+int_periods+'" min="0" max="23" style="width: 30px; " required></input> : <input name="m_init_'+int_periods+'" type="number" min="0" max="59" style="width: 30px;" required></input></td><td><input name="h_end_'+int_periods+'" type="number" min="0" max="23" style="width: 30px;" required></input> : <input name="m_end_'+int_periods+'" type="number" min="0" max="59" style="width: 30px;" required></input></td><td><div id="removePeriod">X</div></td></tr>');
				int_periods++;
			});

		});
	</script>
</html>