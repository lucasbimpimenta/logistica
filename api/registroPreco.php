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
                $retorno = Util::objetoParaArray(new RegistroPreco($id));
            }
            else
            {
                $retorno = RegistroPreco::listar();
            }

        break;

        case 'POST':

            parse_str(file_get_contents('php://input'), $data);

            $registro_preco = new RegistroPreco();
            $registro_preco->setEmpresa(new Empresa($data['Empresa']));
            $registro_preco->setProcesso($data['Processo']);
            $registro_preco->setPregao($data['Pregao']);
            $registro_preco->setAta($data['Ata']);
            $registro_preco->setBDI($data['BDI']);
            $registro_preco->setMobilizacao($data['Mobilizacao']);

            if($registro_preco->inserir())
            {
                $retorno = array(
                    'status' => 1,
                    'status_message' =>'Registro de preço cadastrado com sucesso!',
                    'id' => $registro_preco->getId()
                );
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao cadastrar registro de preço'
                );
            }

        break;

        case 'PUT':

            parse_str(file_get_contents('php://input'), $data);
            //print_r($data);

            if(!empty($data['Id']))
            {
                $registro_preco = new RegistroPreco($data['Id']);

                if(in_array('Empresa', array_keys($data)))      $registro_preco->setEmpresa(new Empresa($data['Empresa']));
                if(in_array('Processo', array_keys($data)))     $registro_preco->setProcesso($data['Processo']);
                if(in_array('Pregao', array_keys($data)))       $registro_preco->setPregao($data['Pregao']);
                if(in_array('Ata', array_keys($data)))          $registro_preco->setAta($data['Ata']);
                if(in_array('BDI', array_keys($data)))          $registro_preco->setBDI($data['BDI']);
                if(in_array('Mobilizacao', array_keys($data)))  $registro_preco->setMobilizacao($data['Mobilizacao']);

                if($registro_preco->atualizar())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Registro de preço atualizado com sucesso!',
                        'id' => $registro_preco->getId()
                    );
                }
                else
                {
                    $retorno = array(
                        'status' => 0,
                        'status_message' =>'Erro ao atualizar registro de preço'
                    );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao atualizar registro de preço. Identificador não informado'
                );
            }

        break;

        case 'DELETE':

            parse_str(file_get_contents('php://input'), $data);

            if(!empty($data['Id']))
            {
                $registro_preco = new RegistroPreco($data['Id']);

                if($registro_preco->excluir())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Registro de Preço excluido com sucesso.'
                    );
                }
                else
                {
                    $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir registro de preço.'
                );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir registro de preço. Identificador não foi informado'
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