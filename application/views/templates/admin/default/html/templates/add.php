	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_label_add_template'); ?></h2>
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

					<?php  echo form_open_multipart('', array('id' => 'form_add_template', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), set_value('name'), 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Description -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_description'), 'description', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'class' => 'form-control', 'rows' => '4'), set_value('description')); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

							<!-- Status -->
						  	<div class="form-group">
			    				<div class="col-sm-offset-3 col-sm-9">
			      					<div class="checkbox">
			        					<label>
			        						<?php
			        							$attr = array(
			        								'name'			=>	'status',
			        								'id'			=>	'status',
			        								'value'			=>	'1',
			        								'data-off-text'	=>  "<i class='fa fa-times'></i>",
			        								'data-on-text'	=>	"<i class='fa fa-check'></i>",
			        								'checked'		=>	set_value('status')
			        							);
			        						?>
			          						<?php echo form_checkbox($attr); ?> <?php echo $this->lang->line('cms_general_label_active'); ?>?
			        					</label><!-- end label -->
			      					</div><!-- end checkbox -->
			    				</div><!-- end col-sm-9 -->
			  				</div><!-- end form-group -->

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_add'), 'content' => $this->lang->line('cms_general_label_add'))); ?>

				  		</div><!-- end col-sm-8 -->

				  		<div class="col-sm-4">

				  			<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_label_file'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">

										<!-- File -->
										<div class="wrapper-file-input">
											<span class="fake-file-input"></span>
									        <?php echo form_input(array('type' => 'file', 'id' => 'source', 'name' => 'source', 'class' => 'file-input')); ?>
									  	</div>

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
										  	<input type="text" class="form-control" id="fieldID" name="featured" value="">
										  	<a class="btn iframe-btn input-group-addon" type="button" href="<?php echo base_url(); ?>filemanager/dialog.php?type=1&field_id=fieldID"><i class="fa fa-image"></i></a>
										</div>

										<figure class="img-thumbs hidden">
											<img src="" class="img-responsive" alt="">
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