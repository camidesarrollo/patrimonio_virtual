<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Verifica tu cuenta</title>

    <style>
        .btn {
            text-shadow: rgba(255,255,255,0.3);
            text-decoration: none;
            color: white;
        }

        .btn-color {
            color: white;
            background-color: #000000;
        }

        .btn-contenedor {
            margin-top: 20px;
            border-left-width: 0px;
            border-top-width: 0px;
            border-right-width: 0px;
            border-bottom-width: 0px;
            padding-left: 50px;
            padding-right: 50px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .btn-color:hover {
            color: white;
            background-color: #2D717C;
        }

        .btn-color:active {
            color: white;
            background-color: #2D717C;
        }
    </style>
</head>
<body style="font-family: Arial; background-color: white; box-sizing: border-box;">
    <div style="background-color: white; border-radius: 3px; color: #999; font-size: 0.8em; padding: 20px; margin: 0 auto; width: 600px;">

        <!--<div style="background-color: #2D717C; width: 100%; margin-left: 0px; padding-top: 0px; height: 20px; display: inline-flex; ">
        &nbsp;
    </div>-->

        <div style="background-color: #000000; width: 100%; margin-left: 0px; padding-top: 0px; height: 40px; display: inline-flex; ">
            &nbsp;
        </div>

        <div style="width: 100%; padding-top: 10px; display: inline-flex;">
            
            <!--<a style="margin: auto;" href="http://www.contenidoslocales.cl/" target="_blank"><img src="logo.png" alt=""></a>-->
        </div>


        <div style="width: 100%; background-color: white; margin-top: 0px; display: inline-table; text-align:center; ">
            <span style="color: #000000; font-size: 24px; font-weight: bold;">
                <br />
                Portal Patrimonio Virtual
            </span>
        </div>

        <div style="width: 100%; background-color: white; margin-top: 20px; display: inline-table; text-align: center;">
            <span style="color: #000000; font-size: 14px;">
                ¡Hola! <br />
                Para terminar de configurar tu cuenta en el portal Patrimonio Virtual, confirma tu correo:<br />
                
            </span>
        </div>

        <div style="width: 100%; background-color: white; margin-top: 20px; display: inline-table; text-align:center; ">
            <a class="btn btn-color btn-contenedor" href="{{texto}}" target="_blank" style="color: white;">Verificar correo</a>
        </div>

        <div style="width: 100%; background-color: white; margin-top: 20px; display: inline-table; text-align: center; ">
            <span style="color: #000000; font-size: 10px;">
                (si no puedes ingresar, por favor has clic en el siguiente link: <br />
                <a style="background-color: white; color: #0F69B4; font-size: 10px; margin-top: 20px;" href="{{texto}}" target="_blank">{{texto}} </a>)
            </span>
        </div>

        <div style="width: 100%; background-color: white; margin-top: 20px; display: inline-table; text-align: center; ">
            <span style="color: #000000; font-size: 10px;">
                ¡Nos vemos pronto! <br />
                <a style="background-color: white; color: #0F69B4; font-size: 10px; margin-top: 20px; " href="https://www.patrimoniovirtual.gob.cl/" target="_blank">https://www.patrimoniovirtual.gob.cl/</a>
            </span>
        </div>

        <div style="background-color: #000000; width: 100%; margin-left: 0px; margin-top:20px; padding-top: 0px; display: inline-flex; ">
            <div style="margin: auto; display: inline-flex;">
                <a href="https://www.cultura.gob.cl/" target="_blank"><img style="padding-top: 15px; padding-bottom: 15px; height: 85px;" src="<?php echo site_url() .'/wp-content/uploads/2022/09/img-mcl-2.png'?>" alt=""></a>
                <!--<a href="https://www.cultura.gob.cl/" target="_blank"><img style="padding-top: 15px; padding-bottom: 15px;" src="logo_ministerio.png" alt=""></a>-->
                <a href="http://www.biblioredes.gob.cl/" target="_blank"><img style="padding-left: 5px; padding-top: 15px; padding-bottom: 15px;" src="<?php echo site_url() .'/wp-content/uploads/2022/09/img-mcl-3.png'?>" alt=""></a>
                <!--<a href="http://www.biblioredes.gob.cl/" target="_blank"><img style="padding-left: 5px; padding-top: 15px; padding-bottom: 15px;" src="logo_biblioredes.png" alt=""></a>-->

                <div style="margin-left: 20px;">
                    <span style="display: flex; background-color: #000000; color: #FFFFFF; font-size: 10px; padding-top: 15px; ">Síguenos en las redes sociales:</span>
                    <a href="https://www.youtube.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="<?php echo site_url() .'/wp-content/uploads/2022/09/img-mcl-4.png'?>" alt=""></a>
                    <!--<a href="https://www.youtube.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="youtube.png" alt=""></a>-->
                    <a href="https://es-la.facebook.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="<?php echo site_url() .'/wp-content/uploads/2022/09/img-mcl-5.png'?>" alt=""></a>
                    <!--<a href="https://es-la.facebook.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="facebook.png" alt=""></a>-->
                    <a href="https://twitter.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="<?php echo site_url() .'/wp-content/uploads/2022/09/img-mcl-6.png'?>" alt=""></a>
                    <!--<a href="https://twitter.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="twitter.png" alt=""></a>-->
                    <a href="https://www.instagram.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="<?php echo site_url() .'/wp-content/uploads/2022/09//img-mcl-7.png'?>" alt=""></a>
                    <!--<a href="https://www.instagram.com/" target="_blank"><img style="padding-left: 5px; padding-top: 10px;" src="instagram.png" alt=""></a>-->
                </div>
            </div>
        </div>

        <br />

    </div>
</body>
</html>




