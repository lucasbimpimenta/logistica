<?php
    require_once('../php/global.php');

    $request_method = $_SERVER["REQUEST_METHOD"];

    $retorno = array();
	
	$cod = 400;

    switch($request_method)
    {
        case 'GET':
            
            if(!empty($_GET["id"]))
            {
				$cod = 200;
                $id = $_GET["id"];
                $retorno = Util::objetoParaArray(new Unidade($id));
            }
            else
            {
				//print_r($_GET);
				$cod = 200;
				
				if(!empty($_GET["tipo"]))
					$retorno = Unidade::listarPorTipo($_GET["tipo"]);
				else
					$retorno = Unidade::listarHieraquiaTreeview();
            }

        break;

        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

	http_response_code($cod);
    header('Content-Type: application/json');
    echo json_encode($retorno,JSON_PRETTY_PRINT);

?>