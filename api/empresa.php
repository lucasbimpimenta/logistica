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
                $retorno = Util::objetoParaArray(new Empresa($id));
            }
            else
            {
				$cod = 200;
                $retorno = Empresa::listar();
            }

        break;

        case 'POST':

			$data = json_decode(file_get_contents('php://input'), true);
			
            $empresa = new Empresa();
            $empresa->setCNPJ($data['NU_CNPJ']);
            $empresa->setRazaoSocial($data['NO_RAZAO_SOCIAL']);
            $empresa->setNomeFantasia($data['NO_FANTASIA']);
            $empresa->setEndereco($data['DE_ENDERECO']);
            $empresa->setTelefone($data['DE_TELEFONE']);
            $empresa->setEmail($data['DE_EMAIL']);

            if($empresa->inserir())
            {
				$cod = 201;
                $retorno = array(
                    'status' => 1,
                    'status_message' =>'Empresa cadastrada com sucesso!',
                    'id' => $empresa->getCNPJ()
                );
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao cadastrar empresa'
                );
            }

        break;

        case 'PUT':

            $data = json_decode(file_get_contents('php://input'), true);
            //print_r($data);

            if(!empty($data['NU_CNPJ']))
            {
                $empresa = new Empresa($data['NU_CNPJ']);
                if(in_array('NO_RAZAO_SOCIAL', array_keys($data))) $empresa->setRazaoSocial($data['NO_RAZAO_SOCIAL']);
                if(in_array('NO_FANTASIA', array_keys($data)))$empresa->setNomeFantasia($data['NO_FANTASIA']);
                if(in_array('DE_ENDERECO', array_keys($data)))$empresa->setEndereco($data['DE_ENDERECO']);
                if(in_array('DE_TELEFONE', array_keys($data)))$empresa->setTelefone($data['DE_TELEFONE']);
                if(in_array('DE_EMAIL', array_keys($data)))$empresa->setEmail($data['DE_EMAIL']);

                if($empresa->atualizar())
                {
					$cod = 200;
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Empresa atualizada com sucesso!',
                        'id' => $empresa->getCNPJ()
                    );
                }
                else
                {
                    $retorno = array(
                        'status' => 0,
                        'status_message' =>'Erro ao atualizar empresa'
                    );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao atualizar empresa. Identificador não informado'
                );
            }

        break;

        case 'DELETE':

            $data = json_decode(file_get_contents('php://input'), true);

            if(!empty($data['NU_CNPJ']))
            {
                $empresa = new Empresa($data['NU_CNPJ']);

                if($empresa->excluir())
                {
					$cod = 204;
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Empresa excluida com sucesso.'
                    );
                }
                else
                {
                    $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir empresa.'
                );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir empresa. Identificador não foi informado'
                );
            }

        break;

        default:
            // Invalid Request Method
            header("HTTP/1.0 405 Method Not Allowed");
        break;
    }

	http_response_code($cod);
    header('Content-Type: application/json');
    echo json_encode($retorno);

?>