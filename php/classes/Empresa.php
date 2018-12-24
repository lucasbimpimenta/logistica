<?php

    class Empresa {

        const TABELA = '[DB7065001].[dbo].[empresa]';

        private $NU_CNPJ;
        private $NO_RAZAO_SOCIAL;
        private $NO_FANTASIA;
        private $DE_ENDERECO;
        private $DE_TELEFONE;
        private $DE_EMAIL;

        public function setCNPJ($NU_CNPJ)                   { $this->NU_CNPJ = $NU_CNPJ; }
        public function setRazaoSocial($NO_RAZAO_SOCIAL)    { $this->NO_RAZAO_SOCIAL = $NO_RAZAO_SOCIAL; }
        public function setNomeFantasia($NO_FANTASIA)       { $this->NO_FANTASIA = $NO_FANTASIA; }
        public function setEndereco($DE_ENDERECO)           { $this->DE_ENDERECO = $DE_ENDERECO; }
        public function setTelefone($DE_TELEFONE)           { $this->DE_TELEFONE = $DE_TELEFONE; }
        public function setEmail($DE_EMAIL)                 { $this->DE_EMAIL = $DE_EMAIL; }

        public function getCNPJ()           { return $this->NU_CNPJ; }
        public function getRazaoSocial()    { return $this->NO_RAZAO_SOCIAL; }
        public function getNomeFantasia()   { return $this->NO_FANTASIA; }
        public function getEndereco()       { return $this->DE_ENDERECO; }
        public function getTelefone()       { return $this->DE_TELEFONE; }
        public function getEmail()          { return $this->DE_EMAIL; }

        function __construct($NU_CNPJ = false) {

            if($NU_CNPJ) {
                $this->NU_CNPJ = $NU_CNPJ;
                $this->carregar();
            }
        }

        public function carregar()
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE NU_CNPJ = :NU_CNPJ";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_CNPJ', $this->NU_CNPJ, PDO::PARAM_INT);
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->NO_RAZAO_SOCIAL  = $linha['NO_RAZAO_SOCIAL'];
            $this->NO_FANTASIA      = $linha['NO_FANTASIA'];
            $this->DE_ENDERECO      = $linha['DE_ENDERECO'];
            $this->DE_TELEFONE      = $linha['DE_TELEFONE'];
            $this->DE_EMAIL         = $linha['DE_EMAIL'];
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            NU_CNPJ, 
                            NO_RAZAO_SOCIAL, 
                            NO_FANTASIA, 
                            DE_ENDERECO, 
                            DE_TELEFONE, 
                            DE_EMAIL
                        ) VALUES 
                        (
                            :NU_CNPJ, 
                            :NO_RAZAO_SOCIAL, 
                            :NO_FANTASIA, 
                            :DE_ENDERECO, 
                            :DE_TELEFONE, 
                            :DE_EMAIL
                        )";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_CNPJ', $this->NU_CNPJ, PDO::PARAM_INT);
            $stmt->bindValue(':NO_RAZAO_SOCIAL', $this->NO_RAZAO_SOCIAL);
            $stmt->bindValue(':NO_FANTASIA', $this->NO_FANTASIA);
            $stmt->bindValue(':DE_ENDERECO', $this->DE_ENDERECO);
            $stmt->bindValue(':DE_TELEFONE', $this->DE_TELEFONE);
            $stmt->bindValue(':DE_EMAIL', $this->DE_EMAIL);

            return $stmt->execute();
        }

        public function atualizar()
        {
            $query = "UPDATE ". self::TABELA . " 
                        set 
                            NO_RAZAO_SOCIAL = :NO_RAZAO_SOCIAL
                            ,NO_FANTASIA = :NO_FANTASIA
                            ,DE_ENDERECO = :DE_ENDERECO
                            ,DE_TELEFONE = :DE_TELEFONE
                            ,DE_EMAIL = :DE_EMAIL
                        WHERE NU_CNPJ = :NU_CNPJ";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NO_RAZAO_SOCIAL', $this->NO_RAZAO_SOCIAL);
            $stmt->bindValue(':NO_FANTASIA', $this->NO_FANTASIA);
            $stmt->bindValue(':DE_ENDERECO', $this->DE_ENDERECO);
            $stmt->bindValue(':DE_TELEFONE', $this->DE_TELEFONE);
            $stmt->bindValue(':DE_EMAIL', $this->DE_EMAIL);
            $stmt->bindValue(':NU_CNPJ', $this->NU_CNPJ, PDO::PARAM_INT);

            return $stmt->execute();
        }

        public function excluir()
        {
            $query = "DELETE FROM ". self::TABELA . " WHERE NU_CNPJ = :NU_CNPJ";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_CNPJ', $this->NU_CNPJ, PDO::PARAM_INT);
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