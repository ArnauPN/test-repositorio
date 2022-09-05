<?php
include_once 'shore.php';
$p = new Shore();
$p->auth();

$idMerchant = $_POST['atr_id'];

$array_services = $p->get('services');
$array_categories = $p->get('service_categories', "?filter[merchants]=$idMerchant");
$array_merchants = $p->get('merchants', $idMerchant);

$array_feedbacks = $p->get('feedbacks', "?filter[merchant]=$idMerchant");

$myJSON2 = [];

foreach ($array_feedbacks->data as $infoFeedback) {
    $myObj2 = new stdClass();
    $myObj2->nombre_cliente = $infoFeedback->attributes->customer_name;

    $fechaComment = $infoFeedback->attributes->given_at;
    $datePost = date('d-m-Y', strtotime($fechaComment));

    $myObj2->fecha_comentario = $datePost;
    $myObj2->comentario = $infoFeedback->attributes->comment;

    $myJSON2[] = $myObj2;
}

$nameShore = '';
$streetShore = '';

$adressShore = $array_merchants->data->attributes->address;

foreach($array_categories->data as $post){
    $myObj = new stdClass();
    $myObj->direccionShore = $adressShore;
    $myObj->titleService = $post->attributes->name;

    $myObj->idService = $post->id;

    $myJSON[] = $myObj; //array categorias
}

echo json_encode([
    $myJSON, 
    $array_merchants->data->attributes->name,
    $myJSON2
]);