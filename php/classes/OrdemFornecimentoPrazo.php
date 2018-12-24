<?php

    class OrdemFornecimentoPrazo {

        const TABELA = '[DB7065001].[dbo].[ordem_fornecimento_prazo]';

        private $NU_ID;
        private $NU_ITENS_MIN;
        private $NU_ITENS_MAX;
        private $VR_TOTAL_MIN;
        private $VR_TOTAL_MAX;
        private $NU_PRAZO_DIAS;

        public function setId($NU_ID)                   { $this->NU_ID = $NU_ID; }
        public function setItensMin($NU_ITENS_MIN)      { $this->NU_ITENS_MIN = $NU_ITENS_MIN; }
        public function setItensMax($NU_ITENS_MAX)      { $this->NU_ITENS_MAX = $NU_ITENS_MAX; }
        public function setValorMin($VR_TOTAL_MIN)      { $this->VR_TOTAL_MIN = $VR_TOTAL_MIN; }
        public function setValorMax($VR_TOTAL_MAX)      { $this->VR_TOTAL_MAX = $VR_TOTAL_MAX; }
        public function setPrazoEmDias($NU_PRAZO_DIAS)  { $this->NU_PRAZO_DIAS = $NU_PRAZO_DIAS; }

        public function getId()         { return $this->NU_ID; }
        public function getItensMin()   { return $this->NU_QTDE_M_ITENS; }
        public function getItensMax()   { return $this->VR_TOTAL_OF; }
        public function getValorMin()   { return $this->NU_QTDE_M_ITENS; }
        public function getValorMax()   { return $this->VR_TOTAL_OF; }
        public function getPrazoEmDias(){ return $this->NU_PRAZO_DIAS; }

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
            
            $this->NU_ITENS_MIN  = $linha['NU_ITENS_MIN'];
            $this->NU_ITENS_MAX  = $linha['NU_ITENS_MAX'];
            $this->VR_TOTAL_MIN  = $linha['VR_TOTAL_MIN'];
            $this->VR_TOTAL_MAX  = $linha['VR_TOTAL_MAX'];
            $this->NU_PRAZO_DIAS = $linha['NU_PRAZO_DIAS'];
        }

        public function inserir()
        {
            $query = "INSERT INTO ". self::TABELA . "
                        (
                            NU_ITENS_MIN,
                            NU_ITENS_MAX,
                            VR_TOTAL_MIN,
                            VR_TOTAL_MAX,
                            NU_PRAZO_DIAS
                        ) VALUES 
                        (
                            :NU_ITENS_MIN,
                            :NU_ITENS_MAX,
                            :VR_TOTAL_MIN,
                            :VR_TOTAL_MAX,
                            :NU_PRAZO_DIAS
                        )";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_ITENS_MIN', $this->NU_ITENS_MIN);
            $stmt->bindValue(':NU_ITENS_MAX', $this->NU_ITENS_MAX);
            $stmt->bindValue(':VR_TOTAL_MIN', $this->VR_TOTAL_MIN);
            $stmt->bindValue(':VR_TOTAL_MAX', $this->VR_TOTAL_MAX);
            $stmt->bindValue(':NU_PRAZO_DIAS', $this->NU_PRAZO_DIAS);

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
                        SET 
                            NU_ITENS_MIN = :NU_ITENS_MIN
                            ,NU_ITENS_MAX = :NU_ITENS_MAX
                            ,VR_TOTAL_MIN = :VR_TOTAL_MIN
                            ,VR_TOTAL_MAX = :VR_TOTAL_MAX
                            ,NU_PRAZO_DIAS = :NU_PRAZO_DIAS
                        WHERE NU_ID = :NU_ID";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);

            $stmt->bindValue(':NU_ITENS_MIN', $this->NU_ITENS_MIN);
            $stmt->bindValue(':NU_ITENS_MAX', $this->NU_ITENS_MAX);
            $stmt->bindValue(':VR_TOTAL_MIN', $this->VR_TOTAL_MIN);
            $stmt->bindValue(':VR_TOTAL_MAX', $this->VR_TOTAL_MAX);
            $stmt->bindValue(':NU_PRAZO_DIAS', $this->NU_PRAZO_DIAS);

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

        public static function listar()
        {
            $query = "SELECT * FROM ". self::TABELA . " ";
            $conexao = Conexao::pegarConexao();
            $resultado = $conexao->query($query);
            $lista = $resultado->fetchAll();
            return $lista;
        }

        public static function calculaPrazo(OrdemFornecimento $of) {
            $query = "SELECT [NU_ID] FROM ". self::TABELA . "
            WHERE 
            (:QTDE_MACRO_ITENS BETWEEN [NU_ITENS_MIN] AND COALESCE([NU_ITENS_MAX],[NU_ITENS_MIN]) OR ([NU_ITENS_MIN] IS NULL AND [NU_ITENS_MAX] IS NULL))
            AND
            (:VALOR_TOTAL BETWEEN COALESCE([VR_TOTAL_MIN],0) AND COALESCE([VR_TOTAL_MAX],[VR_TOTAL_MIN]))";

            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':QTDE_MACRO_ITENS', $of->getTotalMacroItens());
            $stmt->bindValue(':VALOR_TOTAL', $of->getValorTotalComBDI());
            $stmt->execute();
            $lista = $stmt->fetchAll();

            if(count($lista) == 1)
                return new OrdemFornecimentoPrazo($lista[0]['NU_ID']);
            else
                throw new Exception("Verifique a tabela de Prazo, nenhum deles está compativel com a OF. Consulte o administrador");
            
        }

    }
?>