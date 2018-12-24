<?php
    require_once('../php/global.php');

    $request_method = $_SERVER["REQUEST_METHOD"];

    $retorno = array();

    switch($request_method)
    {
        case 'GET':
            
            if(!empty($_GET["id"]))
            {
                $id = $_GET["id"];
                $retorno = new Empresa($id);
            }
            else
            {
                $retorno = Empresa::listar();
            }

        break;

        case 'POST':

            $data = json_decode(file_get_contents('php://input'), true);
            if(!$data) $data = $_POST;

            $empresa = new Empresa();
            $empresa->setCNPJ($data['CNPJ']);
            $empresa->setRazaoSocial($data['RazaoSocial']);
            $empresa->setNomeFantasia($data['NomeFantasia']);
            $empresa->setEndereco($data['Endereco']);
            $empresa->setTelefone($data['Telefone']);
            $empresa->setEmail($data['Email']);

            if($empresa->inserir())
            {
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

            parse_str(file_get_contents('php://input'), $data);
            //print_r($data);

            if(!empty($data['CNPJ']))
            {
                $empresa = new Empresa($data['CNPJ']);
                if(in_array('RazaoSocial', array_keys($data))) $empresa->setRazaoSocial($data['RazaoSocial']);
                if(in_array('NomeFantasia', array_keys($data)))$empresa->setNomeFantasia($data['NomeFantasia']);
                if(in_array('Endereco', array_keys($data)))$empresa->setEndereco($data['Endereco']);
                if(in_array('Telefone', array_keys($data)))$empresa->setTelefone($data['Telefone']);
                if(in_array('Email', array_keys($data)))$empresa->setEmail($data['Email']);

                if($empresa->atualizar())
                {
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

            parse_str(file_get_contents('php://input'), $data);

            if(!empty($data['CNPJ']))
            {
                $empresa = new Empresa($data['CNPJ']);

                if($empresa->excluir())
                {
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

    header('Content-Type: application/json');
    echo json_encode($retorno);

?>