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
                $retorno = Util::objetoParaArray(new RegistroPrecoItem($id));
            }
            else
            {
                $retorno = RegistroPrecoItem::listar();
            }

        break;

        case 'POST':

            parse_str(file_get_contents('php://input'), $data);

            $registro_preco = new RegistroPrecoItem();
            $registro_preco->setRegistroPreco(new RegistroPreco($data['RegistroPreco']));
            $registro_preco->setItem(new Item($data['Item']));
            $registro_preco->setValorMaoDeObra($data['MaoDeObra']);
            $registro_preco->setValorMaterial($data['Material']);
            $registro_preco->setQuantidade($data['Quantidade']);

            if($registro_preco->inserir())
            {
                $retorno = array(
                    'status' => 1,
                    'status_message' =>'Item de Registro de preço cadastrado com sucesso!',
                    'id' => $registro_preco->getId()
                );
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao cadastrar item de  registro de preço'
                );
            }

        break;

        case 'PUT':

            parse_str(file_get_contents('php://input'), $data);
            //print_r($data);

            if(!empty($data['Id']))
            {
                $registro_preco = new RegistroPrecoItem($data['Id']);

                if(in_array('Empresa', array_keys($data)))      $registro_preco->setRegistroPreco(new RegistroPreco($data['RegistroPreco']));
                if(in_array('MaoDeObra', array_keys($data)))    $registro_preco->setValorMaoDeObra($data['MaoDeObra']);
                if(in_array('Material', array_keys($data)))     $registro_preco->setValorMaterial($data['Material']);
                if(in_array('Quantidade', array_keys($data)))   $registro_preco->setQuantidade($data['Quantidade']);

                if($registro_preco->atualizar())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Item de Registro de preço atualizado com sucesso!',
                        'id' => $registro_preco->getId()
                    );
                }
                else
                {
                    $retorno = array(
                        'status' => 0,
                        'status_message' =>'Erro ao atualizar item de  registro de preço'
                    );
                }
            }
            else
            {
                $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao atualizar item de  registro de preço. Identificador não informado'
                );
            }

        break;

        case 'DELETE':

            parse_str(file_get_contents('php://input'), $data);

            if(!empty($data['Id']))
            {
                $registro_preco = new RegistroPrecoItem($data['Id']);

                if($registro_preco->excluir())
                {
                    $retorno = array(
                        'status' => 1,
                        'status_message' =>'Item de Registro de Preço excluido com sucesso.'
                    );
                }
                else
                {
                    $retorno = array(
                    'status' => 0,
                    'status_message' =>'Erro ao excluir item de registro de preço.'
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