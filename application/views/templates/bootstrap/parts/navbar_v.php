<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #0084FF;">
    <a class="navbar-brand" href="<?= base_url() ?>">PAE</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php $this->load->view('templates/bootstrap/menus/navbar_menu_v'); ?>

        <?php if ( $this->session->userdata('logged') ) { ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Cuenta
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <h6 class="dropdown-header">
                            <i class="fa fa-user-circle"></i>
                            <?= $this->session->userdata('display_name') ?>
                        </h6>
                        <a class="dropdown-item" href="<?= base_url('accounts/edit/basic') ?>">Mi cuenta</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= base_url('accounts/logout') ?>">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        <?php } else { ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('accounts/login') ?>">
                        <i class="fa fa-sign-in-alt"></i>
                        Ingresar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('accounts/signup') ?>">
                        <i class="fa fa-user-plus"></i>
                        Registrarme
                    </a>
                </li>
            </ul>
        <?php } ?>

    </div>
</nav>