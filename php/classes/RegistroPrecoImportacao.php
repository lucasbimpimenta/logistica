<?php

    require_once(__DIR__ . '/../vendors/excel-reader/php-excel-reader/excel_reader2.php');
	require_once(__DIR__ . '/../vendors/excel-reader/SpreadsheetReader.php');

    class RegistroPrecoImportacao {

        private $arquivo;
        private $cnpj;
        private $rp;
        private $itens_codigos;
        private $itens = array();

        public function getCNPJ() { return $this->cnpj; }
        public function getItens() { return $this->itens; }

        function __construct($arquivo, RegistroPreco $rp) {
            $this->arquivo = $arquivo;
            $this->rp = $rp;
            $this->itens_codigos = Item::getCodigosAtuais();
            $this->preProcessamento();
        }

        public function preProcessamento() {
            $Reader = new SpreadsheetReader($this->arquivo);
            foreach ($Reader as $Row)
            {
                $this->cnpj = $this->localizaCNPJ($Row);
                $this->processaLinhaItem($Row);
            }
        }
        
        private function localizaCNPJ($linha)
        {
            foreach($linha as $cel){
                preg_match('/\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}/', $cel, $matches);
                if(count($matches) == 1)
                    return $matches[0];
            }
        }

        private function processaLinhaItem($row) {
            
            if(in_array(mb_strtoupper(trim($row[1])), $this->itens_codigos) && floatval(Util::numeroParaSQL($row[4])) > 0 && floatval(trim($row[5])) && floatval(trim($row[6])))
            {
                print_r($row);
                $rp_item = new RegistroPrecoItem($this->rp, new Item(mb_strtoupper(trim($row[1]))));
                $rp_item->setQuantidade(floatval(preg_replace("/[^0-9.,]/", "", (str_ireplace(',','', $row[4])))));
                $rp_item->setValorMaoDeObra(floatval(str_ireplace(',','', ($row[5]))));
                $rp_item->setValorMaterial(floatval(str_ireplace(',','', ($row[6]))));

                if(!in_array($rp_item, $this->itens, true))
                    $this->itens[] = $rp_item;
            }
            /*
            elseif(preg_match('/[a-zA-Z]{1}\d{3}/', $row[1]))
            {
                print_r($row);
            }
            */
        }

        public function salvar(){
            foreach($this->itens as $item){
                $item->inserir();
            }
        }
        
    }
?>