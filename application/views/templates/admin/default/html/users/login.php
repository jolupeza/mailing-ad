    <section class="wrapper-login">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h4 class="text-center">
                        <?php echo img(array(
                                'src'       =>  'assets/images/logo-login.png',
                                'class'     =>  'img-responsive',
                                'alt'       =>  $this->config->item('cms_site_name')
                            ));
                        ?>
                    </h4>
                </div><!-- end col-xs-12 -->
            </div><!-- end row -->
            <div class="row">
                <div class="col-lg-6 col-md-4 col-sm-8 col-sm-offset-2 col-md-offset-4 col-lg-offset-3">
                    <?php foreach($_warning as $_msg): ?>
                    <div class="alert alert-warning"><?php echo $_msg; ?></div>
                    <?php endforeach;?>

                    <?php foreach($_success as $_msg): ?>
                        <div class="alert alert-success"><?php echo $_msg; ?></div>
                    <?php endforeach;?>

                    <?php foreach($_error as $_msg): ?>
                        <div class="alert alert-danger"><?php echo $_msg; ?></div>
                    <?php endforeach;?>

                    <?php foreach($_info as $_msg): ?>
                        <div class="alert alert-info"><?php echo $_msg; ?></div>
                    <?php endforeach;?>

                    <?php if (validation_errors()) : ?>
                        <div class="alert alert-danger">
                            <?php echo validation_errors('<p><small>', '</small></p>'); ?>
                        </div>
                    <?php endif; ?>

                    <aside class="panel panel-default panel-login">
                        <div class="panel-heading">
                            <h3><?php echo $this->lang->line('user_label_login_title'); ?></h3>
                        </div><!-- end panel-heading -->

                        <div class="panel-body">
                            <small><?php echo $this->lang->line('user_text_help'); ?></small>

                            <?php echo form_open('', array('id' => 'form_login', 'role' => 'form'), array('token' => $_token)); ?>
                                <div class="form-group">
                                    <?php echo form_label($this->lang->line('cms_general_label_user'), 'user', array('class' => 'sr-only')); ?>
                                    <span class="input-icon">
                                        <?php echo form_input(array('id' => 'user', 'name' => 'user', 'class' => 'form-control', 'maxlength' => 30, 'placeholder' => $this->lang->line('cms_general_label_user')), set_value('user'), 'required'); ?>
                                        <i class="fa fa-user"></i>
                                    </span><!-- end input-icon -->
                                </div>
                                <div class="form-group">
                                    <?php echo form_label($this->lang->line('cms_general_label_password'), 'password', array('class' => 'sr-only')); ?>
                                    <span class="input-icon">
                                        <?php echo form_input(array('type' => 'password', 'id' => 'password', 'name' => 'password', 'class' => 'form-control', 'placeholder' => $this->lang->line('cms_general_label_password')), '', 'required'); ?>
                                        <i class="fa fa-lock"></i>
                                    </span><!-- end input-icon -->
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="chkDatos"><small><?php echo $this->lang->line('cms_general_label_remember_user_pass'); ?></small>
                                    </label>
                                </div>
                                <?php
                                    $attr = array(
                                        'class'     =>  'btn btn-cms pull-right',
                                        'type'      =>  'submit',
                                        'content'   =>  $this->lang->line('cms_general_label_button_access') . '<i class="fa fa-lock"></i>'
                                    );
                                ?>
                                <?php echo form_button($attr); ?>
                            <?php form_close(); ?>
                        </div><!-- end panel-body -->
                    </aside><!-- end panel -->
                </div><!-- end col-md-4 col-sm-6 -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section><!-- end wrapper-login -->