	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_label_add_subscriber'); ?></h2>
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

					<?php echo form_open('', array('id' => 'form_add_subscriber', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'name', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), set_value('name'), 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Email -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_email'), 'email', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'email', 'type' => 'email', 'name' => 'email', 'class' => 'form-control'), set_value('email'), 'required'); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Company -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_company'), 'company', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'company', 'name' => 'company', 'class' => 'form-control'), set_value('company')); ?>
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

						  	<!-- Address -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_address'), 'address', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
						        	<?php echo form_input(array('id' => 'address', 'name' => 'address', 'class' => 'form-control'), set_value('address')); ?>
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
			          						<?php echo form_checkbox($attr); ?> <?php echo $this->lang->line('cms_general_label_status'); ?>?
			        					</label><!-- end label -->
			      					</div><!-- end checkbox -->
			    				</div><!-- end col-sm-9 -->
			  				</div><!-- end form-group -->

							<!-- Date birth -->
						  	<div class="form-group">
						  		<?php echo form_label($this->lang->line('cms_general_label_date_birth'), 'date_birth', array('class' => 'col-sm-3 control-label')); ?>
						  		<div class="col-sm-9">
									<div class='input-group date' id='datetimepicker-add' data-date-format="YYYY/MM/DD">
										<input type='text' class="form-control" name="date_birth" id="date_birth" />
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div><!-- end col-sm-9 -->
							</div>

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_add'), 'content' => $this->lang->line('cms_general_label_add'))); ?>

				  		</div><!-- end col-sm-8 -->

				  		<div class="col-sm-4">

				  			<div class="sidebar-right">

								<div class="wrapper-widget">
									<div class="title-widget">
										<h4><?php echo $this->lang->line('cms_general_title_lists'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
									</div>
									<div class="content-widget">
										<?php if (isset($_lists) && (count($_lists) > 0)) { ?>
										<ul>
											<?php foreach ($_lists as $list) : ?>
											<li>
												<div class="checkbox">
						  							<label>
						  								<?php $checked = (isset($_list_subs) && in_array($list->id, $_list_subs)) ? TRUE : FALSE; ?>
								  						<?php echo form_checkbox('lists[]', $list->id, $checked); ?>
								  						<?php echo $list->name; ?>
													</label>
												</div><!-- end checkbox -->

											</li>
											<?php 	endforeach; ?>
										</ul>

										<?php } else { ?>

										<p><?php echo $this->lang->line('cms_general_label_no_found'); ?></p>

										<?php } ?>

									</div><!-- end content-widget -->

								</div><!-- end wrapper-widget -->

				  		</div><!-- end col-sm-4 -->

				  	</div><!-- end row -->

					<?php echo form_close(); ?>

				</div><!-- end panel-body -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->