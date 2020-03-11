<!DOCTYPE html>
<html>
    <head>
    <title>SLA TIMER</title>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    </head>
    <style>
        .container{
            display: block;
            width: 800px;
            height: 600px;
            border: 1px solid;
            background-color: lightgray;
        }
        .main{
            display: block;
            margin: 10px;
            width: 97%;
            height: 170px;
            border: 1px solid;
            background-color: white;
        }

        .main_box{
            display: -webkit-inline-box;
            margin: 10px;
            width: 97%;
            height: 64%;
            border: 1px solid;
            background-color: white;
        }

        .box_periodo{
            width: 200px;
            background-color: white;
            margin: 30px;
            
        }

        .fields{
            margin: 10px; 
        }

        .field_inputs{
            width: 300px;
            margin-bottom:13px;
            display: grid;
        }

        .input_periodo_h, .input_periodo_m{
            width: 20px;
        }

        .plus_btn{
            margin-right: 10px;
        }

    </style>
    <body>
        <div class="container">
            <div class="main">
                <div class="fields">
                    <label for="nome">Título: </label>
                    <input class="field_inputs" type="text" name="nome"/>

                    <label for="sla">SLA: </label>
                    <input class="field_inputs" type="number" min="0" max="999" name="sla"/>

                    <input type="radio" name="unidade">Minutos</input>
                    <input type="radio" name="unidade">Horas</input>
                </div>
            </div>
            <div class="main_box">
                <div class="box_periodo" id="seg">
                    <center>Segunda-feira</center>
                    <br>
                    <button class="plus_btn" id="btn_seg">+</button>
                    <input maxlength="2" class="input_periodo_h" type="text"/>
                    :
                    <input maxlength="2" class="input_periodo_m" type="text"/>
                    -
                    <input maxlength="2" class="input_periodo_h" type="text"/>
                    :
                    <input maxlength="2" class="input_periodo_m" type="text"/>
                </div>

                <div class="box_periodo" id="ter">
                    <center>Terça-feira</center>
                    <br>
                    <button class="plus_btn" id="btn_ter">+</button>
                    <div class="inputs">
                        <input maxlength="2" class="input_periodo_h" type="text"/>
                        :
                        <input maxlength="2" class="input_periodo_m" type="text"/>
                        -
                        <input maxlength="2" class="input_periodo_h" type="text"/>
                        :
                        <input maxlength="2" class="input_periodo_m" type="text"/>
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
    <script>

        $(".input_periodo_m").on("change", function(){
            var str = $(this).val();
            var patt1 = /[0-9]/g;
            var result = str.match(patt1);

            if(!result) $(this).val("");
            if(parseInt(str) < 10 && str.length == 1) $(this).val("0"+$(this).val());
            if(parseInt(str) > 59) $(this).val(59);
            
        });
        
        $(".input_periodo_h").on("change", function(){
            var str = $(this).val();
            var patt1 = /[0-9]/g;
            var result = str.match(patt1);

            if(!result) $(this).val("");
            if(parseInt(str) < 10 && str.length == 1) $(this).val("0"+$(this).val());
            if(parseInt(str) > 23) $(this).val(23);
            
        });

        $(".plus_btn").on("click", function(){
            $(".inputs").append('<input maxlength="2" class="input_periodo_h" type="text"/>:<input maxlength="2" class="input_periodo_m" type="text"/>-<input maxlength="2" class="input_periodo_h" type="text"/>:<input maxlength="2" class="input_periodo_m" type="text"/>');
        });
    </script>
</html>