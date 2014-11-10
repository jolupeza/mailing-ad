<?php echo doctype('html5'); ?>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?php echo $this->config->item('cms_site_desc'); ?>" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ico/favicon.png" />

        <title><?php echo (isset($_title)) ? $_title . ' | ' : ''; ?><?php echo $this->config->item('cms_site_name'); ?></title>

        <!-- Google Font -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css' />

        <!-- Font awesome -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" />

        <!-- Bootstrap core CSS -->
        <?php echo $_css; ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <!-- Cargarmos vista personalizada sola para el login -->
        <?php if (isset($_notmp) && $_notmp === TRUE) : ?>

            <?php foreach($_content as $_view): ?>
                <?php include $_view;?>
            <?php endforeach; ?>

        <?php else : ?>
        <div id="spin"></div><!-- end #spin -->

        <header class="topbar navbar navbar-fixed-top inner">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <button type="button" id="toggle-menu">
                    <i class="fa fa-bars"></i>
                </button>
                <h1 class="logo">
                    <a href="<?php echo base_url(); ?>admin/dashboard" title="<?php echo $this->config->item('cms_site_name'); ?>">
                    <?php echo img(array(
                            'src'       =>  'assets/images/logo.png',
                            'class'     =>  'img-responsive',
                            'alt'       =>  $this->config->item('cms_site_name')
                        ));
                    ?>
                    </a>
                </h1><!-- end logo -->
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                <ul class="list-inline mnu-user pull-right">
                    <li>
                        <a href="<?php echo base_url(); ?>admin/users/edit/<?php echo $this->user->id; ?>" class="img-avatar">
                        <?php if ($this->user->avatar != '') : ?>
                            <img src="<?php echo $this->user->avatar; ?>" alt="<?php echo $this->user->name; ?>" />
                        <?php else : ?>
                            <i class="fa fa-user"></i><?php endif; ?>
                        </a>
                    </li>
                    <li class="text-right"><h6>Bienvenido</h6><?php echo $this->user->name; ?></li>
                    <li><a href="#" title="Ver mensajes"><span class="glyphicon glyphicon-envelope"></span></a></li>
                    <li><a href="<?php echo base_url(); ?>admin/users/logout" title="Cerrar sesión"><span class="glyphicon glyphicon-off"></span></a></li>
                </ul>

            </div>
        </header><!-- end header -->

        <div id="pageslide-left" class="pageslide inner">
            <nav>
                <ul>
                    <li class="text-center <?php echo ($_active === 'dashboard') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/dashboard" class="btn-dashboard"><?php echo $this->lang->line('cms_general_title_dashboard'); ?></a>
                    </li>

                    <?php if ($this->user->has_permission('view_tmp_email')) : ?>
                    <li class="text-center <?php echo ($_active === 'templates') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/templates/display" class="btn-dashboard"><?php echo $this->lang->line('cms_general_title_templates'); ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($this->user->has_permission('view_news')) : ?>
                    <li class="text-center <?php echo ($_active === 'newsletters') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/newsletters/display" class="btn-dashboard"><?php echo $this->lang->line('cms_general_title_newsletters'); ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($this->user->has_permission('view_campaigns')) : ?>
                    <li class="text-center <?php echo ($_active === 'campaigns') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/campaigns/display" class="btn-dashboard"><?php echo $this->lang->line('cms_general_title_campaigns'); ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($this->user->has_permission('view_lists')) : ?>
                    <li class="text-center <?php echo ($_active === 'lists') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/lists/display" class="btn-dashboard"><?php echo $this->lang->line('cms_general_title_lists'); ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($this->user->has_permission('view_subs')) : ?>
                    <li class="text-center <?php echo ($_active === 'subscribers') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/subscribers/display" class="btn-dashboard"><?php echo $this->lang->line('cms_general_title_subscribers'); ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($this->user->has_permission('admin_users')) : ?>
                    <li class="text-center <?php echo ($_active === 'users') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/users/display" class="btn-dashboard"><?php echo $this->lang->line('cms_general_title_users'); ?></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($this->user->has_permission('admin_permissions')) : ?>
                    <li class="text-center <?php echo ($_active === 'permissions') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/permissions/perms" class="btn-dashboard"><?php echo $this->lang->line('cms_general_label_permissions'); ?></a>
                        <i class="fa fa-angle-down down-menu"></i>
                        <ul class="hidden">
                            <li><a href="<?php echo base_url(); ?>admin/permissions"><?php echo $this->lang->line('cms_general_label_roles'); ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if ($this->user->has_permission('admin_site_configuration')) : ?>
                    <li class="text-center <?php echo ($_active === 'configurations') ? 'active': ''; ?>">
                        <a href="<?php echo base_url(); ?>admin/configuration" class="btn-dashboard"><?php echo $this->lang->line('cms_general_label_title_general_settings'); ?> </a>
                        <i class="fa fa-angle-down down-menu"></i>
                        <ul class="hidden">
                            <li><a href="<?php echo base_url(); ?>admin/configuration/media"><?php echo $this->lang->line('cms_general_title_setting_multimedia'); ?></a></li>
                            <li><a href="<?php echo base_url(); ?>admin/configuration/smtp"><?php echo $this->lang->line('cms_general_title_setting_smtp'); ?></a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div><!-- end #pageslide-left .pageslide -->

        <div class="main-container">
            <div class="main-content">
                <div class="container width-auto">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php foreach($_warning as $_msg): ?>
                                <div class="alert alert-warning"><?=$_msg?></div>
                            <?php endforeach;?>

                            <?php foreach($_success as $_msg): ?>
                                <div class="alert alert-success"><?=$_msg?></div>
                            <?php endforeach;?>

                            <?php foreach($_error as $_msg): ?>
                                <div class="alert alert-danger"><?=$_msg?></div>
                            <?php endforeach;?>

                            <?php foreach($_info as $_msg): ?>
                                <div class="alert alert-info"><?=$_msg?></div>
                            <?php endforeach;?>
                        </div><!-- end col-sm-12 -->

                    </div><!-- end row -->

                    <?php foreach($_content as $_view): ?>
                        <?php include $_view;?>
                    <?php endforeach; ?>

                </div><!-- end .container -->
            </div><!-- end main-content -->
        </div><!-- end main-container -->

        <?php endif; ?>

        <!-- Load Javascript -->
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/scripts/libraries/jquery/jquery-1.11.0.min.js"><\/script>')</script>
		<script> var _root_ = '<?php echo base_url(); ?>'</script>
        <script type="text/javascript" src="http://fgnass.github.io/spin.js/spin.min.js"></script>
        <?php echo $_js; ?>
    </body>
</html>