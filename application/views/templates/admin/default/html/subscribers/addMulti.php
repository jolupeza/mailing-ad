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

					<p class="text-muted"><?php echo $this->lang->line('cms_general_label_description_file'); ?></p>

					<?php echo form_open_multipart('', array('id' => 'form_add_subscriber_multi', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">

						<div class="col-sm-8">

							<!-- File -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_file'), 'source', array('class' => 'col-sm-3 control-label')); ?>
								<div class="col-sm-9">
									<input type="file" class="filestyle" id="source" name="source" data-buttonText="<?php echo $this->lang->line('cms_general_label_select_file'); ?>" data-iconName="glyphicon-inbox" />
						        </div><!-- end col-sm-9 -->
						  	</div><!-- end form-group -->

							<?php echo form_button(array('class' => 'btn btn-cms pull-right', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_add'), 'content' => $this->lang->line('cms_general_label_add') . '<i class="fa fa-save"></i>')); ?>

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