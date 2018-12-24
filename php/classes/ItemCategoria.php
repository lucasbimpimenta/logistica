<?php

    class ItemCategoria {

        const TABELA = '[DB7065001].[dbo].[item_categoria]';

        private $NU_ID;
        private $NO_CATEGORIA;

        public function setId($NU_ID)           { $this->NU_ID = $NU_ID; }
        public function setNome($NO_CATEGORIA)  { $this->NO_CATEGORIA = $NO_CATEGORIA; }

        public function getId()     { return $this->NU_ID; }
        public function getNome()   { return $this->NO_CATEGORIA; }

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
            
            $this->NO_CATEGORIA  = $linha['NO_CATEGORIA'];
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            NU_ID, 
                            NO_CATEGORIA
                        ) VALUES 
                        (
                            :NU_ID, 
                            :NO_CATEGORIA
                        )";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->bindValue(':NO_CATEGORIA', $this->NO_CATEGORIA);

            return $stmt->execute();
        }

        public function atualizar()
        {
            $query = "UPDATE ". self::TABELA . " 
                        SET 
                            NO_CATEGORIA = :NO_CATEGORIA
                        WHERE NU_ID = :NU_ID";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NO_CATEGORIA', $this->NO_CATEGORIA);
            $stmt->bindValue(':NU_ID', $this->NU_ID);

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

    }
?>