	<div class="row">

		<div class="col-md-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_title_edit_template'); ?> <?php if ($this->user->has_permission('create_tmp_email')) : ?><a href="<?php echo base_url(); ?>admin/templates/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?> <i class="fa fa-plus"></i></a><?php endif; ?></h2>

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

				<?php if (isset($_temp) && sizeof($_temp) > 0) : ?>

					<?php echo form_open_multipart('', array('id' => 'form_edit_template', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), $_temp->name, 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Description -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_description'), 'description', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'class' => 'form-control', 'rows' => '4'), $_temp->description); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Status -->
						  	<div class="form-group">
			    				<div class="col-sm-offset-3 col-sm-9">
			      					<div class="checkbox">
			        					<label>
			        						<?php $checked = ($_temp->status == 1) ? TRUE : FALSE; ?>
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
			          						<?php echo form_checkbox('status', '1', $checked); ?> <?php echo $this->lang->line('cms_general_label_active'); ?>?
			        					</label><!-- end label -->
			      					</div><!-- end checkbox -->
			    				</div><!-- end col-sm-9 -->
			  				</div><!-- end form-group -->

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_edit'))); ?>

						</div><!-- end col-sm-8 -->

						<div class="col-sm-4">

							<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_label_file'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">
										<figure class="text-center show" id="img-file">
											<i class='fa fa-file-code-o fa-4x'></i>
										</figure>

										<p class="text-center show" id="remove-file">
											<a class="text-center remove-file" data-source="<?php echo $_temp->guid_path; ?>" data-id="<?php echo $_temp->id; ?>" href="#">
												<small><?php echo $this->lang->line('cms_general_label_remove_file'); ?></small>
											</a>
										</p>
									</div><!-- end content-widget -->

								</div><!-- end wrapper-widget -->

							</div><!-- end sidebar-right -->

							<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_label_featured_image'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">

										<div class="input-group">
										  	<input type="text" class="form-control" id="fieldID" name="featured" value="<?php echo $_temp->image; ?>">
										  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
										</div>

										<?php $visible = (!empty($_temp->image) && $_temp->image != '') ? 'show' : 'hidden'; ?>
										<figure class="img-thumbs <?php echo $visible; ?>">
											<img src="<?php echo $_temp->image; ?>" class="img-responsive img-thumbnail" alt="">
											<a href="#" class="remove-img"><?php echo $this->lang->line('cms_general_label_remove_featured_image') ?></a>
										</figure>

									</div><!-- end content-widget -->

								</div><!-- end wrapper-widget -->

							</div><!-- end sidebar-right -->

						</div><!-- end col-sm-4 -->

					</div><!-- end row -->

					<?php echo form_close(); ?>


				<?php else : ?>

					<div class="alert alert-warning"><?php echo $this->lang->line('cms_general_label_no_found'); ?></div>

				<?php endif; ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->