<div class="row">

	<div class="col-sm-12">

		<div class="panel">

			<div class="panel-heading">
				<h2><?php echo $this->lang->line('cms_general_add_new'); ?></h2>
				<?php if (validation_errors()) : ?>
	            <div class="alert alert-danger">
	                <?php echo validation_errors('<p><small>', '</small></p>'); ?>
	            </div>
	            <?php endif; ?>
			</div>

		</div><!-- end panel -->

	</div><!-- end col-sm-12 -->

</div> <!-- end row -->

<div class="row">

	<div class="col-sm-12">

		<div class="panel">

			<div class="panel-body">

				<?php echo form_open_multipart('', array('id' => 'form_add_post', 'role' => 'form'), array('token' => $_token)); ?>

				<div class="row">

					<div class="col-sm-8">

						<!-- Title -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_title'), 'post_title'); ?>
					        <?php echo form_input(array('id' => 'post_title', 'name' => 'post_title', 'class' => 'form-control'), set_value('post_title'), 'required'); ?>
					  	</div>

					  	<!-- Content -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_content'), 'post_content'); ?>
						    <?php echo form_textarea(array('id' => 'post_content', 'name' => 'post_content', 'class' => 'form-control edit-wysiwg'), set_value('post_content')); ?>
					  	</div>

						<!-- Excerpt -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_excerpt'), 'post_excerpt'); ?>
					        <?php echo form_textarea(array('id' => 'post_excerpt', 'name' => 'post_excerpt', 'class' => 'form-control', 'rows' => '3'), set_value('post_excerpt')); ?>
					  	</div>

					</div><!-- end col-sm-8 -->

					<div class="col-sm-4">

						<div class="sidebar-right">

							<div class="wrapper-widget">
								<div class="title-widget">
									<h4><?php echo $this->lang->line('cms_general_label_publish'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
								</div>
								<div class="content-widget">
									<ul>
				    					<li><span class="glyphicon glyphicon-thumbs-up"></span> <?php echo $this->lang->line('cms_general_label_status'); ?>: <strong><?php echo $this->lang->line('cms_general_label_publish'); ?></strong> <a href="#" class="open_edit"><?php echo $this->lang->line('cms_general_label_edit'); ?></a>
											<div class="post_edit_option">
												<?php $options = array('0' => $this->lang->line('cms_general_label_unpublish'), '1' => $this->lang->line('cms_general_label_publish')); ?>
												<?php echo form_dropdown('post_status', $options, '1'); ?>
												<?php echo form_button(array('class' => 'btn btn-cms', 'id' => 'edit-status', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_accept'))); ?>
												<a href="#" class="cancel"><?php echo $this->lang->line('cms_general_label_cancel'); ?></a>
											</div><!-- end post_status_option -->
				    					</li>
				    					<li><span class="glyphicon glyphicon-calendar"></span> <?php echo $this->lang->line('cms_general_title_date_published'); ?>: <strong><?php echo date('d F Y h:i a'); ?></strong><a href="#" class="open_edit"><?php echo $this->lang->line('cms_general_label_edit'); ?></a>
											<div class="post_edit_option">
												<div class="form-group">
				            						<div class='input-group date' id='datetimepicker2' data-date-format="YYYY/MM/DD hh:mm:ss">
				                						<input type='text' class="form-control" name="published_at" id="published_at" />
				                						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				            						</div>
				        						</div>
												<?php echo form_button(array('class' => 'btn btn-cms', 'id' => 'edit-published', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_accept'))); ?>
												<a href="#" class="cancel"><?php echo $this->lang->line('cms_general_label_cancel'); ?></a>
											</div><!-- end post_status_option -->
				    					</li>
				    				</ul>
									<button type="submit" class="btn pull-right btn-cms"><?php echo $this->lang->line('cms_general_label_add'); ?></button>
								</div><!-- end content-widgets -->
							</div><!-- end wrapper-widget -->

							<div class="wrapper-widget">
								<div class="title-widget">
									<h4><?php echo $this->lang->line('cms_general_title_categories'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
								</div>
								<div class="content-widget">
									<ul>
										<?php
											if (isset($_categories['masters']) && count($_categories['masters'])) {
												foreach ($_categories['masters'] as $master) : ?>
										<li>
											<div class="checkbox">
					  							<label>
					  						<?php echo form_checkbox('post_category[]', $master->term_id); ?>
					  						<?php echo $master->name; ?>
												</label>
											</div><!-- end checkbox -->

											<?php
												if (isset($_categories['childrens'])) {
													$ci =& get_instance();
													echo $ci->childrenCategories($_categories['childrens'], $master->term_id);
												}
											?>

										</li>
										<?php 	endforeach;
											}
										?>
									</ul>
								</div><!-- end content-widget -->
							</div><!-- end wrapper-widget -->

							<div class="wrapper-widget">
								<div class="title-widget">
									<h4><?php echo $this->lang->line('cms_general_label_featured_image'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
								</div>
								<div class="content-widget">
									<!-- Title -->
									<div class="wrapper-file-input">
										<span class="fake-file-input"><small><?php echo $this->lang->line('cms_general_label_assign_featured_image'); ?></small></span>
								        <?php echo form_input(array('type' => 'file', 'id' => 'image', 'name' => 'image', 'class' => 'file-input')); ?>
								  	</div>
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