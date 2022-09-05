<?php
include_once 'shore.php';
$p = new Shore();
$p->auth();

if (isset($_POST['idServicio'])) {
    $idService = $_POST['idServicio'];
}else{
    $idService = '';
}

$services_filtered = $p->get('services', "?filter[service_category]=$idService");

foreach ($services_filtered->data as $servicio) {
    $myService = new stdClass();

    $myService->name = $servicio->attributes->name;
    $myService->description = $servicio->attributes->description;
    $myService->amount = $servicio->attributes->cost->amount;
    $myService->id = $servicio->id;

    foreach ($servicio->attributes->steps as $dur) {
        // $myService->duration = $dur->duration;
        $duration = $dur->duration;
        $resultado = sprintf('%2d:%02d', ($duration/60%60), $duration%60);
        $separacion = explode(":", $resultado);
        // var_dump($separacion[0]);

        if ($separacion[0] == "0") {
            $resultado = $separacion[1]." min";
        }
        elseif($separacion[1] == "00"){
            $resultado = $separacion[0]."h";
        }
        else{
            $resultado = $separacion[0]."h ".$separacion[1]." min";
        }

        // var_dump($resultado);
        // $minutos = floor(($horas * 60)/60);
        $myService->duration = $resultado;
        break;
    }
    $servicios[] = $myService;
}

echo json_encode($servicios);