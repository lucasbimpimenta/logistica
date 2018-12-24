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
                $retorno = Util::objetoParaArray(new ItemCategoria($id));
            }
            else
            {
                $retorno = ItemCategoria::listar();
            }

        break;

        case 'POST':

            parse_str(file_get_contents('php://input'), $data);

            $item_categoria = new ItemCategoria();
            $item_categoria->setId($data['Id']);
            $item_categoria->setNome($data['Nome']);

            if($item_categoria->inserir())
            {
                $retorno = array(
                    'status' => 1,
                    'status_message' =>'Categoria de Item cadastrada com sucesso!',
                    'id' => $item_categoria->getId()
                );
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao cadastrar Categoria de Item'
                );
            }

        break;

        case 'PUT':

            parse_str(file_get_contents('php://input'), $data);
            //print_r($data);

            if(!empty($data['Id']))
            {
                $item_categoria = new ItemCategoria($data['Id']);
                if(in_array('Nome', array_keys($data))) $item_categoria->setNome($data['Nome']);

                if($item_categoria->atualizar())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Categoria de Item atualizada com sucesso!',
                        'id' => $item_categoria->getId()
                    );
                }
                else
                {
                    $retorno = array(
                        'status' => 0,
                        'status_message' =>'Erro ao atualizar categoria de item'
                    );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao atualizar categoria de item. Identificador não informado'
                );
            }

        break;

        case 'DELETE':

            parse_str(file_get_contents('php://input'), $data);

            if(!empty($data['Id']))
            {
                $item_categoria = new ItemCategoria($data['Id']);

                if($item_categoria->excluir())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Categoria de Item excluida com sucesso.'
                    );
                }
                else
                {
                    $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir categoria de item.'
                );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir categoria de item. Identificador não foi informado'
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