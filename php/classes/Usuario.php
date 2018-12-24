<?php

    class Usuario() {

        private $usuario;
        private $dados;

        function __construct($usuario) {
            $this->usuario = $usuario; 
        } 

        public function dados() {
            $this->dados = LDAP::DadosUsuario($this->usuario);
            mb
        }

        private function identificaPeloServidor() {

            if(isset($_SERVER['LOGON_USER']) && trim($_SERVER['LOGON_USER']) != '') {
                $usuario_do_servidor = $_SERVER['LOGON_USER']; 
            }
            elseif(isset($_SERVER['REMOTE_USER']) && trim($_SERVER['REMOTE_USER']) != '') {
                $usuario_do_servidor = $_SERVER['REMOTE_USER']; 
            }
            elseif(isset($_SERVER['AUTH_USER']) && trim($_SERVER['AUTH_USER']) != '') {
                $usuario_do_servidor = $_SERVER['AUTH_USER']; 
            }

            $re = "/[CcAaPpMmEe][\\d]{6}/";

            if($usuario_do_servidor && preg_match($re, $usuario_do_servidor)) {

                $usuario_do_servidor = strtoupper($usuario_do_servidor);

                if(strpos($usuario_do_servidor,'\\') >= 0) { 
                    $part = explode('\\',$usuario_do_servidor);
                    $this->setMatricula(trim($part[1]));
                    $this->setDominioWindows(trim($part[0])); return true; 
                } else {
                    $this->setMatricula($usuario_do_servidor);
                    $this->setDominioWindows(null); return true; 
                } 
            } else {
                $this->setMatricula(false); 
                $this->setDominioWindows(null); 
                return false;
            }
            
        }
    }

?>