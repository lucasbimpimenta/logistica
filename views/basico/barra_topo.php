<ul class="top-nav">
    <li class="hidden-xl-up"><a href="" data-sa-action="search-open"><i class="zmdi zmdi-search"></i></a></li>

    <?php include_once('views/basico/barra_topo_mensagens.php'); ?>
    <?php include_once('views/basico/barra_topo_notificacoes.php'); ?>
    <?php include_once('views/basico/barra_topo_demandas.php'); ?>
    <?php include_once('views/basico/barra_topo_atalhos.php'); ?>

    <li class="dropdown hidden-xs-down">
        <a href="" data-toggle="dropdown"><i class="zmdi zmdi-more-vert"></i></a>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="" class="dropdown-item" data-sa-action="fullscreen">Tela cheia</a>
            <a href="" class="dropdown-item">Limpar Dados Locais</a>
        </div>
    </li>

    <!--
    <li class="hidden-xs-down">
        <a href="" class="top-nav__themes" data-sa-action="aside-open" data-sa-target=".themes"><i class="zmdi zmdi-palette"></i></a>
    </li>
    -->
</ul>