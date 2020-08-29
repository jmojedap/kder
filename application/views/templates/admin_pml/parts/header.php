<header class="main-header">
    <!-- Logo -->
    <a href="<?= base_url() ?>" class="logo fixed-top">
        <img src="<?= URL_IMG . 'app/logo_admin.png' ?>" alt="WebApp Logo">
    </a>
    <nav class="navbar fixed-top" role="navigation" style="padding: 0px;">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div id="page_titles" class="float-left">
            <h1 id="head_title"><?= substr($head_title, 0, 50) ?></h1>
            <h2 id="head_subtitle"><?= $head_subtitle ?></h2>
        </div>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu nav-item">
                    <a href="#" data-toggle="dropdown">
                        <img src="<?= $this->session->userdata('src_img') ?>" class="user-image" alt="User Image">
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $this->session->userdata('src_img') ?>" class="rounded-circle" alt="User Image" onerror="this.src='<?= URL_IMG ?>users/sm_user.png'">
                            <p>
                                <?= $this->session->userdata('display_name') ?>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="float-left">
                                <?= anchor("accounts/profile", '<i class="fa fa-user"></i> Mi perfil', 'class="btn btn-light" title="Ver mi perfil"') ?>
                            </div>
                            <div class="float-right">
                                <?= anchor("accounts/logout", 'Cerrar sesión', 'class="btn btn-light" title="Cerrar sesión"') ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>