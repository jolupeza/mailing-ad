	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_title_edit_list'); ?> <?php if ($this->user->has_permission('create_lists')) : ?><a href="<?php echo base_url(); ?>admin/lists/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?> <i class="fa fa-plus"></i></a><?php endif; ?></h2>

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

		<div class="col-md-12">

			<div class="panel">

			<?php if (isset($_list) && sizeof($_list) > 0) : ?>

				<div class="panel-body">

					<?php echo form_open('', array('id' => 'form_edit_list', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

						<!-- Name -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-2 control-label')); ?>
							<div class="col-sm-6">
					        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), $_list[0]->name, 'required'); ?>
					        </div><!-- end col-sm-6 -->
					  	</div><!-- end form-group -->

					  	<!-- Description -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_description'), 'description', array('class' => 'col-sm-2 control-label')); ?>
							<div class="col-sm-6">
					        	<?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'class' => 'form-control', 'rows' => '4'), $_list[0]->description); ?>
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

								<?php echo form_dropdown('access_level', $options, $_list[0]->access_level, 'class="form-control" id="access_level"'); ?>
					        </div><!-- end col-sm-6 -->
					  	</div><!-- end form-group -->

					  	<!-- Status -->
					  	<div class="form-group">
		    				<div class="col-sm-offset-2 col-sm-6">
		      					<div class="checkbox">
		        					<label>
		        						<?php $checked = ($_list[0]->status == 1) ? TRUE : FALSE; ?>
		        						<?php
		        							$attr = array(
		        								'name'			=>	'status',
		        								'id'			=>	'status',
		        								'value'			=>	'1',
		        								'data-off-text'	=>  "<i class='fa fa-times'></i>",
		        								'data-on-text'	=>	"<i class='fa fa-check'></i>",
		        								'checked'		=>	$checked
		        							);
		        						?>
		          						<?php echo form_checkbox('status', '1', $checked); ?> <?php echo $this->lang->line('cms_general_label_confirm'); ?>?
		        					</label><!-- end label -->
		      					</div><!-- end checkbox -->
		    				</div><!-- end col-sm-6 -->
		  				</div><!-- end form-group -->

					  	<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_edit'))); ?>

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			<?php else : ?>

				<div class="alert alert-warning"><?php echo $this->lang->line('cms_general_label_no_found'); ?></div>

			<?php endif; ?>

			</div><!-- end panel -->

		</div><!-- end col-md-12 -->

	</div><!-- end row -->