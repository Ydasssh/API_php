<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['page'])){
        $pagina = $_GET['page'];
        $listaPacientes = $_pacientes -> listaPacientes($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaPacientes);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $pacienteId = $_GET['id'];
        $datosPaciente = $_pacientes -> obtenerPaciente($pacienteId);
        header("Content-Type: application/json");
        echo json_encode($datosPaciente); 
        http_response_code(200);
    }
    

}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    // Enviamos los datos al manejador
    $resp = $_pacientes->post($postBody);
    print_r($resp);


}else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
    echo "Hola PUT";
}else if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    echo "Hola DELETE";
}else{
    header('Content-Type: aaplication/json');
    $datosArray = $_respuestas -> error_405();
    echo json_encode($datosArray);
}






?>