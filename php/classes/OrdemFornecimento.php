<?php

    class OrdemFornecimento {

        const TABELA = '[DB7065001].[dbo].[ordem_fornecimento]';

        private $NU_ID;
        private $NU_UNIDADE;
        private $DT_PEDIDO;
        private $NU_SEQ;
        private $OBJ_REGISTRO_PRECO;
        private $OBJ_PRAZO = null;

        private $ITENS = array();

        public function setId($NU_ID)                { $this->NU_ID = $NU_ID; }
        public function setUnidade($NU_UNIDADE)      { $this->NU_UNIDADE = $NU_UNIDADE; }
        public function setData(Datetime $DT_PEDIDO) { $this->DT_PEDIDO = $DT_PEDIDO; }
      //public function setNumero($NU_SEQ)             { $this->NU_SEQ = $NU_SEQ; }

        public function setRegistroPreco(RegistroPreco $OBJ_REGISTRO_PRECO){ $this->OBJ_REGISTRO_PRECO = $OBJ_REGISTRO_PRECO; }
        public function setPrazo(OrdemFornecimentoPrazo $OBJ_PRAZO = null) { $this->OBJ_PRAZO = $OBJ_PRAZO; }

        public function getId()      { return $this->NU_ID; }
        public function getUnidade() { return $this->NU_UNIDADE; }
        public function getData()    { return $this->DT_PEDIDO; }
        public function getNumero()  { return $this->NU_SEQ; }

        public function getRegistroPreco()  { return $this->OBJ_REGISTRO_PRECO; }
        public function getPrazo()           { return $this->OBJ_PRAZO; }

        function __construct() {

            $arguments = func_get_args();
            $num = sizeof($arguments);

            if($num > 0) {
                if($num == 1) {
                    $this->NU_ID = $arguments[0];
                    $this->carregarPorID();
                    $this->carregaItens();
                }
                else {
                    $this->carregar($arguments[0], $arguments[1]);
                    $this->carregaItens();
                }
            }
        }

        public function carregar(RegistroPreco $OBJ_REGISTRO_PRECO, $NU_SEQ) {

            $this->OBJ_REGISTRO_PRECO = $OBJ_REGISTRO_PRECO;
            $this->NU_SEQ = $NU_SEQ;

            $query = "SELECT * FROM ". self::TABELA . " WHERE FK_NU_ID_RP = :FK_NU_ID_RP AND NU_SEQ = :NU_SEQ";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':FK_NU_ID_RP', $this->OBJ_REGISTRO_PRECO->getId());
            $stmt->bindValue(':NU_SEQ', $this->NU_SEQ);
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->NU_ID                = $linha['NU_ID'];
            $this->NU_UNIDADE           = $linha['NU_UNIDADE'];
            $this->DT_PEDIDO            = new Datetime($linha['DT_PEDIDO']);
            $this->OBJ_PRAZO            = new OrdemFornecimentoPrazo($linha['FK_NU_ID_PRAZO']);
        }

        public function carregarPorID()
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->NU_UNIDADE           = $linha['NU_UNIDADE'];
            $this->DT_PEDIDO            = new Datetime($linha['DT_PEDIDO']);
            $this->NU_SEQ               = $linha['NU_SEQ'];
            $this->OBJ_REGISTRO_PRECO   = new RegistroPreco($linha['FK_NU_ID_RP']);
            $this->OBJ_PRAZO            = new OrdemFornecimentoPrazo($linha['FK_NU_ID_PRAZO']);
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            NU_UNIDADE, 
                            DT_PEDIDO, 
                            NU_SEQ, 
                            FK_NU_ID_RP,
                            FK_NU_ID_PRAZO
                        )
                        SELECT
                            :NU_UNIDADE, 
                            :DT_PEDIDO,
                            COALESCE(MAX(NU_SEQ) + 1,1),
                            :FK_NU_ID_RP,
                            :FK_NU_ID_PRAZO
                        FROM ". self::TABELA . " WHERE FK_NU_ID_RP = :FK_NU_ID_RP2
            ";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_UNIDADE', $this->NU_UNIDADE);
            $stmt->bindValue(':DT_PEDIDO', $this->DT_PEDIDO->format('Y-m-d'));
            //$stmt->bindValue(':NU_SEQ', $this->NU_SEQ);
            $stmt->bindValue(':FK_NU_ID_RP', $this->OBJ_REGISTRO_PRECO->getId());
            $stmt->bindValue(':FK_NU_ID_PRAZO', (is_a($this->OBJ_PRAZO, 'OrdemFornecimentoPrazo') ? $this->OBJ_PRAZO->getId() : null));
            $stmt->bindValue(':FK_NU_ID_RP2', $this->OBJ_REGISTRO_PRECO->getId());

            if($stmt->execute())
            {
                $sth_id = $conexao->query("SELECT CAST(COALESCE(SCOPE_IDENTITY(), @@IDENTITY) AS int)");
                $sth_id->execute();

                $result = $sth_id->fetch(PDO::FETCH_NUM);

                $this->NU_ID = $result[0];

                $this->salvarItens();
            }
        }

        public function atualizar()
        {
            $query = "UPDATE ". self::TABELA . " 
                        set 
                            NU_UNIDADE = :NU_UNIDADE
                            ,FK_NU_ID_PRAZO = :FK_NU_ID_PRAZO
                        WHERE NU_ID = :NU_ID";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_UNIDADE', $this->NU_UNIDADE);
            $stmt->bindValue(':FK_NU_ID_PRAZO', $this->OBJ_PRAZO->getId());
            $stmt->bindValue(':NU_ID', $this->NU_ID);

            if($stmt->execute())
            {
                $this->salvarItens();
            }
        }

        public function excluir()
        {
            $query = "DELETE FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->execute();
        }

        public static function listar()
        {
            $query = "SELECT * FROM ". self::TABELA . " ";
            $conexao = Conexao::pegarConexao();
            $resultado = $conexao->query($query);
            $lista = $resultado->fetchAll();
            return $lista;
        }

        public function carregaItens() {

            $itens_atuais = OrdemFornecimentoItem::listarPorOF($this);

            foreach($itens_atuais as $item) {
                $this->ITENS[] = new OrdemFornecimentoItem($this, new RegistroPrecoItem($item['FK_NU_ID_RP_ITEM']));
            }
        }
        
        public function addItem(RegistroPrecoItem $rp_item, $qtde){

            $item = new OrdemFornecimentoItem($this, $rp_item);
            $item->setQuantidade($qtde);
            $item->setOrdemFornecimento($this);
            $item->setItem($rp_item);

            foreach($this->ITENS as $k_ia => $item_atual) {

                echo "</br> item_atual " . $item_atual->getItem()->getItem()->getId();
                echo "</br> item " . $item->getItem()->getItem()->getId();

                if($item_atual->getItem()->getItem()->getId() == $item->getItem()->getItem()->getId())
                    unset($this->ITENS[$k_ia]);
            }

            $this->ITENS[] = $item; 

            $this->atualizaPrazo();
        }

        private function salvarItens() {
            OrdemFornecimentoItem::excluirTodosOF($this);
            foreach($this->ITENS as $item) {
                $item->inserir();
            }
        }

        public function getTotalItens() {
            return count($this->ITENS);
        }

        public function getTotalMacroItens() {
            $macro_itens = array();

            foreach($this->ITENS as $item) {
                $categoria = $item->getItem()->getItem()->getCategoria()->getId();
                if(!in_array($categoria, $macro_itens, true))
                    $macro_itens[] = $categoria;
            }
            
            return count($macro_itens);
        }

        public function getValorTotal() {
            $TOTAL = 0;
            foreach($this->ITENS as $item) {
                $rp_item = $item->getItem();
                $TOTAL += ($item->getQuantidade() * $rp_item->getValorMaoDeObra()) + ($item->getQuantidade() * $rp_item->getValorMaterial());
            }
            return $TOTAL;
        }

        public function getValorBDI() {

            return $this->getValorTotal() * $this->OBJ_REGISTRO_PRECO->getBDI();
        }

        public function getValorTotalComBDI() {

            return $this->getValorTotal() + $this->getValorBDI();
        }

        private function atualizaPrazo() {
            $this->OBJ_PRAZO = OrdemFornecimentoPrazo::calculaPrazo($this);
        }
    }

?>