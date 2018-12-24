<?php

    class RegistroPreco {

        const TABELA = '[DB7065001].[dbo].[registro_preco]';

        private $NU_ID;
        private $CO_PROCESSO;
        private $CO_PREGAO;
        private $CO_ATA;
        private $OBJ_EMPRESA;
        private $VR_BDI;
        private $VR_MOBILIZACAO;

        private $TOTAL_MAO_DE_OBRA = 0;
        private $TOTAL_MATERIAL = 0;

        public function setId($NU_ID)                   { $this->NU_ID = $NU_ID; }
        public function setProcesso($CO_PROCESSO)       { $this->CO_PROCESSO = $CO_PROCESSO; }
        public function setPregao($CO_PREGAO)           { $this->CO_PREGAO = $CO_PREGAO; }
        public function setAta($CO_ATA)                 { $this->CO_ATA = $CO_ATA; }
        public function setEmpresa(Empresa $OBJ_EMPRESA){ $this->OBJ_EMPRESA = $OBJ_EMPRESA; }
        public function setBDI($VR_BDI)                 { $this->VR_BDI = $VR_BDI; }
        public function setMobilizacao($VR_MOBILIZACAO) { $this->VR_MOBILIZACAO = $VR_MOBILIZACAO; }

        public function getId()         { return $this->NU_ID; }
        public function getProcesso()   { return $this->CO_PROCESSO; }
        public function getPregao()     { return $this->CO_PREGAO; }
        public function getAta()        { return $this->CO_ATA; }
        public function getEmpresa()    { return $this->OBJ_EMPRESA; }
        public function getBDI()        { return $this->VR_BDI; }
        public function getMobilizacao(){ return $this->VR_MOBILIZACAO; }

        public function getSubtotalMaoDeObra() { return $this->TOTAL_MAO_DE_OBRA; }
        public function getSubtotalMaterial()  { return $this->TOTAL_MATERIAL; }
        public function getSubtotal()          { return $this->TOTAL_MATERIAL + $this->TOTAL_MAO_DE_OBRA; }
        
        public function getMobilizacaoMaoDeObra() { return $this->TOTAL_MAO_DE_OBRA * $this->VR_MOBILIZACAO; }
        public function getMobilizacaoMaterial()  { return $this->TOTAL_MATERIAL * $this->VR_MOBILIZACAO; }
        public function getMobilizacaoSubTotal()     { return ($this->TOTAL_MATERIAL * $this->VR_MOBILIZACAO) + ($this->TOTAL_MAO_DE_OBRA * $this->VR_MOBILIZACAO); }

        public function getTotal()          { return $this->getSubtotal() + $this->getMobilizacaoSubTotal(); }

        public function getValorBDI()       { return $this->getTotal() * $this->VR_BDI; }

        public function getTotalComBDI()       { return $this->getTotal() + $this->getValorBDI(); }

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
            $stmt->bindValue(':NU_ID', $this->NU_ID, PDO::PARAM_INT);
            $stmt->execute();
            $linha = $stmt->fetch();
            
            $this->CO_PROCESSO      = $linha['CO_PROCESSO'];
            $this->CO_PREGAO        = $linha['CO_PREGAO'];
            $this->CO_ATA           = $linha['CO_ATA'];
            $this->OBJ_EMPRESA      = new Empresa($linha['FK_NU_CNPJ_EMPRESA']);
            $this->VR_BDI           = $linha['VR_BDI'];
            $this->VR_MOBILIZACAO   = $linha['VR_MOBILIZACAO'];
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            CO_PROCESSO, 
                            CO_PREGAO, 
                            CO_ATA, 
                            FK_NU_CNPJ_EMPRESA,
                            VR_BDI,
                            VR_MOBILIZACAO
                        ) VALUES 
                        (
                            :CO_PROCESSO, 
                            :CO_PREGAO, 
                            :CO_ATA, 
                            :FK_NU_CNPJ_EMPRESA,
                            :VR_BDI,
                            :VR_MOBILIZACAO
                        )";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':CO_PROCESSO', $this->CO_PROCESSO);
            $stmt->bindValue(':CO_PREGAO', $this->CO_PREGAO);
            $stmt->bindValue(':CO_ATA', $this->CO_ATA);
            $stmt->bindValue(':FK_NU_CNPJ_EMPRESA', $this->OBJ_EMPRESA->getCNPJ(), PDO::PARAM_INT);
            $stmt->bindValue(':VR_BDI', $this->VR_BDI);
            $stmt->bindValue(':VR_MOBILIZACAO', $this->VR_MOBILIZACAO);

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
                            CO_PROCESSO = :CO_PROCESSO
                            ,CO_PREGAO = :CO_PREGAO
                            ,CO_ATA = :CO_ATA
                            ,FK_NU_CNPJ_EMPRESA = :FK_NU_CNPJ_EMPRESA
                            ,VR_BDI = :VR_BDI
                            ,VR_MOBILIZACAO = :VR_MOBILIZACAO
                        WHERE NU_ID = :NU_ID";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':CO_PROCESSO', $this->CO_PROCESSO);
            $stmt->bindValue(':CO_PREGAO', $this->CO_PREGAO);
            $stmt->bindValue(':CO_ATA', $this->CO_ATA);
            $stmt->bindValue(':FK_NU_CNPJ_EMPRESA', $this->OBJ_EMPRESA->getCNPJ(), PDO::PARAM_INT);
            $stmt->bindValue(':VR_BDI', $this->VR_BDI);
            $stmt->bindValue(':VR_MOBILIZACAO', $this->VR_MOBILIZACAO);
            $stmt->bindValue(':NU_ID', $this->NU_ID, PDO::PARAM_INT);

            return $stmt->execute();
        }

        public function excluir()
        {
            $query = "DELETE FROM ". self::TABELA . " WHERE NU_ID = :NU_ID";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':NU_ID', $this->NU_ID, PDO::PARAM_INT);
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

        public function getItens() {
            return RegistroPrecoItem::itensRP($this);
        }

        public function calculaTotais() {
            $this->TOTAL_MAO_DE_OBRA = 0;
            $this->TOTAL_MATERIAL = 0;

            foreach($this->getItens() as $item)
            {
                $this->TOTAL_MAO_DE_OBRA += $item->subTotalMaoDeObra();
                $this->TOTAL_MATERIAL +=  $item->subTotalMaterial();
            }
        }
    }
?>