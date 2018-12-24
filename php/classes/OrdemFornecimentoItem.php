<?php

    class OrdemFornecimentoItem {

        const TABELA = '[DB7065001].[dbo].[ordem_fornecimento_item]';

        private $NU_ID;
        private $OBJ_ORDEM_FORNECIMENTO;
        private $OBJ_RP_ITEM;
        private $NU_QTDE;

        public function setId($NU_ID)           { $this->NU_ID = $NU_ID; }
        public function setQuantidade($NU_QTDE) { $this->NU_QTDE = $NU_QTDE; }
        public function setOrdemFornecimento(OrdemFornecimento $OBJ_ORDEM_FORNECIMENTO){ $this->OBJ_ORDEM_FORNECIMENTO = $OBJ_ORDEM_FORNECIMENTO; }
        public function setItem(RegistroPrecoItem $OBJ_RP_ITEM = null) { $this->OBJ_RP_ITEM = $OBJ_RP_ITEM; }

        public function getId()                 { return $this->NU_ID; }
        public function getQuantidade()         { return $this->NU_QTDE; }
        public function getOrdemFornecimento()  { return $this->OBJ_ORDEM_FORNECIMENTO; }
        public function getItem()               { return $this->OBJ_RP_ITEM; }

        function __construct() {

            $arguments = func_get_args();
            $num = sizeof($arguments);

            if($num > 0) {
                if($num == 1) {
                    $this->NU_ID = $arguments[0];
                    $this->carregarPorID();
                }
                else {
                    $this->carregar($arguments[0], $arguments[1]);
                }
            }
        }

        public function carregar(OrdemFornecimento $OBJ_ORDEM_FORNECIMENTO, RegistroPrecoItem $OBJ_RP_ITEM) {

            $this->OBJ_ORDEM_FORNECIMENTO = $OBJ_ORDEM_FORNECIMENTO;
            $this->OBJ_RP_ITEM = $OBJ_RP_ITEM;

            $query = "SELECT * FROM ". self::TABELA . " WHERE FK_NU_ID_OF = :FK_NU_ID_OF AND FK_NU_ID_RP_ITEM = :FK_NU_ID_RP_ITEM";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':FK_NU_ID_OF', $this->OBJ_ORDEM_FORNECIMENTO->getId());
            $stmt->bindValue(':FK_NU_ID_RP_ITEM', $this->OBJ_RP_ITEM->getId());
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->NU_ID                    = $linha['NU_ID'];
            $this->NU_QTDE                  = $linha['NU_QTDE'];
            $this->OBJ_ORDEM_FORNECIMENTO   = $OBJ_ORDEM_FORNECIMENTO;
            $this->OBJ_RP_ITEM              = $OBJ_RP_ITEM;
        }

        public function carregarPorID()
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->NU_QTDE                  = $linha['NU_QTDE'];
            $this->OBJ_ORDEM_FORNECIMENTO   = new OrdemFornecimento($linha['FK_NU_ID_OF']);
            $this->OBJ_RP_ITEM              = new RegistroPrecoItem($linha['FK_NU_ID_RP_ITEM']);
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            NU_QTDE, 
                            FK_NU_ID_OF,
                            FK_NU_ID_RP_ITEM
                        ) VALUES 
                        (
                            :NU_QTDE, 
                            :FK_NU_ID_OF,
                            :FK_NU_ID_RP_ITEM
                        )";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_QTDE', $this->NU_QTDE);
            $stmt->bindValue(':FK_NU_ID_OF', $this->OBJ_ORDEM_FORNECIMENTO->getId());
            $stmt->bindValue(':FK_NU_ID_RP_ITEM', $this->OBJ_RP_ITEM->getId());

            if($stmt->execute())
            {
                $sth_id = $conexao->query("SELECT CAST(COALESCE(SCOPE_IDENTITY(), @@IDENTITY) AS int)");
                $sth_id->execute();

                $result = $sth_id->fetch(PDO::FETCH_NUM);

                $this->NU_ID = $result[0];
            }
        }

        public function atualizar()
        {
            $query = "UPDATE ". self::TABELA . " 
                        set 
                            NU_QTDE = :NU_QTDE
                            ,FK_NU_ID_OF = :FK_NU_ID_OF
                            ,FK_NU_ID_RP_ITEM = :FK_NU_ID_RP_ITEM
                        WHERE NU_ID = :NU_ID";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_QTDE', $this->NU_QTDE);
            $stmt->bindValue(':FK_NU_ID_OF', $this->OBJ_ORDEM_FORNECIMENTO->getId());
            $stmt->bindValue(':FK_NU_ID_RP_ITEM', $this->OBJ_RP_ITEM->getId());
            $stmt->bindValue(':NU_ID', $this->NU_ID);

            $stmt->execute();
        }

        public function excluir()
        {
            $query = "DELETE FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->execute();
        }

        public static function excluirTodosOF(OrdemFornecimento $of)
        {
            $query = "DELETE FROM ". self::TABELA . " WHERE FK_NU_ID_OF = :FK_NU_ID_OF";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':FK_NU_ID_OF', $of->getId());
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

        public static function listarPorOF(OrdemFornecimento $of)
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE FK_NU_ID_OF = :FK_NU_ID_OF";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':FK_NU_ID_OF', $of->getId());
            $stmt->execute();
            $lista = $stmt->fetchAll();
            return $lista;
        }

    }

?>