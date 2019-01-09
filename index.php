<?php require_once('php/global.php') ;?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="vendors/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">

        <!-- App styles -->
        <link rel="stylesheet" href="css/app.min.css">

        <title>GILOG/BH</title>

        <base href="/">
    </head>

    <body data-sa-theme="1">
        <main class="main">
            <div class="page-loader">
                <div class="page-loader__spinner">
                    <svg viewBox="25 25 50 50">
                        <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                    </svg>
                </div>
            </div>

            <header class="header">
                <div class="navigation-trigger hidden-xl-up" data-sa-action="aside-open" data-sa-target=".sidebar">
                    <i class="zmdi zmdi-menu"></i>
                </div>

                <div class="logo hidden-sm-down">
                    <h1><a href="/"><img src='img/logos/logo_caixa.png' /></a></h1>
                </div>

                <form class="search">
                    <div class="search__inner">
                        <input type="text" class="search__text" placeholder="Pesquise por Unidade, Empresa, Ordem de Fornecimento, etc">
                        <i class="zmdi zmdi-search search__helper" data-sa-action="search-close"></i>
                    </div>
                </form>

                <?php require_once('views/basico/barra_topo.php'); ?>

                <div class="clock hidden-md-down">
                    <div class="time">
                        <span class="time__hours"></span>
                        <span class="time__min"></span>
                        <span class="time__sec"></span>
                    </div>
                </div>
            </header>

            <aside class="sidebar">
                <div class="scrollbar-inner">
                   <?php require_once('views/basico/profile.php'); ?>
                   <?php require_once('views/basico/menu.php'); ?>
                </div>
            </aside>

            <section id="content" class="content">
                <div class="content__inner">
                    <header class="content__title">
                        <h1>xxx</h1>

                        <div class="actions">
                            <a href="" class="actions__item zmdi zmdi-trending-up"></a>
                            <a href="" class="actions__item zmdi zmdi-check-all"></a>

                            <div class="dropdown actions__item">
                                <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="" class="dropdown-item">Refresh</a>
                                    <a href="" class="dropdown-item">Manage Widgets</a>
                                    <a href="" class="dropdown-item">Settings</a>
                                </div>
                            </div>
                        </div>
                    </header>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">xxx</h4>
                            <h6 class="card-subtitle">xxx</h6>
                        </div>
                    </div>

                    <footer class="footer hidden-xs-down">
                        <p>GILOG/BH </p>
                    </footer>
                </div>
            </section>
        </main>

        <!-- Older IE warning message -->
            <!--[if IE]>
                <div class="ie-warning">
                    <h1>Atenção!!</h1>
                    <p>Este versão do Internet Explorer não é compativel com este sistema. Gentileza utilizar um dos navegadores relacionados abaixo:</p>

                    <div class="ie-warning__downloads">
                        <a href="http://www.google.com/chrome">
                            <img src="img/browsers/chrome.png" alt="">
                        </a>

                        <a href="https://www.mozilla.org/en-US/firefox/new">
                            <img src="img/browsers/firefox.png" alt="">
                        </a>
                        <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
                            <img src="img/browsers/edge.png" alt="">
                        </a>
                    </div>
                    <p>Pedimos desculpas pelo transtorno!</p>
                </div>
            <![endif]-->

        <!-- Javascript -->
        <!-- Vendors -->
        <script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="vendors/bower_components/popper.js/dist/umd/popper.min.js"></script>
        <script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="vendors/bower_components/pagejs/page.js"></script>
        <script src="vendors/bower_components/jsrender/jsrender.min.js"></script>
        <script src="vendors/bower_components/jsviews/jsviews.min.js"></script>
        <script src="vendors/bower_components/axios/dist/axios.min.js"></script>

        <!-- Vendors: Data tables -->
        <script src="vendors/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="vendors/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <script src="js/app/Empresa.js"></script>
        <script src="js/app/EmpresaLista.js"></script>

        <!-- App functions and actions -->
        <script src="js/app.min.js"></script>
        <script src="js/rotas.js"></script>
    </body>
</html>