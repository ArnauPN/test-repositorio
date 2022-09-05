<?php

require 'vendor/autoload.php';
require_once "vendor/autoload.php";
$dotenv = new Symfony\Component\Dotenv\Dotenv();
try {
    $dotenv->usePutenv()->load('.env');
} catch (Exception $e) {
}

use GuzzleHttp\Client;

class Shore
{
    private $username;
    private $password;
    private $endpoint;

    private $guzzle_client;
    private $token;

    public function __construct()
    {
       $this->username = $_ENV['USERNAME'];
       $this->password = $_ENV['PASSWORD'];
       $this->endpoint = $_ENV['ENDPOINT'];
        try{
            $this->guzzle_client = new Client([
                'base_uri' => $this->endpoint,
                'timeout'  => 5,
            ]);

        }catch(Exception $e){
            echo 'Error interno, código de error: '. $e->getMessage(). "\n";
        }
    }

    public function auth(){
        try{
            $response = $this->guzzle_client->request('POST', 'tokens', [
                'form_params' => [
                    'grant_type' => 'password',
                    'username' => $this->username,
                    'password' => $this->password
                ]
            ]);
    
            if ($response->getStatusCode() == 200) {
                $data_response = json_decode($response->getBody()->getContents());
                $this->token = $data_response->access_token;
                return true;
            }
    
            return false;

        }catch(Exception $e){
            return 'Error al cargar la página: '. $e->getMessage(). "\n";
        }
        
    }

    public function get($endpoint, $data = null){
        try{
            $response = $this->guzzle_client->request('GET', $endpoint . "/" . $data, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->token
                ],
                // 'query' => [
                //     'page' => '2',
                // ]
            ]);
    
            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody()->getContents());
            }
    
            return false;

        }catch(Exception $e){
            return 'No se han podido obtener los datos: '. $e->getMessage()."\n";
        }

    }

    public function post($endpoint, $data){
        try{
            $response = $this->guzzle_client->request('POST', $endpoint, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->token,
                    'Content-Type' => "application/vnd.api+json"
                ],
                'body' => $data
            ]);
    
            if ($response->getStatusCode() == 201) {
                $response = $response->getBody();
                $arr_result = json_decode($response);
                return $arr_result;
            }
    
            return false;

        }catch(Exception $e){
            return 'No se han podido entregar los datos: '.$e->getMessage()."\n";
        }
    }

    public function patch($endpoint, $id, $data){
        try{
            $response = $this->guzzle_client->request('PATCH', $endpoint . "/" . $id, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->token,
                    'Content-Type' => "application/vnd.api+json"
                ],
                'body' => $data
            ]);
    
            if ($response->getStatusCode() == 200) {
                $response = $response->getBody();
                $arr_result = json_decode($response);
                return $arr_result;
            }
    
            return false;

        }catch(Exception $e){
            return 'No se han podido actualizar los datos: '.$e->getMessage()."\n";
        }
        
    }

    public function delete($endpoint, $data){
        try{
            $response = $this->guzzle_client->request('DELETE', $endpoint . "/" . $data, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->token
                ]
            ]);
    
            if ($response->getStatusCode() == 204) { 
                return true;
            }
    
            return false;

        }catch(Exception $e){
            return 'No se han podido eliminar los datos: '.$e->getMessage()."\n";
        }
        

    }

}


// $p->delete('appointments', '31ff13e9-03d2-409e-81df-c3e39bc925af');
// var_dump($p->get('services'));

// $data = '{
//     "data": {
//         "type": "appointments",
//         "attributes": {
//             "starts_at": "2029-05-17T12:00:00Z",
//             "title": "Pelito Karim de la Benzema",
//             "color": "#bdd7a5",
//             "participant_count": "1",
//             "address": {
//                 "line1": "Rosenheimer Str. 1",
//                 "line2": "",
//                 "city": "Real Madrir",
//                 "state": "CF",
//                 "country": "ES",
//                 "postal_code": "12345"
//             },
//             "steps": [
//                 {
//                     "name": "Working step",
//                     "break": false,
//                     "with_customer": true,
//                     "duration": 60,
//                     "resource_ids": [],
//                     "service_id": "0e5053de-697f-4516-8918-9b85d0c105f9",
//                     "employee_selected_by": "customer"
//                 }
//             ]
//         },
//         "relationships": {
//             "merchant": {
//                 "data": {
//                     "type": "merchants",
//                     "id": "fb146049-5f1b-46c5-8215-f938e7e3cb10"
//                 }
//             },
//             "customer": {
//                 "data": {
//                     "type": "customers",
//                     "id": "10cf3b5c-11db-4982-b158-50c21a92f857"
//                 }
//             }
//         }
//     }
// }';

// $data = '{
// 	"data": {
// 		"type": "services",
// 		"attributes": {
// 			"name" : "Haircut",
// 			"cost" : { 
// 				"amount": 1000, 
// 				"currency": "EUR" 
// 			},
// 			"steps" : [
// 				{
// 					"name" : "Cortesito + bañito chilling",
// 					"break" : false,
// 					"duration" : 10,
// 					"with_customer" : true
// 				},
// 				{
// 					"name" : "Cutting",
// 					"break" : false,
// 					"duration": 30,
// 					"with_customer" : true
// 				}
// 			]
// 		},
// 		"relationships" : {
// 			"merchant" : {
// 				"data" : {
// 					"type" : "merchants",
// 					"id" : "fb146049-5f1b-46c5-8215-f938e7e3cb10"
// 				}
// 			}
// 		}
// 	}
// }';

// $data2 = '{
//     "data" : {
//         "id": "6fe26500-dc9b-4ed3-ab41-aad3a023dfaa",
// 		"type" : "appointments",
// 		"attributes" : {
// 			"title" : "Pelito Actualisadooooo",
// 			"starts_at" : "2016-11-30T15:23:21Z",
// 			"no_show": true,
// 			"steps" : [
// 				{
// 					"name" : "Working step",
// 					"break" : false,
// 					"with_customer" : true,
// 					"duration" : 60,
// 					"resource_ids" : [],
// 					"employee_selected_by": "employee"
// 				}
// 			]
// 		}
// 	}
// }';


// $data2 = '{
// 	"data": {
// 		"type": "services",
// 		"id": "0aa93d81-0591-40d3-8942-bc13638bf506",
// 		"attributes": {
//             "steps": [
//                 {
//                     "name": "Partido con los panas",
//                     "duration": 3
//                 },
//                 {
//                     "name": "Furbo",
//                     "type": "ocio",
//                     "duration": 30
//                 }
//             ],
//             "description": "Descripcion cambiada"
// 		}
// 	}
// }';

// var_dump($p->post('services', $data));

// var_dump($p->patch('appointments', '6fe26500-dc9b-4ed3-ab41-aad3a023dfaa', $data2));
// var_dump($p->patch('services', '0aa93d81-0591-40d3-8942-bc13638bf506', $data2));
