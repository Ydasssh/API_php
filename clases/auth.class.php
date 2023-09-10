<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class auth extends conexion {

    public function login($json){
        // instancia respuestas
        $_respuestas = new respuestas;
        // convertir json en array -- true = asociativo
        $datos = json_decode($json,true);

        if(!isset($datos['usuario']) || !isset($datos['contraseña'])){
            // error con los campos
            return $_respuestas->error_400();
        }else{
            // todo bien
            $usuario = $datos['usuario'];
            $contraseña = $datos['contraseña'];
            $contraseña = parent::encriptar($contraseña);
            $datos = $this -> obtenerDatosUsuarios($usuario);
            if($datos){
                //verificar si la contraseña es igual
                if($contraseña == $datos[0]['Password']){
                    if($datos[0]['Estado'] == "Activo"){
                        // Crear token
                        $verificar = $this -> insertarToken($datos[0]['UsuarioId']);
                        if($verificar){
                            //si se guardo
                            $result = $_respuestas -> response;
                            $result['result'] = array(
                                "token" => $verificar
                            );
                            return $result;
                        }else{
                            // error al guardar
                            return $_respuestas->error_500("Error interno, no se ha podido guardar");
                        }
                    }else{
                        //el usuario está inactivo
                    return $_respuestas->error_200("el usuario está inactivo");
                    }
                    
                }else{
                    //la contraseña no es igual
                    return $_respuestas->error_200("la contraseña es invalida");
                }
            }else{
                // no existe el usuario
                return $_respuestas->error_200("el $usuario no existe");
            }
        }
    }


    private function obtenerDatosUsuarios($correo){

        $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]['UsuarioId'])){
            return $datos;
        }else{
            return 0;
        }
    }

    private function insertarToken($usuarioId){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        date_default_timezone_set('America/Bogota');
        $date = date("Y-m-d h:i:s");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,TOken,Estado,Fecha)VALUES('$usuarioId','$token','$estado','$date')";
        $verificar = parent::nonQuery($query);
        if($verificar){
            return $token;
        }else{
            return 0;
        }
    }








}


?>