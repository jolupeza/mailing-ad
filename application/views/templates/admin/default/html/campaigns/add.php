	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_label_add_campaign'); ?></h2>
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

					<?php echo form_open('', array('id' => 'form_add_campaign', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), set_value('name'), 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Sender -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_sender'), 'sender', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'sender', 'name' => 'sender', 'class' => 'form-control'), set_value('sender')); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Email Sender -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_email_sender'), 'email_sender', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('type' => 'email', 'id' => 'email_sender', 'name' => 'email_sender', 'class' => 'form-control'), set_value('email_sender')); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Email Reply -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_email_reply'), 'email_reply', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('type' => 'email', 'id' => 'email_reply', 'name' => 'email_reply', 'class' => 'form-control'), set_value('email_reply')); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Subject -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_subject'), 'subject', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'subject', 'name' => 'subject', 'class' => 'form-control'), set_value('subject'), 'required'); ?>
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
			        							);
			        						?>
			          						<?php echo form_checkbox($attr); ?> <?php echo $this->lang->line('cms_general_label_active'); ?>?
			        					</label><!-- end label -->
			      					</div><!-- end checkbox -->
			    				</div><!-- end col-sm-9 -->
			  				</div><!-- end form-group -->

							<!-- Date submit -->
						  	<div class="form-group">
						  		<?php echo form_label($this->lang->line('cms_general_label_date_submit'), 'submit_at', array('class' => 'col-sm-3 control-label')); ?>
						  		<div class="col-sm-9">
									<div class='input-group date' id='datetimepicker-add' data-date-format="YYYY/MM/DD hh:mm:ss a">
										<input type='text' class="form-control" name="submit_at" id="submit_at" />
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div><!-- end col-sm-9 -->
							</div>

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_add'), 'content' => $this->lang->line('cms_general_label_add') . '<i class="fa fa-save"></i>')); ?>

				  		</div><!-- end col-sm-8 -->

				  		<div class="col-sm-4">

				  			<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_title_newsletters'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">
										<?php if (isset($_news) && (count($_news) > 0)) { ?>

										<?php
											$options = array(
							                  '0'  		=> $this->lang->line('cms_general_label_select_newsletter')
							                );

							                foreach ($_news as $new) {
							                	$options[$new->id] = $new->name;
							                }

											echo form_dropdown('news', $options, '0', 'id="news" class="form-control"');
										?>

										<?php } else { ?>

										<p><?php echo $this->lang->line('cms_general_label_no_found'); ?></p>

										<?php } ?>

									</div><!-- end content-widget -->

								</div><!-- end wrapper-widget -->

							</div><!-- end sidebar-right -->

							<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_title_lists'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">
										<div class="radio">
									  		<label>
									    		<input type="radio" name="lists" value="1" /><?php echo $this->lang->line('cms_general_title_all_lists'); ?>
									  		</label>
										</div>

										<div class="radio">
									  		<label>
									    		<input type="radio" name="lists" value="2" /><?php echo $this->lang->line('cms_general_title_selected_lists'); ?>
									  		</label>
										</div>

									<?php if (isset($_lists) && count($_lists) > 0) : ?>
										<div class="check-lists">
										<?php foreach($_lists as $list) : ?>

											<div class="checkbox">
  												<label>
    												<input type="checkbox" value="<?php echo $list->id; ?>" name="list[]" /><?php echo $list->name; ?>
  												</label>
											</div>

										<?php endforeach; ?>
										</div>
									<?php endif; ?>

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