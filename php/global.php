<?php
    ini_set('display_errors', 1);

    spl_autoload_register('carregarClasse');

    function carregarClasse($nomeClasse)
    {
        if (file_exists(dirname(__FILE__) . '/classes/' . $nomeClasse . '.php')) {
            require_once dirname(__FILE__) . '/classes/' .$nomeClasse . '.php';
        }
    }

    $config = new Config();

?>