<?php

    class Conexao {

        public static function pegarConexao()
        {
            try 
			{
                $conexao = new PDO(Config::BD_DRV .':server='. Config::BD_SRV .';Database='. Config::BD_BSE, Config::BD_USR, Config::BD_PWD); 

                $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                $conexao->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
                $conexao->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);

                return $conexao;
            } 
            catch (PDOException $e) 
			{
                echo "Falha ao pegar conexao com banco de dados";
                print_r($e);
                return false;
            }
        }
    }

?>