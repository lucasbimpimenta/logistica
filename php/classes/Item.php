<?php

    class Item {

        const TABELA = '[DB7065001].[dbo].[item]';

        private $NU_ID;
        private $OBJ_CATEGORIA;
        private $DE_ITEM;
        private $CO_UNID_MEDIDA;

        public function setId($NU_ID)                               { $this->NU_ID = $NU_ID; }
        public function setCategoria(ItemCategoria $OBJ_CATEGORIA)  { $this->OBJ_CATEGORIA = $OBJ_CATEGORIA; }
        public function setDescricao($DE_ITEM)                      { $this->DE_ITEM = $DE_ITEM; }
        public function setUnidadeMedida($CO_UNID_MEDIDA)           { $this->CO_UNID_MEDIDA = $CO_UNID_MEDIDA; }

        public function getId()             { return trim($this->NU_ID); }
        public function getCategoria()      { return $this->OBJ_CATEGORIA; }
        public function getDescricao()      { return trim($this->DE_ITEM); }
        public function getUnidadeMedida()  { return $this->CO_UNID_MEDIDA; }

        function __construct($NU_ID = false) {

            if($NU_ID) {
                $this->NU_ID = $NU_ID;
                $this->carregar();
            }
        }

        public function carregar()
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->OBJ_CATEGORIA    = new ItemCategoria($linha['FK_NU_ID_CATEGORIA']);
            $this->DE_ITEM          = $linha['DE_ITEM'];
            $this->CO_UNID_MEDIDA   = $linha['CO_UNID_MEDIDA'];
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            NU_ID, 
                            FK_NU_ID_CATEGORIA,
                            DE_ITEM,
                            CO_UNID_MEDIDA
                        ) VALUES 
                        (
                            :NU_ID, 
                            :FK_NU_ID_CATEGORIA,
                            :DE_ITEM,
                            :CO_UNID_MEDIDA
                        )";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->bindValue(':FK_NU_ID_CATEGORIA', $this->OBJ_CATEGORIA->getId());
            $stmt->bindValue(':DE_ITEM', $this->DE_ITEM);
            $stmt->bindValue(':CO_UNID_MEDIDA', $this->CO_UNID_MEDIDA);

            return $stmt->execute();
        }

        public function atualizar()
        {
            $query = "UPDATE ". self::TABELA . " 
                        SET 
                            FK_NU_ID_CATEGORIA = :FK_NU_ID_CATEGORIA
                            ,DE_ITEM = :DE_ITEM
                            ,CO_UNID_MEDIDA = :CO_UNID_MEDIDA
                        WHERE NU_ID = :NU_ID";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':FK_NU_ID_CATEGORIA', $this->OBJ_CATEGORIA->getId());
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->bindValue(':DE_ITEM', $this->DE_ITEM);
            $stmt->bindValue(':CO_UNID_MEDIDA', $this->CO_UNID_MEDIDA);

            return $stmt->execute();
        }

        public function excluir()
        {
            $query = "DELETE FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            return $stmt->execute();
        }

        public static function listar()
        {
            $query = "SELECT * FROM ". self::TABELA . " ";
            $conexao = Conexao::pegarConexao();
            $resultado = $conexao->query($query);
            $lista = $resultado->fetchAll();
            return $lista;
        }

        public static function getCodigosAtuais()
        {
            $codigos = array();

            $itens = Item::listar();

            foreach($itens as $item)
                $codigos[] = trim(mb_strtoupper($item['NU_ID']));

            return $codigos;
        }
    }
?>