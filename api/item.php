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
                $retorno = Util::objetoParaArray(new Item($id));
            }
            else
            {
                $retorno = Item::listar();
            }

        break;

        case 'POST':

            parse_str(file_get_contents('php://input'), $data);

            $item_categoria = new Item();
            $item_categoria->setId($data['Id']);
            $item_categoria->setCategoria(new ItemCategoria($data['Categoria']));
            $item_categoria->setDescricao($data['Descricao']);
            $item_categoria->setUnidadeMedida($data['UnidadeMedida']);

            if($item_categoria->inserir())
            {
                $retorno = array(
                    'status' => 1,
                    'status_message' =>'Item cadastrado com sucesso!',
                    'id' => $item_categoria->getId()
                );
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao cadastrar item'
                );
            }

        break;

        case 'PUT':

            parse_str(file_get_contents('php://input'), $data);
            //print_r($data);

            if(!empty($data['Id']))
            {
                $item_categoria = new Item($data['Id']);

                if(in_array('Categoria', array_keys($data))) $item_categoria->setCategoria(new ItemCategoria($data['Categoria']));
                if(in_array('Descricao', array_keys($data))) $item_categoria->setDescricao($data['Descricao']);
                if(in_array('UnidadeMedida', array_keys($data))) $item_categoria->setUnidadeMedida($data['UnidadeMedida']);

                if($item_categoria->atualizar())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Item atualizado com sucesso!',
                        'id' => $item_categoria->getId()
                    );
                }
                else
                {
                    $retorno = array(
                        'status' => 0,
                        'status_message' =>'Erro ao atualizar item'
                    );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao atualizar item. Identificador não informado'
                );
            }

        break;

        case 'DELETE':

            parse_str(file_get_contents('php://input'), $data);

            if(!empty($data['Id']))
            {
                $item_categoria = new Item($data['Id']);

                if($item_categoria->excluir())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Item excluido com sucesso.'
                    );
                }
                else
                {
                    $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir item.'
                );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir item. Identificador não foi informado'
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