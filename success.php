<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Franquicias Miscota</title>
    <link rel='stylesheet' href='css/success.css'>
    <link href='img/605c45aab5fd727b9808d3ea_5d76e1ec344fac836f08e407_favicon32.png' rel='shortcut icon' type='image/x-icon'>
</head>
<body>
    <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'mail/Exception.php';
        require 'mail/PHPMailer.php';
        require 'mail/SMTP.php';
        require_once 'vendor/autoload.php';
        require_once 'shore.php';

        require_once "vendor/autoload.php";
        $dotenv = new Symfony\Component\Dotenv\Dotenv();
        try {
            $dotenv->usePutenv()->load('.env');
        } catch (Exception $e) {
        }

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

        $coste = $_POST['coste'];
        $coste_format = $coste/100;

        $dateTime = new DateTime($fecha_obtenida);
        $dateFormated = $dateTime->format(DateTime::ATOM);

        $arreglo = $p->get('services', $service_id);

        $name_service = $arreglo->data->attributes->name;
        // $service_form = utf8_decode($name_service);

        $name_merchant = $getMerchant->data->attributes->name;

        // Formulario 1
        if (isset($_POST['enviar'])){
            $nombre = $_POST['nombre'];
            $nom = utf8_decode($nombre);

            $apellidos = $_POST['apellidos'];
            $ape = utf8_decode($apellidos);

            $correo = $_POST['email'];

            $telefono = $_POST['telefono'];

            $solicitudes = $_POST['solicitudes'];
            $sol = utf8_decode($solicitudes);

            $duracionTilde = 'Duración';
            $dur = utf8_decode($duracionTilde);

            $telefonoTilde = 'Teléfono';
            $tel = utf8_decode($telefonoTilde);
            
            // Cuerpo del mensaje
            $body = "
            
            <table cellspacing='0' cellpadding='0' border='0' align='center' width='100%' style='border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important;max-width:600px'>
            <tbody><tr>
              <td bgcolor='#ffffff' align='center' height='100%' valign='top' width='100%'>
                <table border='0' cellpadding='0' cellspacing='0' align='center' width='100%' style='border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important;max-width:580px'>
                  <tbody><tr>
                    <td align='center' valign='top' width='100%'>
                      <table cellspacing='0' cellpadding='0' border='0' width='100%' style='table-layout:auto;text-align:left;font-family:'Open Sans',sans-serif;line-height:1.7;color:#555555;padding:30px 35px;border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important'>    
                        <tbody><tr>
                          <td style='text-align:left;font-family:'Open Sans',sans-serif;line-height:1.7;color:#555555;padding:30px 35px'>
                            <p style='margin-top:0;padding:0;margin-bottom:1em;color:#555555'>
                              Hola, $nom $apellidos:
                              <br>
                              Tu cita ha sido reservada.
                            </p>
    
                            <hr style='border-color:#f5f5f5;border-width:1px;border-style:solid;margin:5px'>
    
                                <table width='100%' style='table-layout:auto;border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important'>
    
                                    <tbody>
                                        <tr>
                                            <td style='width:60px;vertical-align:center'>
                                                <img width='30' src='https://ci3.googleusercontent.com/proxy/PmpKCVd1iypx6oGBCZIt5a3JjE6vDpn3ZIz7HAxI4Un6JCapb_hI0BFgkvw1jqGOYJD5khNlA_-SEAqYP_UOsXGLpg=s0-d-e1-ft#https://email-assets.shore.com/when_calendar.png' style='display:block;padding-left:10px;width:30px' class='CToWUd'>
                                            </td>
                                            <td>
                                            <h2 style='font-size:16px;font-weight:bold;margin:0;padding:0'>Cuándo:</h2>
                                            <p style='margin:0;padding:0;color:#555555'>
                                                $fecha_obtenida
                                            </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
    
                            <hr style='border-color:#f5f5f5;border-width:1px;border-style:solid;margin:5px'>
    
                                <table width='100%' style='table-layout:auto;border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important'>
                                    <tbody>
                                    <tr>
                                    <td style='width:60px;vertical-align:center'>
                                        <img width='30' src='https://ci5.googleusercontent.com/proxy/QQlH9mYiafwgy91FaebXJkxOvlJfwnHRJ0-IalaQhgVZ051OpAk8H3U0DNvfjllx2caWSXUQP0wg3bEsHlAU29fCk7k=s0-d-e1-ft#https://email-assets.shore.com/where_location.png' style='display:block;padding-left:10px;width:30px' class='CToWUd'>
                                    </td>
                                    <td>
                                        <h2 style='font-size:16px;font-weight:bold;margin:0;padding:0'>Dónde:</h2>
                                        <p style='margin:0;padding:0;color:#555555'>
                                        <a href='https://maps.google.com/maps?daddr=$calle+$city+$postal' style='color:#555555;text-decoration:none' target='_blank'>$name_merchant ($calle , $postal $city)</a>
                                        </p>
                                    </td>
                                    </tr>
                                    </tbody>
                                </table>
    
                            <hr style='border-color:#f5f5f5;border-width:1px;border-style:solid;margin:5px'>

                                <table width='100%' style='table-layout:auto;border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important'>
                                    <tbody>
                                    <tr>
                                    <td style='width:60px;vertical-align:center'>
                                        <img width='30' src='https://ci6.googleusercontent.com/proxy/OJddGDhd-3Fs-2AUVrj8EIy2t1Kev6nsAhoNkIceRe_hZrA8AU_QD0m95FggLPwBGvpIOT2dF_0o39i6As2J=s0-d-e1-ft#https://email-assets.shore.com/what_tick.png' style='display:block;padding-left:10px;width:30px' class='CToWUd'>
                                
                                    </td>
                                    <td>
                                        <h2 style='font-size:16px;font-weight:bold;margin:0;padding:0'>Qué:</h2>
                                        <p style='margin:0;padding:0;color:#555555'>
                                            $name_service ($coste_format &euro;)
                                        </p>
                                    </td>
                                    </tr>
                                    </tbody>
                                </table>

                            <hr style='border-color:#f5f5f5;border-width:1px;border-style:solid;margin:5px'>

                                <table width='100%' style='table-layout:auto;border-spacing:0!important;border-collapse:collapse!important;table-layout:fixed!important;margin:0 auto!important'>
                                    <tbody>
                                    <tr>
                                    <td style='width:60px;vertical-align:center'>
                                        <img width='30' src='https://ci4.googleusercontent.com/proxy/ObXBae1b3SKmX7pKv63ThGl12eez7K-VIUZ0qdQnuyHaGKk55Kd96xoe5DtVHSIoT4ina4ldgAxqOEt6tx1tOIhr=s0-d-e1-ft#https://email-assets.shore.com/with_user_01.png' style='display:block;padding-left:10px;width:30px' class='CToWUd'>
                                    </td>
                                    <td>
                                        <h2 style='font-size:16px;font-weight:bold;margin:0;padding:0'>Con:</h2>
                                        <p style='margin:0;padding:0;color:#555555'>
                                            $name_merchant
                                        </p>
                                    </td>
                                    </tr>
                                    </tbody>
                                </table>
    
                            <hr style='border-color:#f5f5f5;border-width:1px;border-style:solid;margin:5px'>
                        
                            </td>
                        </tr>    
                            </tbody></table>    
                            </td>
                            </tr>
                            </tbody></table>
                            </td>
                            </tr>
                        </tbody></table>
    
                    </td>
                </tr>
            </tbody></table>";

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'no.reply@buddy.eu';                    //SMTP username
                $mail->Password   = 'gR8L6s3Rv6';                           //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('no.reply@buddy.eu', $name_merchant);
                $mail->addAddress($correo);
                // $mail->addAddress('arnautrv@gmail.com');     //Add a recipient

                // $mail->addAddress('ellen@example.com');               //Name is optional
                // $mail->addReplyTo('franquicias@buddy.eu', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');
            
                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            
                //Content
                $mail->isHTML(true);   
                $subject = 'Cita reservada: '.$name_merchant;
                $tildeSubject = utf8_decode($subject);                         //Set email format to HTML
                $mail->Subject = $tildeSubject;
                $tildeBody = utf8_decode($body); 
                $mail->Body    = $tildeBody;
                // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
                $mail->send();
                // echo alert('El mensaje se envió correctamente');
            } catch (Exception $e) {
                echo "Hubo un error al enviar el mensaje: {$mail->ErrorInfo}";
            }
        }
    ?>
    <div class='seccion-success'>
        <div class='block-success'>
            <img src='img/60892a43bee4f8231ed56362_logo-miscota-blanco.svg' alt='' class='logo'> 
            <h2 class='texto-seccion1'>
                ¡Gracias! Tu reserva se ha realizado con exito. Te mandaremos un mail en breves momentos con los datos de la reserva.
            </h2>
        </div>
    </div>

</body>
</html>