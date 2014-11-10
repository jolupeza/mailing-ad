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
								<?php $mailgun_key = (isset($_mailgun_key) && !empty($_mailgun_key) ? $_mailgun_key : ''); ?>
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_key', 'name' => 'mailgun_key', 'value' => $mailgun_key, 'required' => 'required')); ?>
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
								<?php $mailgun_pubkey = (isset($_mailgun_pubkey) && !empty($_mailgun_pubkey) ? $_mailgun_pubkey : ''); ?>
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_pubkey', 'name' => 'mailgun_pubkey', 'value' => $mailgun_pubkey, 'required' => 'required')); ?>
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
								<?php $mailgun_domain = (isset($_mailgun_domain) && !empty($_mailgun_domain) ? $_mailgun_domain : ''); ?>
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_domain', 'name' => 'mailgun_domain', 'value' => $mailgun_domain, 'required' => 'required')); ?>
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
								<?php $mailgun_secret = (isset($_mailgun_secret) && !empty($_mailgun_secret) ? $_mailgun_secret : ''); ?>
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'mailgun_secret', 'name' => 'mailgun_secret', 'value' => $mailgun_secret, 'required' => 'required')); ?>
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
								<?php $sender = (isset($_sender) && !empty($_sender) ? $_sender->option_value : ''); ?>
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'sender', 'name' => 'sender', 'value' => $sender, 'required' => 'required')); ?>
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
								<?php $email_sender = (isset($_email_sender) && !empty($_email_sender) ? $_email_sender->option_value : ''); ?>
					      		<?php echo form_input(array('type' => 'email', 'class' => 'form-control', 'id' => 'email_sender', 'name' => 'email_sender', 'value' => $email_sender, 'required' => 'required')); ?>
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
								<?php $email_reply = (isset($_email_reply) && !empty($_email_reply) ? $_email_reply->option_value : ''); ?>
					      		<?php echo form_input(array('type' => 'email', 'class' => 'form-control', 'id' => 'email_reply', 'name' => 'email_reply', 'value' => $email_reply, 'required' => 'required')); ?>
							</div>
						</div>


					  	<button type="submit" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_save_button'); ?> <i class="fa fa-save"></i></button>

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->