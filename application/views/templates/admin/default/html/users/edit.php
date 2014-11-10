	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_label_edit_user'); ?> <a href="<?php echo base_url(); ?>admin/users/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_add'); ?> <i class="fa fa-plus"></i></a></h2>
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

					<?php echo form_open('', array('id' => 'form_edit_user', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), $_user->name, 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Username -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_user'), 'user', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'user', 'name' => 'user', 'class' => 'form-control'), $_user->user, 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Email -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_email'), 'email', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('type' => 'email' , 'id' => 'email', 'name' => 'email', 'class' => 'form-control'), $_user->email, 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						<?php if ($this->user->has_permission('admin_users')) : ?>
						  	<!-- Role -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_role'), 'role', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">

									<?php if (count($_roles) > 0 && $_roles) : ?>
										<select name="role" class="form-control">
									  		<option value="0"><?php echo $this->lang->line('cms_general_label_select_role'); ?></option>

										<?php foreach ($_roles as $role) : ?>

									  	<?php $selected = ($role->id == $_user->role) ? 'selected="selected"' : ''; ?>

									  		<option value="<?php echo $role->id; ?>" <?php echo $selected; ?>><?php echo $role->role; ?></option>

									  	<?php endforeach; ?>
										</select>

									<?php endif; ?>

						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Status -->
						  	<div class="form-group">
			    				<div class="col-sm-offset-3 col-sm-9">
			      					<div class="checkbox">
			        					<label>
			        						<?php
			        							$checked = ($_user->status == 1) ? TRUE : FALSE;

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

			  			<?php endif; ?>

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_add'), 'content' => $this->lang->line('cms_general_label_save') . '<i class="fa fa-save"></i>')); ?>

				  		</div><!-- end col-sm-8 -->

				  		<div class="col-sm-4">

				  			<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_label_avatar'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">

										<div class="input-group">
										  	<input type="text" class="form-control" id="fieldID" name="avatar" value="<?php echo $_user->avatar; ?>" />
										  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
										</div>

										<?php $visible = (!empty($_user->avatar) && $_user->avatar != '') ? 'show' : 'hidden'; ?>
										<figure class="img-thumbs <?php echo $visible; ?>">
											<img src="<?php echo $_user->avatar; ?>" class="img-responsive img-thumbnail" alt="">
											<a href="#" class="remove-img"><?php echo $this->lang->line('cms_general_label_remove_featured_image') ?></a>
										</figure>

									</div><!-- end content-widget -->

								</div><!-- end wrapper-widget -->

							</div><!-- end sidebar-right -->

				  		</div><!-- end col-sm-4 -->

				  	</div><!-- end row -->

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->