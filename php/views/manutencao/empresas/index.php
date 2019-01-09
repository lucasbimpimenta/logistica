<?php require_once(__DIR__ . '/../../../../php/global.php'); ?>
<div class="content__inner">
    <header class="content__title">
        <h1>Empresas</h1>

        <div class="actions">
            <div class="dropdown actions__item">
                <i data-toggle="dropdown" class="zmdi zmdi-more-vert"></i>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="" class="dropdown-item">Atualizar</a>
                </div>
            </div>
        </div>
    </header>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Empresas</h4>
            <h6 class="card-subtitle">Lista das empresas cadastradas</h6>
            <div class="table-responsive">
                <table id="data-table" class="table">
                    <thead>
                        <tr>
                            <th>CNPJ</th>
                            <th>Razao Social</th>
                            <th>Nome Fantasia</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    foreach(Empresa::listar() as $empresa){
?>
                        <tr>
                            <td><?php echo $empresa['NU_CNPJ'] ;?></td>
                            <td><?php echo $empresa['NO_RAZAO_SOCIAL'] ;?></td>
                            <td><?php echo $empresa['NO_FANTASIA'] ;?></td>
                            <td><?php echo $empresa['DE_EMAIL'] ;?></td>
                            <td></td>
                        </tr>
<?php        
    }
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>