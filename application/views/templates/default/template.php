<?php echo doctype('html5'); ?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?php echo $this->config->item('cms_site_desc'); ?>" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ico/favicon.png" />

        <title><?php echo (isset($_title)) ? $_title . ' | ' : ''; ?><?php echo $this->config->item('cms_site_name'); ?></title>

        <!-- Google Font -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css' />

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

        <div class="container-fluid">
            <header class="row">
                <div class="col-sm-7">
                    <h1 class="logo">
                        <a href="<?php echo base_url(); ?>" title="<?php echo $this->config->item('cms_site_name'); ?>">
                        <?php echo img(array(
                                'src'       =>  'assets/images/logo.png',
                                'class'     =>  'img-responsive',
                                'alt'       =>  $this->config->item('cms_site_name')
                            ));
                        ?>
                        </a>
                    </h1><!-- end logo -->
                </div>
                <div class="col-sm-5">
                    <ul class="list-inline mnu-user pull-right">
                        <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $this->user->name; ?></a></li>
                        <li><a href="#" title="Ver mensajes"><span class="glyphicon glyphicon-envelope"></span></a></li>
                        <li><a href="<?php echo base_url(); ?>users/logout" title="Cerrar sesión"><span class="glyphicon glyphicon-off"></span></a></li>
                    </ul>

                </div>
            </header><!-- end header -->

            <div class="row">
                <div class="col-sm-3">
                    <div class="sidebar">
                        <nav>
                            <ul>
                                <li class="active"><a href="#">Escritorio</a></li>
                                <li>
                                    <a href="<?php echo base_url(); ?>posts/display"><?php echo $this->lang->line('cms_general_title_all_posts'); ?> <span class="glyphicon glyphicon-play"></span></a>
                                    <ul>
                                        <li><a href="<?php echo base_url(); ?>posts/display"><?php echo $this->lang->line('cms_general_menu_all_posts'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>posts/add"><?php echo $this->lang->line('cms_general_label_add_post'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>terms/display"><?php echo $this->lang->line('cms_general_title_all_terms'); ?></a></li>
                                        <li><a href="#"><?php echo $this->lang->line('cms_general_menu_tags'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Medios</a></li>
                                <li>
                                    <a href="<?php echo base_url(); ?>pages/display"><?php echo $this->lang->line('cms_general_title_all_pages'); ?> <span class="glyphicon glyphicon-play"></span></a>
                                    <ul>
                                        <li><a href="<?php echo base_url(); ?>pages/display"><?php echo $this->lang->line('cms_general_menu_all_pages'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>pages/add"><?php echo $this->lang->line('cms_general_label_add_page'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Comentarios</a></li>
                                <li>
                                    <a href="#"><?php echo $this->lang->line('cms_general_menu_appearance'); ?> <span class="glyphicon glyphicon-play"></span></a>
                                    <ul>
                                        <li><a href="">Temas</a></li>
                                        <li><a href="">Personalizar</a></li>
                                        <li><a href="">Widgets</a></li>
                                        <li><a href="<?php echo base_url(); ?>menus"><?php echo $this->lang->line('cms_general_menu_menus'); ?></a></li>
                                        <li><a href="">Theme Opciones</a></li>
                                        <li><a href="">Editor</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Plugins</a></li>
                                <li><a href="#">Usuarios</a></li>
                                <li><a href="#">Herramientas</a></li>
                                <li>
                                    <a href="<?php echo base_url(); ?>configuration"><?php echo $this->lang->line('cms_general_label_title_general_settings'); ?> <span class="glyphicon glyphicon-play"></span></a>
                                    <ul>
                                        <li><a href="<?php echo base_url(); ?>configuration"><?php echo $this->lang->line('cms_general_menu_general'); ?></a></li>
                                        <li><a href="#">Generales</a></li>
                                        <li><a href="#">Generales</a></li>
                                        <li><a href="#">Generales</a></li>
                                        <li><a href="<?php echo base_url(); ?>configuration/media"><?php echo $this->lang->line('cms_general_title_media'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Cerrar Menú</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-sm-9">
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

                    <?php foreach($_content as $_view): ?>
                        <?php include $_view;?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div><!-- end .container-fluid -->

        <?php endif; ?>

        <!-- Load Javascript -->
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/scripts/libraries/jquery/jquery-1.11.0.min.js"><\/script>')</script>
		<script> var _root_ = '<?php echo base_url(); ?>'</script>
        <?php echo $_js; ?>
    </body>
</html>