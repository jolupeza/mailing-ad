	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_label_add_list'); ?></h2>
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

		<div class="col-xs-12">

			<div class="panel">

				<div class="panel-body">

					<?php echo form_open('', array('id' => 'form_add_list', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

						<!-- Name -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-2 control-label')); ?>
							<div class="col-sm-6">
					        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), set_value('name'), 'required'); ?>
					        </div><!-- end col-sm-6 -->
					  	</div><!-- end form-group -->

					  	<!-- Description -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_description'), 'description', array('class' => 'col-sm-2 control-label')); ?>
							<div class="col-sm-6">
					        	<?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'class' => 'form-control', 'rows' => '4'), set_value('description')); ?>
					        </div><!-- end col-sm-6 -->
					  	</div><!-- end form-group -->

					  	<!-- Access Level -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_access_level'), 'name', array('class' => 'col-sm-2 control-label')); ?>
							<div class="col-sm-2">
								<?php
									$options = array(
										'readonly'		=>	'Read-only',
										'members'		=>	'Members',
										'everyone'		=>	'Everyone'
									);
								?>

								<?php echo form_dropdown('access_level', $options, 'readonly', 'class="form-control" id="access_level"'); ?>
					        </div><!-- end col-sm-6 -->
					  	</div><!-- end form-group -->

						<!-- Status -->
					  	<div class="form-group">
		    				<div class="col-sm-offset-2 col-sm-6">
		      					<div class="checkbox">
		        					<label>
		        						<?php
		        							$attr = array(
		        								'name'			=>	'status',
		        								'id'			=>	'status',
		        								'value'			=>	'1',
		        								'data-off-text'	=>  "<i class='fa fa-times'></i>",
		        								'data-on-text'	=>	"<i class='fa fa-check'></i>",
		        							);
		        						?>
		          						<?php echo form_checkbox($attr); ?> <?php echo $this->lang->line('cms_general_label_active'); ?>?
		        					</label><!-- end label -->
		      					</div><!-- end checkbox -->
		    				</div><!-- end col-sm-6 -->
		  				</div><!-- end form-group -->

				  		<button type="submit" class="btn btn-cms pull-right"><?php echo $this->lang->line('cms_general_label_add'); ?></button>

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->
	</div><!-- end row -->