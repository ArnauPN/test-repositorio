<?php
require_once "vendor/autoload.php";
require_once "shore.php";

$diaActual = $_POST['hoy'];
$yearActual = $_POST['añoActual'];
$monthActual = $_POST['mesActual'];

$today = date($yearActual."-".$monthActual."-1");

$todayNumber = (int)date("d", strtotime($today));
$daysMonth = (int)date("t", strtotime($today));
$merchant_id = $_POST['merchant_id'];

$service_id = $_POST['service_id'];
$nDias = $daysMonth - $todayNumber;

$endDay = date("Y-m-d",strtotime($today."+".$nDias." days"));

$p = new Shore();
$p->auth();
$arreglo = $p->get('merchants');

$array_horas = $p->get('availability/slots', "$merchant_id?required_capacity=1&search_weeks_range=0&services_resources%5B%5D%5Bservice_id%5D=$service_id&timezone=Europe%2FMadrid&starts_at=$today&ends_at=$endDay%2023%3A59%3A59");

$diasTachados = [];

foreach ($array_horas->slots as $key) {

    if(empty($key->times)){
        $diasTachados[] = "day".date("Y-n-j", strtotime($today));
    }
    
    $today = date("Y-m-d",strtotime($today."+ 1 days"));
}

echo json_encode($diasTachados);