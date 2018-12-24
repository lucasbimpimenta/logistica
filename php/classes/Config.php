<?php

    class Config {

        const BD_DRV = 'sqlsrv';
        const BD_SRV = 'MG7435SR315';
        const BD_USR = 'n7065';
        const BD_PWD = 'G7i0l6o5g';
        const BD_BSE = 'DB7065001';

        const LDAP_HOST = 'ldapcluster.corecaixa';
        const LDAP_PORT = '489';
        const LDAP_BASE = 'o=caixa';

        //public $USUARIO = null;

        function __construct()
        {
            ini_set("session.gc_probability", 0);
            ini_set('memory_limit', '-1');
            ini_set('cgi.force_redirect', 1);
            ini_set('display_errors', 1);
            ini_set('error_log', __DIR__ . '../../logs/');

            set_time_limit(0);
            error_reporting(E_ALL);

            setlocale(LC_TIME, 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');

            //$USUARIO = new Usuario();

        }
    }

?>