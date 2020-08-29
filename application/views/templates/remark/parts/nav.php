
<style>
    @import url('https://fonts.googleapis.com/css?family=Ubuntu&display=swap');

    #head_title{
        color: #777;
        font-family: 'Ubuntu', sans-serif;
    }

    #head_subtitle {
        font-family: 'Ubuntu', sans-serif;
        color: #007bff;
    }
</style>

<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
                data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
                data-toggle="collapse">
            <i class="icon wb-more-horizontal" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
            <a href="<?= base_url() ?>" class="" style="text-decoration: none;">
                <img class="navbar-brand-logo" src="<?= URL_IMG ?>app/logo_square.png">
            </a>
            <span class="navbar-brand-text hidden-xs-down">
                <?= APP_NAME ?>
            </span>
        </div>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
                data-toggle="collapse">
            <span class="sr-only">Toggle Search</span>
            <i class="icon wb-search" aria-hidden="true"></i>
        </button>
    </div>

    <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <div class="float-left" style="padding-top: 12px; font-size: 2em;">
                <span id="head_title">
                    <?= $head_title ?>
                </span>
                <span class="text-muted"></span>
                <?php if ( isset($head_subtitle) ) { ?>
                    &middot;
                    <small id="head_subtitle">
                        <?= $head_subtitle ?>
                    </small>
                <?php } ?>
            </div>
            <!-- Navbar Toolbar Right -->
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                
                <li class="nav-item dropdown">
                    <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                       data-animation="scale-up" role="button">
                        <span class="avatar">
                            <img src="<?= $this->session->userdata('src_img') ?>" alt="Imagen usuario" onerror="this.src='<?= URL_IMG . 'users/sm_user.png' ?>';">
                        </span>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" href="<?= base_url('accounts/profile/') ?>" role="menuitem">
                            <i class="icon wb-user" aria-hidden="true"></i> Perfil
                        </a>
                        <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon wb-settings" aria-hidden="true"></i> Configuración</a>
                        <div class="dropdown-divider" role="presentation"></div>
                        <a class="dropdown-item" href="<?= base_url('accounts/logout') ?>" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i> Salir</a>
                    </div>
                </li>
            </ul>
            <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->

        <?php $this->load->view('templates/remark/parts/nav_search'); ?>
    </div>
</nav>