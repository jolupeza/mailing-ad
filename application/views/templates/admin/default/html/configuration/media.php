	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_title_setting_multimedia'); ?></h2>
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

					<h3><?php echo $this->lang->line('cms_general_title_size_images'); ?></h3>
					<p><?php echo $this->lang->line('cms_general_title_size_images_desc'); ?></p>

					<?php echo form_open('', array('id' => 'frm_setting_media'), array('token' => $_token)); ?>
						<div class="form-inline">
							<div class="legend"><?php echo $this->lang->line('cms_general_title_image_thumbnails'); ?></div>
							<?php
								$datos = array(
									'name'		=>	'thumb_width',
									'id'		=>	'thumb_width',
									'type'		=>	'number',
									'value'		=>	$_thumb_s_w,
									'maxlength'	=>	'4'
								);
								$datos2 = array(
									'name'		=>	'thumb_height',
									'id'		=>	'thumb_height',
									'type'		=>	'number',
									'value'		=>	$_thumb_s_h,
									'maxlength'	=>	'4'
								);
							?>
							<div class="area-input"><?php echo $this->lang->line('cms_general_label_width'); ?> <?php echo form_input($datos); ?> <?php echo $this->lang->line('cms_general_label_height'); ?> <?php echo form_input($datos2); ?>
								<div class="checkbox">
				    				<label>
				    					<?php $checked = ($_thumb_crop == 1) ? 'checked="checked"' : ''; ?>
				      					<input name="crop" value="1" type="checkbox" <?php echo $checked; ?>><?php echo $this->lang->line('cms_general_label_message_resize'); ?>
				    				</label><!-- end label -->
				  				</div><!-- end checkbox -->
							</div><!-- end area-input -->
						</div><!-- end form-inline -->

						<div class="form-inline">
							<div class="legend"><?php echo $this->lang->line('cms_general_title_size_medio'); ?></div>
							<?php
								$datos = array(
									'name'		=>	'medio_width',
									'id'		=>	'medio_width',
									'type'		=>	'number',
									'value'		=>	$_med_s_w,
									'maxlength'	=>	'4'
								);
								$datos2 = array(
									'name'		=>	'medio_height',
									'id'		=>	'medio_height',
									'type'		=>	'number',
									'value'		=>	$_med_s_h,
									'maxlength'	=>	'4'
								);
							?>
							<div class="area-input"><?php echo $this->lang->line('cms_general_label_width'); ?> <?php echo form_input($datos); ?> <?php echo $this->lang->line('cms_general_label_height'); ?> <?php echo form_input($datos2); ?>
							</div><!-- end area-input -->
						</div><!-- end form-inline -->

						<div class="form-inline">
							<div class="legend"><?php echo $this->lang->line('cms_general_title_size_large'); ?></div>
							<?php
								$datos = array(
									'name'		=>	'large_width',
									'id'		=>	'large_width',
									'type'		=>	'number',
									'value'		=>	$_larg_s_w,
									'maxlength'	=>	'4'
								);
								$datos2 = array(
									'name'		=>	'large_height',
									'id'		=>	'large_height',
									'type'		=>	'number',
									'value'		=>	$_larg_s_h,
									'maxlength'	=>	'4'
								);
							?>
							<div class="area-input"><?php echo $this->lang->line('cms_general_label_width'); ?> <?php echo form_input($datos); ?> <?php echo $this->lang->line('cms_general_label_height'); ?> <?php echo form_input($datos2); ?>
							</div><!-- end area-input -->
						</div><!-- end form-inline -->

					  	<button type="submit" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_save_button'); ?> <i class="fa fa-save"></i></button>

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->