	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_label_title_general_settings'); ?></h2>
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
					<?php echo form_open('', array('class' => 'form-horizontal','role' => 'form'), array('token' => $_token)); ?>

						<!-- Blogname -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_site_title'), 'blogname', array('class' => 'col-sm-3 control-label')); ?>
					    	<div class="col-sm-6">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'blogname', 'name' => 'blogname', 'value' => $_blogname, 'required' => 'required')); ?>
					    	</div>
					  	</div>

						<!-- Blogdescription -->
					  	<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_site_desc'), 'blogdescription', array('class' => 'col-sm-3 control-label')); ?>
					    	<div class="col-sm-6">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'blogdescription', 'name' => 'blogdescription', 'value' => $_blogdescription)); ?>
					      		<span class="help-block"><small><?php echo $this->lang->line('cms_general_label_site_desc_help'); ?></small></span>
					    	</div>
					  	</div>

						<!-- Admin email -->
					  	<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_admin_email'), 'admin_email', array('class' => 'col-sm-3 control-label')); ?>
					    	<div class="col-sm-6">
					      		<?php echo form_input(array('class' => 'form-control', 'id' => 'admin_email', 'name' => 'admin_email', 'type' => 'text', 'value' => $_admin_email, 'required' => 'required')); ?>
					      		<span class="help-block"><small><?php echo $this->lang->line('cms_general_label_admin_email_help'); ?></small></span>
					    	</div>
					  	</div>

						<!-- Date format -->
					  	<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_date_format'), 'date_format', array('class' => 'col-sm-3 control-label')); ?>
					    	<div class="col-sm-6">
						    	<div class="radio">
						  			<label>
						  				<?php
						  					$value = 'j F, Y';
						  					$checked = ($_date_format == $value) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'date_format', 'id' => 'date_format', 'value' => 'j F, Y', 'checked' => $checked)); ?><?php echo date('j F, Y'); ?>
						  			</label>
								</div>
								<div class="radio">
						  			<label>
						  				<?php
						  					$value = 'Y/m/d';
						  					$checked = ($_date_format == $value) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'date_format', 'id' => 'date_format', 'value' => 'Y/m/d', 'checked' => $checked)); ?><?php echo date('Y/m/d'); ?>
						  			</label>
								</div>
								<div class="radio">
						  			<label>
						  				<?php
						  					$value = 'm/d/Y';
						  					$checked = ($_date_format == $value) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'date_format', 'id' => 'date_format', 'value' => 'm/d/Y', 'checked' => $checked)); ?><?php echo date('m/d/Y'); ?>
						  			</label>
								</div>
								<div class="radio">
						  			<label>
						  				<?php
						  					$value = 'd/m/Y';
						  					$checked = ($_date_format == $value) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'date_format', 'id' => 'date_format', 'value' => 'd/m/Y', 'checked' => $checked)); ?><?php echo date('d/m/Y'); ?>
						  			</label>
								</div>
								<div class="radio">
						  			<label>
						  				<?php
						  					$format = array('j F, Y', 'Y/m/d', 'm/d/Y', 'd/m/Y');
						  					$checked = (!in_array($_date_format, $format)) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'date_format', 'id' => 'date_format', 'value' => '\c\u\s\t\o\m', 'checked' => $checked)); ?><?php echo $this->lang->line('cms_general_label_custom_date_time_format'); ?>
						  			</label>
						  			<input type="text" name="date_format_custom" value="<?php echo $_date_format; ?>" style="width: 60px;" /><span> <?php echo date($_date_format); ?></span>
								</div>
					    	</div>
					  	</div>

						<!-- Time format -->
					  	<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_time_format'), 'time_format', array('class' => 'col-sm-3 control-label')); ?>
					    	<div class="col-sm-6">
						    	<div class="radio">
						  			<label>
						  				<?php
						  					$value = 'g:i a';
						  					$checked = ($_time_format == $value) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'time_format', 'id' => 'time_format', 'value' => 'g:i a', 'checked' => $checked)); ?><?php echo date('g:i a'); ?>
						  			</label>
								</div>
								<div class="radio">
						  			<label>
						  				<?php
						  					$value = 'g:i A';
						  					$checked = ($_time_format == $value) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'time_format', 'id' => 'time_format', 'value' => 'g:i A', 'checked' => $checked)); ?><?php echo date('g:i A'); ?>
						  			</label>
								</div>
								<div class="radio">
						  			<label>
						  				<?php
						  					$value = 'H:i';
						  					$checked = ($_time_format == $value) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'time_format', 'id' => 'time_format', 'value' => 'H:i', 'checked' => $checked)); ?><?php echo date('H:i'); ?>
						  			</label>
								</div>
								<div class="radio">
						  			<label>
						  				<?php
						  					$format = array('g:i a', 'g:i A', 'H:i');
						  					$checked = (!in_array($_time_format, $format)) ? TRUE : FALSE;
						  				?>
						  				<?php echo form_radio(array('name' => 'time_format', 'id' => 'time_format', 'value' => '\c\u\s\t\o\m', 'checked' => $checked)); ?><?php echo $this->lang->line('cms_general_label_custom_date_time_format'); ?>
						  			</label>
						  			<input type="text" name="time_format_custom" value="<?php echo $_time_format; ?>" style="width: 60px;" /><span> <?php echo date($_time_format); ?></span>
								</div>
					    	</div>
					  	</div>

					  	<button type="submit" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_save_button'); ?> <i class="fa fa-save"></i></button>

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->