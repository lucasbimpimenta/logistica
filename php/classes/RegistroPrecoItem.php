<?php

    class RegistroPrecoItem {

        const TABELA = '[DB7065001].[dbo].[registro_preco_item]';

        private $NU_ID;
        private $VR_MAO_OBRA;
        private $VR_MATERIAL;
        private $NU_QTDE;
        private $OBJ_REGISTRO_PRECO;
        private $OBJ_ITEM;

        public function setId($NU_ID)                       { $this->NU_ID = $NU_ID; }
        public function setValorMaoDeObra($VR_MAO_OBRA)     { $this->VR_MAO_OBRA = $VR_MAO_OBRA; }
        public function setValorMaterial($VR_MATERIAL)      { $this->VR_MATERIAL = $VR_MATERIAL; }
        public function setQuantidade($NU_QTDE)             { $this->NU_QTDE = $NU_QTDE; }

        public function setRegistroPreco(RegistroPreco $OBJ_REGISTRO_PRECO){ $this->OBJ_REGISTRO_PRECO = $OBJ_REGISTRO_PRECO; }
        public function setItem(Item $OBJ_ITEM) { $this->OBJ_ITEM = $OBJ_ITEM; }

        public function getId()             { return $this->NU_ID; }
        public function getValorMaoDeObra() { return $this->VR_MAO_OBRA; }
        public function getValorMaterial()  { return $this->VR_MATERIAL; }
        public function getQuantidade()     { return $this->NU_QTDE; }

        public function getRegistroPreco()  { return $this->OBJ_REGISTRO_PRECO; }
        public function getItem()           { return $this->OBJ_ITEM; }

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

         public function carregar(RegistroPreco $OBJ_REGISTRO_PRECO, Item $OBJ_ITEM) {

            $this->OBJ_REGISTRO_PRECO = $OBJ_REGISTRO_PRECO;
            $this->OBJ_ITEM = $OBJ_ITEM;

            $query = "SELECT * FROM ". self::TABELA . " WHERE FK_NU_ID_RP = :FK_NU_ID_RP AND FK_NU_ID_ITEM = :FK_NU_ID_ITEM";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':FK_NU_ID_RP', $this->OBJ_REGISTRO_PRECO->getId());
            $stmt->bindValue(':FK_NU_ID_ITEM', $this->OBJ_ITEM->getId());
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->NU_ID                = $linha['NU_ID'];
            $this->VR_MAO_OBRA          = $linha['VR_MAO_OBRA'];
            $this->VR_MATERIAL          = $linha['VR_MATERIAL'];
            $this->NU_QTDE              = $linha['NU_QTDE'];
        }

        public function carregarPorID()
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID);
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->VR_MAO_OBRA          = $linha['VR_MAO_OBRA'];
            $this->VR_MATERIAL          = $linha['VR_MATERIAL'];
            $this->NU_QTDE              = $linha['NU_QTDE'];
            $this->OBJ_REGISTRO_PRECO   = new RegistroPreco($linha['FK_NU_ID_RP']);
            $this->OBJ_ITEM             = new Item($linha['FK_NU_ID_ITEM']);
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            VR_MAO_OBRA, 
                            VR_MATERIAL, 
                            NU_QTDE, 
                            FK_NU_ID_RP,
                            FK_NU_ID_ITEM
                        ) VALUES 
                        (
                            :VR_MAO_OBRA, 
                            :VR_MATERIAL, 
                            :NU_QTDE, 
                            :FK_NU_ID_RP,
                            :FK_NU_ID_ITEM
                        )";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':VR_MAO_OBRA', $this->VR_MAO_OBRA);
            $stmt->bindValue(':VR_MATERIAL', $this->VR_MATERIAL);
            $stmt->bindValue(':NU_QTDE', $this->NU_QTDE);
            $stmt->bindValue(':FK_NU_ID_RP', $this->OBJ_REGISTRO_PRECO->getId());
            $stmt->bindValue(':FK_NU_ID_ITEM', $this->OBJ_ITEM->getId());

            if($stmt->execute())
            {
                $sth_id = $conexao->query("SELECT CAST(COALESCE(SCOPE_IDENTITY(), @@IDENTITY) AS int)");
                $sth_id->execute();

                $result = $sth_id->fetch(PDO::FETCH_NUM);

                $this->NU_ID = $result[0];

                return true;
            }

            return false;
        }

        public function atualizar()
        {
            $query = "UPDATE ". self::TABELA . " 
                        set 
                            VR_MAO_OBRA = :VR_MAO_OBRA
                            ,VR_MATERIAL = :VR_MATERIAL
                            ,NU_QTDE = :NU_QTDE
                            ,FK_NU_ID_RP = :FK_NU_ID_RP
                            ,FK_NU_ID_ITEM = :FK_NU_ID_ITEM
                        WHERE NU_ID = :NU_ID";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':VR_MAO_OBRA', $this->VR_MAO_OBRA);
            $stmt->bindValue(':VR_MATERIAL', $this->VR_MATERIAL);
            $stmt->bindValue(':NU_QTDE', $this->NU_QTDE);
            $stmt->bindValue(':FK_NU_ID_RP', $this->OBJ_REGISTRO_PRECO->getId());
            $stmt->bindValue(':FK_NU_ID_ITEM', $this->OBJ_ITEM->getId());
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

        public static function excluirTodosDoRP(RegistroPreco $rp)
        {
            $query = "DELETE FROM ". self::TABELA . " WHERE FK_NU_ID_RP = :FK_NU_ID_RP";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':FK_NU_ID_RP', $rp->getId());
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

        public static function itensRP(RegistroPreco $rp)
        {
            $saida = array();
            $query = "SELECT * FROM ". self::TABELA . " ";
            $conexao = Conexao::pegarConexao();
            $resultado = $conexao->query($query);
            $lista = $resultado->fetchAll();
            foreach($lista as $item)
                $saida[] = new RegistroPrecoItem($rp, new Item($item['FK_NU_ID_ITEM']));

            return $saida;
        }

        
        public function subTotalMaoDeObra()
        {
            return $this->NU_QTDE * $this->VR_MAO_OBRA;
        }

        public function subTotalMaterial()
        {
            return $this->NU_QTDE * $this->VR_MATERIAL;
        }

        public function subTotal()
        {
            return $this->subTotalMaoDeObra() + $this->subTotalMaterial();
        }
    }

?>