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
            <a href="<?php echo base_url() ?>" class="" style="text-decoration: none;">
                <img class="navbar-brand-logo" src="<?php echo URL_IMG ?>app/logo_square.png">
            </a>
            <span class="navbar-brand-text hidden-xs-down">
                <?php echo APP_NAME ?>
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
                <span id="head_title" style="color: #777">
                    <?php echo $head_title ?>
                </span>
                <small style="color: #007bff" id="head_subtitle">
                    <?php echo $head_subtitle ?>
                </small>
            </div>
            <!-- Navbar Toolbar Right -->
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                
                <li class="nav-item dropdown">
                    <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                       data-animation="scale-up" role="button">
                        <span class="avatar">
                            <img src="<?php echo $this->session->userdata('src_img') ?>" alt="Imagen usuario" onerror="this.src='<?php echo URL_IMG . 'users/sm_user.png' ?>';">
                        </span>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" href="<?php echo base_url('accounts/profile/') ?>" role="menuitem">
                            <i class="icon wb-user" aria-hidden="true"></i> Perfil
                        </a>
                        <a class="dropdown-item" href="javascript:void(0)" role="menuitem"><i class="icon wb-settings" aria-hidden="true"></i> Configuración</a>
                        <div class="dropdown-divider" role="presentation"></div>
                        <a class="dropdown-item" href="<?php echo base_url('accounts/logout') ?>" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i> Salir</a>
                    </div>
                </li>
            </ul>
            <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->

        <?php $this->load->view('templates/remark/parts/nav_search'); ?>
    </div>
</nav>