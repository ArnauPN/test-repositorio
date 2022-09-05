<?php
    include_once 'shore.php';
    require_once "vendor/autoload.php";
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    try {
        $dotenv->usePutenv()->load('.env');
    } catch (Exception $e) {
    }

    $p = new Shore();
    $p->auth();
    $numeroklk = '1';

    $arreglo_merchants = $p->get('merchants');
    $array_feedbacks = $p->get('feedbacks');

    foreach ($array_feedbacks as $longitud_array) {
        $longitud_array = count($longitud_array);
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Reservas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="css/stylesCalendar.css">
    <link href="img/605c45aab5fd727b9808d3ea_5d76e1ec344fac836f08e407_favicon32.png" rel="shortcut icon" type="image/x-icon">
</head>
<body>
    <div class="header">
        <div class="sc-fHxwqH iFcOWn">
            <div class="sc-cEvuZC crlfOh">
                <div class="sc-ugnQR hRKNKp">
                    <h2 class="sc-eIHaNI gTjWnk" id="name-tienda" style="display: none;">Miscota San Vicente</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="ubicacion">
        <ul class="sc-OxbzP jpBGpq">
            <li title="Feedbacks" class="sc-bYnzgO bhQYzO">
                <a href="index.html" class="li-servicios">Volver atrás</a>
            </li>

            <li title="Direccion" class="sc-bYnzgO bhQYzO">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="map-marker-alt" class="svg-inline--fa fa-map-marker-alt fa-w-12 sc-cPuPxo BXamN" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path fill="currentColor" d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"></path>
                </svg>
                <span class="sc-hvvHee dWZHUx" id="calle-tienda">Ninguna tienda seleccionada</span>
            </li>

            <li title="Feedbacks" class="sc-bYnzgO bhQYzO">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="map-marker-alt" class="svg-inline--fa fa-map-marker-alt fa-w-12 sc-cPuPxo BXamN" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path fill="currentColor" d="M416 192c0-88.4-93.1-160-208-160S0 103.6 0 192c0 34.3 14.1 65.9 38 92-13.4 30.2-35.5 54.2-35.8 54.5-2.2 2.3-2.8 5.7-1.5 8.7S4.8 352 8 352c36.6 0 66.9-12.3 88.7-25 32.2 15.7 70.3 25 111.3 25 114.9 0 208-71.6 208-160zm122 220c23.9-26 38-57.7 38-92 0-66.9-53.5-124.2-129.3-148.1.9 6.6 1.3 13.3 1.3 20.1 0 105.9-107.7 192-240 192-10.8 0-21.3-.8-31.7-1.9C207.8 439.6 281.8 480 368 480c41 0 79.1-9.2 111.3-25 21.8 12.7 52.1 25 88.7 25 3.2 0 6.1-1.9 7.3-4.8 1.3-2.9.7-6.3-1.5-8.7-.3-.3-22.4-24.2-35.8-54.5z"></path>
                </svg>
                <span class="sc-hvvHee dWZHUx" id="evaluacion">
                    opiniones
                </span>
            </li>
        </ul>
    </div>

    <div id="banner" class="centered-container">
        <div class="sc-hMrMfs fUonNN">
            <div class="sc-drlKqa fXGofg">
                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="comment" class="svg-inline--fa fa-comment fa-w-16 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" d="M256 32C114.6 32 0 125.1 0 240c0 47.6 19.9 91.2 52.9 126.3C38 405.7 7 439.1 6.5 439.5c-6.6 7-8.4 17.2-4.6 26S14.4 480 24 480c61.5 0 110-25.7 139.1-46.3C192 442.8 223.2 448 256 448c141.4 0 256-93.1 256-208S397.4 32 256 32zm0 368c-26.7 0-53.1-4.1-78.4-12.1l-22.7-7.2-19.5 13.8c-14.3 10.1-33.9 21.4-57.5 29 7.3-12.1 14.4-25.7 19.9-40.2l10.6-28.1-20.6-21.8C69.7 314.1 48 282.2 48 240c0-88.2 93.3-160 208-160s208 71.8 208 160-93.3 160-208 160z"></path>
                </svg>
            </div>
            <div class="sc-bIqbHp bhsucF" style="font-size: 16px;">Bienvenido a Miscota! "Uno más de la familia" 💙💈</div>
            <button class="sc-jxGEyO cJpGUw" id="cruz">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
                    <path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="centered-container">
        <h2 class="h2Container">Selecciona una tienda</h2>

        <div>

            <?php
                foreach ($arreglo_merchants->data as $merch) {
                    echo "<div class='divMerchants' id='".$merch->id."' onClick=\"mostrarTiendas('".$merch->id."');\"><span class='".$merch->id."'></span>".$merch->attributes->name."</div>";
                }
            ?>

        </div>
    </div>

    <div id='container'>
        <div class='centered-container'>
            <h2 class="h2Container">Selecciona el servicio</h2>
            <div id="oculto">
                <div class="containers-carton">
                    <h2 style="filter: blur(6px);">🛁 Solo Baño 💦</h2>
                </div>
                <hr>
                <div class="containers-carton">
                    <h2 style="filter: blur(6px);">🛁 Baño + Corte a Máquina 🔦 ♒</h2>
                </div>
                <hr>
                <div class="containers-carton">
                    <h2 style="filter: blur(6px);">🛁 Baño + Corte a tijera ✂</h2>
                </div>
                <hr>
                <div class="containers-carton">
                    <h2 style="filter: blur(6px);">🛁 Baño + Stripping 🪒</h2>
                </div>
                <hr>
                <div class="containers-carton">
                    <h2 style="filter: blur(6px);">😸 Gato / Conejo 🐰</h2>
                </div>
            </div>
        </div>
    </div>

    <footer class="sc-dKEPtC cHGneC" >
        <section class="sc-aewfc bshoRP">
            <ul class="sc-dBAPYN boWenl" id="info-feedback">

                <li id="li-feedback">

                </li>

                <li name="li-direccion">
                    <div class="sc-dwztqd hwOphT">
                        <div class="sc-hdPSEv izdyOr">
                            <h3 class="h3Footer">Dirección</h3>
                            <div id="merchant-location-655351532" class="sc-gleUXh gKANZI">
                                <iframe id="maps" frameborder="0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDdN0jti71h62shAZnI3J-Jta76j451pmo&amp;q=''" allowfullscreen="" class="sc-doWzTn kTbsSj"></iframe>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>
        </section>
    </footer>
    <!-- Final del footer -->
    
</body>
</html>