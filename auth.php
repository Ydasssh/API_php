<?php 
require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

$_auth = new auth;
$_respuestas = new respuestas;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // recibir datos
    $postbody = file_get_contents("php://input");

    // enviamos los datos al manejador
    $datosArray = $_auth->login($postbody);

    // devolvemos una respuesta
    header('Content-Type: aaplication/json');
    if(isset($datosArray['result']['error_id'])){
        $responsecode = $datosArray['result']['error_id'];
        http_response_code($responsecode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);

}else{
    header('Content-Type: aaplication/json');
    $datosArray = $_respuestas -> error_405();
    echo json_encode($datosArray);
}






?>