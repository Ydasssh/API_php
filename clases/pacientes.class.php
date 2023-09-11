<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pacientes extends conexion{

    private $tabla = "pacientes";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";

    public function listaPacientes($pagina = 1){
        $inicio = 0;
        $cantidad = 50;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }

        $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM " . $this->tabla ." limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function obtenerPaciente($id){
        
        $query = "SELECT * FROM ". $this -> tabla . " WHERE PacienteId = '$id'";
        return parent::obtenerDatos($query);
    }

    // Manejador
    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        // Validar campos
        if(!isset($datos['nombre']) || !isset($datos['correo']) || !isset($datos['dni'])){
            return $_respuestas->error_400();
        }else{
            $this->nombre = $datos['nombre'];
            $this->dni = $datos['dni'];
            $this->correo = $datos['correo'];

            if(isset($datos['telefono'])){$this->telefono = $datos['telefono'];}
            if(isset($datos['direccion'])){$this->direccion = $datos['direccion'];}
            if(isset($datos['codigoPostal'])){$this->codigoPostal = $datos['codigoPostal'];}
            if(isset($datos['genero'])){$this->genero = $datos['genero'];}
            if(isset($datos['fechaNacimiento'])){$this->fechaNacimiento = $datos['fechaNacimiento'];}
            $resp = $this->insertarPaciente();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta['result'] = array(
                    "pacienteId" => $resp
                );
                return $resp;
            }else{
                return $_respuestas->error_500();
            }
        }
    }

    private function insertarPaciente(){
        $query = "INSERT INTO " . $this -> tabla . " (DNI, Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo)
        values
        ('" . $this->dni . "','" .$this->nombre . "','" . $this->direccion ."','" . $this->codigoPostal . "' , '" . $this->telefono . "','" . $this->genero . "', '" . $this->fechaNacimiento . "','" . $this->correo ."')";
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }

    }

    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);
        // Validar campos
        if(!isset($datos['pacienteId'])){
            return $_respuestas->error_400();
        }else{
            $this->pacienteId = $datos['pacienteId'];
            if(isset($datos['nombre'])){$this->nombre = $datos['nombre'];}
            if(isset($datos['dni'])){$this->dni = $datos['dni'];}
            if(isset($datos['correo'])){$this->correo = $datos['correo'];}
            if(isset($datos['telefono'])){$this->telefono = $datos['telefono'];}
            if(isset($datos['direccion'])){$this->direccion = $datos['direccion'];}
            if(isset($datos['codigoPostal'])){$this->codigoPostal = $datos['codigoPostal'];}
            if(isset($datos['genero'])){$this->genero = $datos['genero'];}
            if(isset($datos['fechaNacimiento'])){$this->fechaNacimiento = $datos['fechaNacimiento'];}

            $resp = $this->modificarPaciente();
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta['result'] = array(
                    "pacienteId" => $resp
                );
                return $resp;
            }else{
                return $_respuestas->error_500();
            }
        }
    }

    private function modificarPaciente(){
        $query = "UPDATE " . $this -> tabla . " SET Nombre = '" . $this->nombre . "', Direccion = '" . $this->direccion . "', DNI = '" . $this->dni . "', CodigoPostal = '" . $this->codigoPostal . "',Telefono ='" . $this->telefono . "',Genero = '" . $this->genero . "', FechaNacimiento = '" . $this->fechaNacimiento . "', Correo = '" . $this->correo .
        "' WHERE PacienteId = '" . $this->pacienteId . "'";

        $resp = parent::nonQuery($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }

    }




}



?>
