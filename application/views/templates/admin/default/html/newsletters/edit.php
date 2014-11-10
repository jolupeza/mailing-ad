	<div class="row">

		<div class="col-md-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_title_edit_newsletter'); ?> <?php if ($this->user->has_permission('create_news')) : ?><a href="<?php echo base_url(); ?>admin/newsletters/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?> <i class="fa fa-plus"></i></a><?php endif; ?></h2>

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

				<?php if (isset($_news) && sizeof($_news) > 0) : ?>

					<?php echo form_open('', array('id' => 'form_edit_newsletter', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), $_news[0]->name, 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Status -->
						  	<div class="form-group">
			    				<div class="col-sm-offset-3 col-sm-9">
			      					<div class="checkbox">
			        					<label>
			        						<?php
			        							$checked = ($_news[0]->status == 1) ? TRUE : FALSE;

			        							$attr = array(
			        								'name'			=>	'status',
			        								'id'			=>	'status',
			        								'value'			=>	'1',
			        								'data-off-text'	=>  "<i class='fa fa-times'></i>",
			        								'data-on-text'	=>	"<i class='fa fa-check'></i>",
			        								'checked'		=>	$checked
			        							);
			        						?>
			          						<?php echo form_checkbox('status', '1', $checked); ?> <?php echo $this->lang->line('cms_general_label_status'); ?>?
			        					</label><!-- end label -->
			      					</div><!-- end checkbox -->
			    				</div><!-- end col-sm-9 -->
			  				</div><!-- end form-group -->

							<!-- Date submit -->
						  	<div class="form-group">
						  		<?php echo form_label($this->lang->line('cms_general_label_date_submit'), 'submit_at', array('class' => 'col-sm-3 control-label')); ?>
						  		<div class="col-sm-9">
									<div class='input-group date' id='datetimepicker-edit' data-date-format="YYYY/MM/DD hh:mm:ss a" data-datetime="<?php echo $_news[0]->submit_at; ?>">
										<input type='text' class="form-control" name="submit_at" id="submit_at" />
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div><!-- end col-sm-9 -->
							</div>

						  	<!-- Content -->
							<div class="form-group">
								<div class="col-sm-12">
								<?php echo form_label($this->lang->line('cms_general_label_content'), 'content'); ?>
						        <?php echo form_textarea(array('id' => 'content', 'name' => 'content', 'class' => 'form-control edit-wysiwg')); ?>
						       	</div><!-- end col-sm-12 -->
						  	</div>

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_edit'))); ?>

						</div><!-- end col-sm-8 -->

						<div class="col-sm-4">

							<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_title_templates'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">

										<?php if (isset($_templates) && (count($_templates) > 0)) { ?>
										<ul>
											<?php foreach ($_templates as $temp) : ?>
											<li>
												<div class="checkbox">
						  							<label>
						  								<?php $checked = ($temp->id == $_news[0]->temp_id) ? TRUE : FALSE; ?>
						  								<?php $news = ($temp->id == $_news[0]->temp_id) ? $_news[0]->id : 0; ?>
								  						<?php echo form_radio(array('name' => 'temp_id', 'id' => 'temp-' . $temp->id, 'value' => $temp->id, 'checked' => $checked, 'data-news' => $news)); ?>
								  						<?php echo $temp->name; ?>
													</label>
												</div><!-- end checkbox -->
												<?php if ($temp->image != '') : ?>
												<figure class="text-center">
													<img src="<?php echo $temp->image; ?>" class="img-responsive img-thumbnail" />
												</figure>
											<?php endif; ?>
											</li>
											<?php 	endforeach; ?>
										</ul>

										<?php } else { ?>

										<p><?php echo $this->lang->line('cms_general_label_no_found'); ?></p>

										<?php } ?>

									</div><!-- end content-widget -->

								</div><!-- end wrapper-widget -->

							</div><!-- end sidebar-right -->

							<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_title_test_send'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">
										<div class="form-send-email">
											<div class="group-input">
												<label for="test-email">Email</label>
												<input type="email" name="test-email" id="test-email" />
											</div><!-- end group-input -->

											<div class="group-input">
												<label for="test-name">Name</label>
												<input type="text" name="test-name" id="test-name" />
											</div><!-- end group-input -->

  											<button id="send-test" type="button" data-id="<?php echo $_news[0]->id; ?>" class="btn btn-cms pull-right"><?php echo $this->lang->line('cms_general_title_test_send'); ?></button>
										</div><!-- end form-send-email -->
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