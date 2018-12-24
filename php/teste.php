<?php
    require_once('global.php');
    /*
    RegistroPrecoItem::excluirTodosDoRP(new RegistroPreco(3));

    $arquivo = new RegistroPrecoImportacao('../upload/Planilha SCE _Engearte Centro Oeste MG MACROITENS.XLS', new RegistroPreco(3));
    $arquivo->salvar();
    
    echo "<pre>";
    print_r($arquivo);
    echo "</pre>";
    */
    /*
    
    $rp->calculaTotais();
    echo 'getSubtotalMaoDeObra' . $rp->getSubtotalMaoDeObra() . '</br>';
    echo 'getSubtotalMaterial' . $rp->getSubtotalMaterial() . '</br>';
    echo 'getSubtotal' . $rp->getSubtotal() . '</br>';
    echo 'getMobilizacaoMaoDeObra' . $rp->getMobilizacaoMaoDeObra() . '</br>';
    echo 'getMobilizacaoMaterial' . $rp->getMobilizacaoMaterial() . '</br>';
    echo 'getMobilizacaoSubTotal' . $rp->getMobilizacaoSubTotal() . '</br>';
    echo 'getTotal' . $rp->getTotal() . '</br>';
    echo 'getValorBDI' . $rp->getValorBDI() . '</br>';
    echo 'getTotalComBDI' . $rp->getTotalComBDI() . '</br>';
    

    $rp = new RegistroPreco(3);
    $of = new OrdemFornecimento(11);

    echo "<pre>";
    print_r($rp);
    print_r($of);
    
    $of->setUnidade(intval('0115'));
    $of->setData(new Datetime());
    $of->setRegistroPreco($rp);
    
    $of->addItem(new RegistroPrecoItem($rp, new Item('C101')), 50);
    $of->addItem(new RegistroPrecoItem($rp, new Item('C102')),100);

    print_r($of);
    $of->atualizar();
    
    
    echo "Total Itens: " . $of->getTotalItens() . '</br>';
    echo "Total MacroItens: " . $of->getTotalMacroItens() . '</br>';
    echo "Total Valor: " . $of->getValorTotal() . '</br>';
    echo "Total Valor: " . $of->getValorTotalComBDI() . '</br>';

    */

    $url = 'http://www.gilogbh.des.mg.caixa/api/empresa/';

    $data = array(
                'CNPJ' => '1111111111111'
                ,'RazaoSocial' => 'value2'
                ,'NomeFantasia' => 'value2'
                ,'Endereco' => 'value2'
                ,'Telefone' => 'value2'
                ,'Email' => 'value2'
            );

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    //curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 20);

    $response = curl_exec($ch);
    curl_close($ch);

    print_r($response);

?>