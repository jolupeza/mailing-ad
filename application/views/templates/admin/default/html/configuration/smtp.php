	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_title_smtp'); ?></h2>
					<?php if (validation_errors()) : ?>
				    <div class="alert alert-danger">
				        <?php echo validation_errors('<p><small>', '</small></p>'); ?>
				    </div>
				    <?php endif; ?>

				</div><!-- end panel-heading -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->

	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-body">

					<h3><?php echo $this->lang->line('cms_general_title_setting_smtp'); ?></h3>

					<?php echo form_open('', array('id' => 'frm_setting_smtp', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>
						<!-- Mailgun Key -->
						<div class="form-group">
							<?php
								$attr = array(
									'class'		=>	'col-sm-2 control-label'
								);
								echo form_label($this->lang->line('cms_general_mailgun_key'), 'mailgun_key', $attr);
							?>
							<div class="col-sm-5">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_key', 'name' => 'mailgun_key', 'value' => $_mailgun_key, 'required' => 'required')); ?>
							</div>
						</div>

						<!-- Mailgun PubKey -->
						<div class="form-group">
							<?php
								$attr = array(
									'class'		=>	'col-sm-2 control-label'
								);
								echo form_label($this->lang->line('cms_general_mailgun_pubkey'), 'mailgun_pubkey', $attr);
							?>
							<div class="col-sm-5">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_pubkey', 'name' => 'mailgun_pubkey', 'value' => $_mailgun_pubkey, 'required' => 'required')); ?>
							</div>
						</div>

						<!-- Mailgun Domain -->
						<div class="form-group">
							<?php
								$attr = array(
									'class'		=>	'col-sm-2 control-label'
								);
								echo form_label($this->lang->line('cms_general_mailgun_domain'), 'mailgun_domain', $attr);
							?>
							<div class="col-sm-5">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_domain', 'name' => 'mailgun_domain', 'value' => $_mailgun_domain, 'required' => 'required')); ?>
							</div>
						</div>

						<!-- Mailgun Secret -->
						<div class="form-group">
							<?php
								$attr = array(
									'class'		=>	'col-sm-2 control-label'
								);
								echo form_label($this->lang->line('cms_general_mailgun_secret'), 'mailgun_secret', $attr);
							?>
							<div class="col-sm-5">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_secret', 'name' => 'mailgun_secret', 'value' => $_mailgun_secret, 'required' => 'required')); ?>
							</div>
						</div>

						<!-- Remitente -->
						<div class="form-group">
							<?php
								$attr = array(
									'class'		=>	'col-sm-2 control-label'
								);
								echo form_label($this->lang->line('cms_general_label_sender'), 'sender', $attr);
							?>
							<div class="col-sm-5">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'sender', 'name' => 'sender', 'value' => $_sender, 'required' => 'required')); ?>
							</div>
						</div>

						<!-- Email Remitente -->
						<div class="form-group">
							<?php
								$attr = array(
									'class'		=>	'col-sm-2 control-label'
								);
								echo form_label($this->lang->line('cms_general_label_email_sender'), 'email_sender', $attr);
							?>
							<div class="col-sm-5">
					      		<?php echo form_input(array('type' => 'email', 'class' => 'form-control', 'id' => 'email_sender', 'name' => 'email_sender', 'value' => $_email_sender, 'required' => 'required')); ?>
							</div>
						</div>

						<!-- Email Reply -->
						<div class="form-group">
							<?php
								$attr = array(
									'class'		=>	'col-sm-2 control-label'
								);
								echo form_label($this->lang->line('cms_general_label_email_reply'), 'email_reply', $attr);
							?>
							<div class="col-sm-5">
					      		<?php echo form_input(array('type' => 'email', 'class' => 'form-control', 'id' => 'email_reply', 'name' => 'email_reply', 'value' => $_email_reply, 'required' => 'required')); ?>
							</div>
						</div>


					  	<button type="submit" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_save_button'); ?> <i class="fa fa-save"></i></button>

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->