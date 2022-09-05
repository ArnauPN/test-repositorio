<?php
require_once "vendor/autoload.php";
require_once "shore.php";

// Funcion para fraccionar el horario de un dia
function intervaloHora($hora_inicio, $hora_fin, $intervalo = 30)
{

    $hora_inicio = new DateTime($hora_inicio);
    $hora_fin = new DateTime($hora_fin);
    $hora_fin->modify('+1 second'); // Añadimos 1 segundo para que nos muestre $hora_fin

    // Si la hora de inicio es superior a la hora fin
    // añadimos un día más a la hora fin
    if ($hora_inicio > $hora_fin) {

        $hora_fin->modify('+1 day');
    }

    // Establecemos el intervalo en minutos
    $intervalo = new DateInterval('PT' . $intervalo . 'M');

    // Sacamos los periodos entre las horas
    $periodo = new DatePeriod($hora_inicio, $intervalo, $hora_fin);

    foreach ($periodo as $hora) {

        // Guardamos las horas intervalos
        $horas[] = $hora->format('H:i');
    }

    return $horas;
}

if (isset($_POST['daySelected'])) {
    $day = $_POST['daySelected'];
}else{
    $day = strtolower(date("l"));
}

$merchant_id = $_POST['merchant_id'];

$service_id = $_POST['service_id'];

$endDay = date("Y-m-d",strtotime($day."+ 1 days"));;


$p = new Shore();
$p->auth();
$arreglo = $p->get('merchants');

$array_horas = $p->get('availability/slots', "$merchant_id?required_capacity=1&search_weeks_range=0&services_resources%5B%5D%5Bservice_id%5D=$service_id&timezone=Europe%2FMadrid&starts_at=$day&ends_at=$endDay%2023%3A59%3A59");

$horasGuardadas = [];

foreach ($array_horas->slots as $key) {
    if ($key->date == $day) {
        foreach ($key->times as $key2) {
            $horasGuardadas[] = $key2;
        }
    }
}

echo json_encode($horasGuardadas);