<?php

    class Unidade {

        const TABELA = '[DB7065001].[dbo].[unidade]';

		private $CO_UNIDADE;
		private $CO_UNIDADE_DV;
		private $SG_TIPO;
		private $NO_UNIDADE;
		private $SG_UNIDADE;
		private $DE_SITUACAO;
		private $DT_CRIACAO;
		private $DE_ENDERECO;
		private $DE_BAIRRO;
		private $NU_PORTE;
		private $NO_CIDADE;
		private $SG_UF;
		private $NU_CEP;
		private $NO_RESPONSAVEL;
		private $CO_RESP_MATRICULA;
		private $DE_EMAIL;
		private $CO_SUBORDINACAO;
		private $HH_ABERTURA;
		private $HH_FECHAMENTO;
		private $NU_DDD;
		private $NU_TELEFONES;
		private $DT_ATUALIZACAO;

        public function getId() { return $this->CO_UNIDADE; }
		public function getDv() { return $this->CO_UNIDADE_DV; }
		public function getTipo() { return $this->SG_TIPO; }
		public function getNome() { return $this->NO_UNIDADE; }
		public function getSigla() { return $this->SG_UNIDADE; }
		public function getSituacao() { return $this->DE_SITUACAO; }
		public function getDataCriacao() { return $this->DT_CRIACAO; }
		public function getEndereco() { return $this->DE_ENDERECO; }
		public function getBairro() { return $this->DE_BAIRRO; }
		public function getPorte() { return $this->NU_PORTE; }
		public function getCidade() { return $this->NO_CIDADE; }
		public function getUF() { return $this->SG_UF; }
		public function getCEP() { return $this->NU_CEP; }
		public function getResponsavel() { return $this->NO_RESPONSAVEL; }
		public function getResponsavelMatricula() { return $this->CO_RESP_MATRICULA; }
		public function getEmail() { return $this->DE_EMAIL; }
		public function getSubordinacao() { return new Unidade($this->CO_SUBORDINACAO); }
		public function getAbertura() { return $this->HH_ABERTURA; }
		public function getFechamento() { return $this->HH_FECHAMENTO; }
		public function getDDD() { return $this->NU_DDD; }
		public function getTelefones() { return $this->NU_TELEFONES; }
		public function getDataAtualizacao() { return $this->DT_ATUALIZACAO; }
		


        function __construct() {

            $arguments = func_get_args();
            $num = sizeof($arguments);

            if($num > 0) {
                if($num == 1) {
                    $this->CO_UNIDADE = $arguments[0];
                    $this->carregarPorID();
                }
            }
        }

        public function carregarPorID()
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE CO_UNIDADE = :CO_UNIDADE";
            $conexao = Conexao::pegarConexao();
            $stmt = $conexao->prepare($query);
            $stmt->bindValue(':CO_UNIDADE', $this->CO_UNIDADE);
            $stmt->execute();
            $linha = $stmt->fetch();
			$linha = Util::TrimArray($linha);
			
			$this->CO_UNIDADE = $linha['CO_UNIDADE'];
			$this->CO_UNIDADE_DV = $linha['CO_UNIDADE_DV'];
			$this->SG_TIPO = $linha['SG_TIPO'];
			$this->NO_UNIDADE = $linha['NO_UNIDADE'];
			$this->SG_UNIDADE = $linha['SG_UNIDADE'];
			$this->DE_SITUACAO = $linha['DE_SITUACAO'];
			$this->DT_CRIACAO = $linha['DT_CRIACAO'];
			$this->DE_ENDERECO = $linha['DE_ENDERECO'];
			$this->DE_BAIRRO = $linha['DE_BAIRRO'];
			$this->NU_PORTE = $linha['NU_PORTE'];
			$this->NO_CIDADE = $linha['NO_CIDADE'];
			$this->SG_UF = $linha['SG_UF'];
			$this->NU_CEP = $linha['NU_CEP'];
			$this->NO_RESPONSAVEL = $linha['NO_RESPONSAVEL'];
			$this->CO_RESP_MATRICULA = $linha['CO_RESP_MATRICULA'];
			$this->DE_EMAIL = $linha['DE_EMAIL'];
			$this->CO_SUBORDINACAO = $linha['CO_SUBORDINACAO'];
			$this->HH_ABERTURA = $linha['HH_ABERTURA'];
			$this->HH_FECHAMENTO = $linha['HH_FECHAMENTO'];
			$this->NU_DDD = $linha['NU_DDD'];
			$this->NU_TELEFONES = $linha['NU_TELEFONES'];
			$this->DT_ATUALIZACAO = $linha['DT_ATUALIZACAO'];
        }
        
        public static function listar()
        {
            $query = "SELECT * FROM ". self::TABELA . " ";
            $conexao = Conexao::pegarConexao();
            $resultado = $conexao->query($query);
            $lista = $resultado->fetchAll();
			$lista = Util::TrimArray($lista);
            return $lista;
        }
		
		public static function listarPorTipo($tipo)
        {
            $query = "SELECT * FROM ". self::TABELA . " WHERE SG_TIPO = :SG_TIPO";
            $conexao = Conexao::pegarConexao();
			$stmt = $conexao->prepare($query);
			$stmt->bindValue(':SG_TIPO', $tipo);
            $stmt->execute();
            $lista = $stmt->fetchAll();
			$lista = Util::TrimArray($lista);
			return $lista;
        }
		
		public static function listarHieraquiaTreeview()
		{
			$query = "SELECT [CO_UNIDADE]
						,[NO_UNIDADE]
						,[SG_UNIDADE]
						,[CO_SUBORDINACAO]
						,[SG_TIPO]
						,[SG_UF]
						,[NO_CIDADE]
						,[Nivel]
					FROM [DB7065001].[dbo].[VW_HIERARQUIA_SR_AG]
					UNION
					SELECT [CO_UNIDADE]
						,[NO_UNIDADE]
						,[SG_UNIDADE]
						,[CO_SUBORDINACAO]
						,[SG_TIPO]
						,[SG_UF]
						,[NO_CIDADE] 
						, CASE 
							WHEN SG_TIPO IN ('PV','SN') THEN 0
							ELSE 9  
						  END as [Nivel]
						FROM [DB7065001].[dbo].[unidade]
				WHERE [SG_UF] = 'MG' AND CO_UNIDADE NOT IN (SELECT DISTINCT CO_UNIDADE FROM [DB7065001].[dbo].[VW_HIERARQUIA_SR_AG])
				ORDER BY Nivel DESC;";
					
			$conexao = Conexao::pegarConexao();
			$stmt = $conexao->prepare($query);
            $stmt->execute();
            $lista = $stmt->fetchAll();
			$lista = Util::TrimArray($lista);
			
			$saida = array();
			
			/*
			[{
				value: 'mars',
				label: 'Mars',
				children: [
					{ value: 'phobos', label: 'Phobos' },
					{ value: 'deimos', label: 'Deimos' },
				],
			}];
			*/
			$unidade_por_pai = array();
			$unidade_por_municipio = array();
			
			$saida['FILIAIS'] 	= array('value' => 'FILIAIS', 'label' => 'Filiais', 'children' => array());
			$saida['PVS'] 		= array('value' => 'PVS', 'label' => 'Pontos de Venda', 'children' => array());
			
			foreach($lista as $unidade)
			{
				if($unidade['Nivel'] != 9)
				{
					$unidade_por_pai[$unidade['CO_SUBORDINACAO']][] = $un = array('value' => $unidade['CO_UNIDADE'], 'label' => $unidade['NO_UNIDADE'], 'children' => (isset($unidade_por_pai[$unidade['CO_UNIDADE']])) ? $unidade_por_pai[$unidade['CO_UNIDADE']] : array());
				}
				else
				{
					if(!array_key_exists($unidade['NO_CIDADE'], $unidade_por_municipio))
						$unidade_por_municipio[$unidade['NO_CIDADE']] = array('value' => $unidade['NO_CIDADE'], 'label' => $unidade['NO_CIDADE'], 'children' => array());
				
					$unidade_por_municipio[$unidade['NO_CIDADE']]['children'][] = $un = array('value' => $unidade['CO_UNIDADE'], 'label' => $unidade['NO_UNIDADE'], 'children' => array());
				}
			}
			
			
			
			$saida['FILIAIS']['children'] = $unidade_por_municipio;
			
			foreach($lista as $unidade)
			{
				$un = array('value' => $unidade['CO_UNIDADE'], 'label' => $unidade['NO_UNIDADE'], 'children' => array());

				if(array_key_exists($unidade['CO_UNIDADE'], $unidade_por_pai))
				{
					$un['children'] = $unidade_por_pai[$unidade['CO_UNIDADE']];
				}
				
				if($unidade['Nivel'] == 0)
					$saida['PVS']['children'][] = $un;
				
				//if($unidade['Nivel'] == 9)
				//	$saida['FILIAIS']['children'][]$saida[$unidade['NO_CIDADE']][] = $un;
			}
			
			return $saida;
		}
    }

?>