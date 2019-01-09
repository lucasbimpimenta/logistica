<?php

    class Usuario() {

        private $usuario;
        private $dados;

        function __construct($usuario) {
            $this->usuario = $usuario; 
        } 

        public function dados() {
            $this->dados = LDAP::DadosUsuario($this->usuario);
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
		
		public function token() {
			
			$header = [
			   'alg' => 'HS256',
			   'typ' => 'JWT'
			];
			$header = json_encode($header);
			$header = base64_encode($header);

			$payload = [
			   'iss' => 'mg.caixa',
			   'name' => 'Diogo',
			   'email' => 'diogo.fragabemfica@gmail.com'
			];
			$payload = json_encode($payload);
			$payload = base64_encode($payload);

			$signature = hash_hmac('sha256',"$header.$payload",'minha-senha',true);
			$signature = base64_encode($signature);

			echo "$header.$payload.$signature";	
		}
		
		public static function checkToken($token){

			$part = explode(".",$token);
			$header = $part[0];
			$payload = $part[1];
			$signature = $part[2];

			$valid = hash_hmac('sha256',"$header.$payload",'minha-senha',true);
			$valid = base64_encode($valid);

			if($signature == $valid){
			   echo "valid";
			   return true;
			}else{
			   echo 'invalid';
			}
			
			return false;
		}
    }

?>