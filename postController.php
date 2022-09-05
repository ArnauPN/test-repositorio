<?php
require_once "vendor/autoload.php";
require_once "shore.php";

$p = new Shore();
$p->auth();

$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$solicitudes = $_POST['solicitudes'];

$service_id = $_POST['service'];
$merchant_id = $_POST['merchant'];
$tiempo_cita = $_POST['duration'];
$fecha_obtenida = $_POST['fecha'];

$dateTime = new DateTime($fecha_obtenida);
$dateFormated = $dateTime->format(DateTime::ATOM);

$getMerchant = $p->get('merchants', $merchant_id);
$getResource = $p->get('resources', "?filter[merchant]=$merchant_id");

$search_mail_customer = $p->get('customers', "?filter[emails.value]=$email");
$search_tel_customer = $p->get('customers', "?filter[phones.value]=$telefono");

$info_customer = $search_mail_customer->data;
$info_customer2 = $search_tel_customer->data;

if (empty($info_customer)) {
    $data2 = '{
        "data" : {
            "type" : "customers",
            "attributes" : {
                "vip" : false,
                "custom_attributes" : [],
                "given_name" : "'.$nombre.'",
                "surname" : "'.$apellidos.'",
                "addresses" : [],
                "phones" : [
                    {
                        "name" : "phone",
                        "value" : "+34 '.$telefono.'"
                    }
                ],
                "emails" : [
                    {
                        "name" : "email",
                        "value" : "'.$email.'"
                    }
                ]
            },
            "relationships" : {
                "merchant" : {
                    "data" : {
                        "type" : "merchants",
                        "id" : "'.$merchant_id.'"
                    }
                }
            }
        }
    }';

    $new_customer = $p->post('customers', $data2);
    foreach ($new_customer as $key) {
        $customer_id = $key->id;
    }
}
else{
    foreach ($info_customer as $key) {
        $customer_id = $key->id;
    }
}

foreach ($getResource->data as $key) {
    $resource_id = $key->id;
}

foreach ($getMerchant as $key) {
    $calle = $key->attributes->address->line1;
    $city = $key->attributes->address->city;
    $state = $key->attributes->address->country;
    $postal = $key->attributes->address->postal_code;
}

$data = '{
    "data": {
        "type": "appointments",
        "attributes": {
            "title": "'.$solicitudes.'",
            "starts_at": "'.$dateFormated.'",
            "participant_count": "1",
            "address": {
                "line1": "'.$calle.'",
                "line2": "",
                "city": "",
                "state": "'.$state.'",
                "country": "'.$city.'",
                "postal_code": "'.$postal.'"
            },
            "steps": [
                {
                    "name": "Working step",
                    "break": false,
                    "with_customer": true,
                    "duration": "'.$tiempo_cita.'",
                    "resource_ids": [
                        "'.$resource_id.'"
                    ],
                    "service_id": "'.$service_id.'",
                    "employee_selected_by": "customer"
                }
            ]
        },
        "relationships": {
            "merchant": {
                "data": {
                    "type": "merchants",
                    "id": "'.$merchant_id.'"
                }
            },
            "customer" : {
                "data" : {
                    "type" : "customers",
                    "id" : "'.$customer_id.'"
                }
            }
        }
    }
}';

$p->post('appointments', $data);

// header('Location: success.php');