<?php
include_once "shore.php";
require_once "vendor/autoload.php";
$dotenv = new Symfony\Component\Dotenv\Dotenv();
try {
    $dotenv->usePutenv()->load('.env');
} catch (Exception $e) {
}

if (isset($_GET['token'])) {
    $idRecuperada = $_GET['token'];
}else{
    $idRecuperada = "No hay id";
}

$p = new Shore();
$p->auth();

$arreglo = $p->get('services', $idRecuperada);
$idMerchant = $arreglo->data->relationships->merchant->data->id;

$resource_filter = $p->get('resources', "?filter[merchant]=$idMerchant");

$getMerchant = $p->get('merchants', $idMerchant);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario Reservas</title>
    <link rel="stylesheet" href="css/stylesCheckout.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <link href="img/605c45aab5fd727b9808d3ea_5d76e1ec344fac836f08e407_favicon32.png" rel="shortcut icon" type="image/x-icon">
</head>
<body>
<?php  echo "<div id='info_ids' merchant-id='$idMerchant' service-id='$idRecuperada'></div>" ?>

<div class="container">
    <div class="divServicios" id="oculto-2">
        <a href="peluqueria.php" class="linkServicios">Volver a los servicios</a>
    </div>
</div>

<div class="container">
    <div class="divServicios" id="oculto-1" style="display: none;">
        <a href="peluqueria.php" class="linkServicios">Volver a los servicios</a>
    </div>

    <div class="container2">
        <div class="container3">
            <h3 class="mis-font" id="fecha-y-hora">Selecciona una fecha y hora</h3>
            <div class="calendar" id="calendar-app">
                <div class="calendar--view" id="calendar-view">
                    <div class="cview__month mis-font">
                        <span class="cview__month-last" id="calendar-month-last">Apr</span>
                        <span class="cview__month-current" id="calendar-month">May</span>
                        <span class="cview__month-next" id="calendar-month-next">Jun</span>
                    </div>
                    <div class="cview__header">lun</div>
                    <div class="cview__header">mar</div>
                    <div class="cview__header">mié</div>
                    <div class="cview__header">jue</div>
                    <div class="cview__header">vie</div>
                    <div class="cview__header">sáb</div>
                    <div class="cview__header">dom</div>
                    <div class="calendar--view" id="dates">
                    </div>
                </div>
            </div>
            <!-- Horarios -->
            <div id="horarios" style="display: none;">
                <h3 id="h3Horarios" class="mis-font"></h3>

                <ul id="listaHoras"></ul>
            </div>
        </div>
    </div>
    <div class="container-resumen">
        <div class="resumen">
            <div>
                <h1 class="mis-font">Resumen</h1>
                <div id="resumen">
                    <?php echo $arreglo->data->attributes->name; ?>
                    <br>
                    <?php echo "<span style='font-weight:200'>".$getMerchant->data->attributes->name."</span>"; ?>
                    
                </div>
            </div>
            <hr>
            <div class="horario-seleccionado" id="ocultado">
                <div>
                    <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="calendar-check" class="svg-inline--fa fa-calendar-check fa-w-14 sc-cpmLhU bsCeFa" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M400 64h-48V12c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v52H160V12c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v52H48C21.49 64 0 85.49 0 112v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm-6 400H54a6 6 0 0 1-6-6V160h352v298a6 6 0 0 1-6 6zm-52.849-200.65L198.842 404.519c-4.705 4.667-12.303 4.637-16.971-.068l-75.091-75.699c-4.667-4.705-4.637-12.303.068-16.971l22.719-22.536c4.705-4.667 12.303-4.637 16.97.069l44.104 44.461 111.072-110.181c4.705-4.667 12.303-4.637 16.971.068l22.536 22.718c4.667 4.705 4.636 12.303-.069 16.97z"></path></svg>
                </div>
                <div>
                    <div id="fecha-oculta"></div>
                    <div>
                        <div id="hora-oculta" style="display: inline-block"></div>
                        <p id="duracion-oculta" style="display:none; margin: 0px;">
                            <?php 
                                foreach ($arreglo->data->attributes->steps as $dur) {
                                    $duration = $dur->duration;
                                    $resultado = sprintf('%2d:%02d', ($duration/60%60), $duration%60);
                                    $separacion = explode(":", $resultado);

                                    if ($separacion[0] == "0") {
                                        $resultado = $separacion[1]." min";
                                    }
                                    elseif($separacion[1] == "00"){
                                        $resultado = $separacion[0]."h";
                                    }
                                    else{
                                        $resultado = $separacion[0]."h ".$separacion[1]." min";
                                    }

                                    echo $resultado;
                                    echo "<div id='duration' attr-duration='$dur->duration'></div>";
                                }
                            ?>
                            
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <p style="display: inline-block;"> Precio total:</p>
                <p style="display: inline-block; float: right;" class="points"> <?php echo $arreglo->data->attributes->cost->amount ?> €</p>
            </div>
            <button class="btn">Continuar</button>
        </div>
    </div> 
</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Introduce tus datos</h2>
    <p>Introduce tus datos para completar la reserva</p>
    <div class="formulario">
        <div class="div-form">
            <form class="styles-form" method="post" action="success.php">
                <div style="display: block; margin-bottom: 16px">
                    <label for="" style="display: block;">Nombre *</label>
                    <div class="div-input">
                        <input type="text" name="nombre" id="" class="input-style" required>
                        <input type="hidden" name="merchant" id="" value="<?php echo $idMerchant ?>">
                        <input type="hidden" name="service" id="" value="<?php echo $idRecuperada ?>">
                        <input type="hidden" name="duration" id="" value="<?php echo $dur->duration ?>">
                        <input type="hidden" name="coste" id="keloke" value="<?php echo $arreglo->data->attributes->cost->amount ?>">
                        <input type="hidden" name="fecha" id="fechaSeleccionada">
                    </div>
                </div>

                <div style="display: block; margin-bottom: 16px">
                    <label for="" style="display: block;">Apellidos *</label>
                    <div class="div-input">
                        <input type="text" name="apellidos" id="" class="input-style" required>
                    </div>
                </div>
                
                <div style="display: block; margin-bottom: 16px">
                    <label for="" style="display: block;">E-mail *</label>
                    <div class="div-input">
                        <input type="email" name="email" id="" class="input-style">
                    </div>
                </div>

                <div style="display: block; margin-bottom: 16px">
                    <label for="" style="display: block;">Teléfono móvil *</label>
                    <div class="div-input">
                        <input type="number" name="telefono" id="" class="input-style" required>
                    </div>
                </div>

                <div style="display: block; margin-bottom: 16px">
                    <label for="" style="display: block;">Solicitudes especiales</label>
                    <div class="div-input">
                        <textarea name="solicitudes" id="" cols="30" rows="3" class="input-style"></textarea>
                    </div>
                </div>

                <p>Los campos con * son obligatoios</p>

                <input type="submit" class="btn-reserva" value="Reservar ahora" name="enviar">
            </form>
        </div>
    </div>
  </div>

</div>

<script src="js/calendarApp.js"></script>
<script src="js/scriptCheckout.js"></script>
</body>
</html>