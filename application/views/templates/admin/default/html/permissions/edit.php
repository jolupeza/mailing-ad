	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_label_edit_role'); ?> <a href="<?php echo base_url(); ?>admin/permissions/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_add'); ?> <i class="fa fa-plus"></i></a></h2>

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

					<?php echo form_open_multipart('', array('id' => 'form_edit_role', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">
						<div class="col-sm-6">
							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_role'), 'role', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'role', 'name' => 'role', 'class' => 'form-control'), $_role->role, 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Status -->
						  	<div class="form-group">
			    				<div class="col-sm-offset-3 col-sm-9">
			      					<div class="checkbox">
			        					<label>
			        						<?php
			        							$checked = ($_role->status == 1) ? TRUE : FALSE;

			        							$attr = array(
			        								'name'			=>	'status',
			        								'id'			=>	'status',
			        								'value'			=>	'1',
			        								'data-off-text'	=>  "<i class='fa fa-times'></i>",
			        								'data-on-text'	=>	"<i class='fa fa-check'></i>",
			        								'checked'		=>	$checked
			        							);
			        						?>
			          						<?php echo form_checkbox($attr); ?> <?php echo $this->lang->line('cms_general_label_active'); ?>?
			        					</label><!-- end label -->
			      					</div><!-- end checkbox -->
			    				</div><!-- end col-sm-9 -->
			  				</div><!-- end form-group -->

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_save'), 'content' => $this->lang->line('cms_general_label_save') . '<i class="fa fa-save"></i>')); ?>

				  		</div><!-- end col-sm-6 -->
				  	</div><!-- end row -->
					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->