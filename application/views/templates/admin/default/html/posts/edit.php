<div class="row">
	<div class="col-sm-12">
		<div class="panel">
			<div class="panel-heading">
				<h2><?php echo $this->lang->line('cms_general_title_edit_post'); ?> <a href="<?php echo base_url(); ?>admin/posts/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?></a></h2>
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

			<?php if (isset($_post) && sizeof($_post) > 0) : ?>
				<div class="row">
					<?php echo form_open_multipart('', array('id' => 'form_edit_post', 'role' => 'form'), array('token' => $_token, 'id' => $_post->id)); ?>

					<div class="col-sm-8">

						<!-- Title -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_title'), 'post_title', array('class' => 'sr-only')); ?>
					        <?php echo form_input(array('id' => 'post_title', 'name' => 'post_title', 'class' => 'form-control'), $_post->post_title, 'required'); ?>
					  	</div>

						<!-- Slug -->
					  	<p><small><?php echo $this->lang->line('cms_general_label_permalink'); ?>: <?php echo base_url(); ?> <?php echo form_input(array('id' => 'post_name', 'name' => 'post_name'), $_post->post_name, 'required'); ?>/</small></p>

					  	<!-- Content -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_content'), 'post_content', array('class' => 'sr-only')); ?>
					        <?php echo form_textarea(array('id' => 'post_content', 'name' => 'post_content', 'class' => 'form-control edit-wysiwg'), $_post->post_content); ?>
					  	</div>

						<!-- Excerpt -->
						<div class="form-group">
							<?php echo form_label($this->lang->line('cms_general_label_excerpt'), 'post_excerpt'); ?>
					        <?php echo form_textarea(array('id' => 'post_excerpt', 'name' => 'post_excerpt', 'class' => 'form-control', 'rows' => '3'), $_post->post_excerpt); ?>
					  	</div>

					</div><!-- end col-sm-8 -->

					<div class="col-md-4">
						<div class="sidebar-right">
							<div class="wrapper-widget">
								<div class="title-widget">
									<h4><?php echo $this->lang->line('cms_general_label_publish'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
								</div>
								<div class="content-widget">
									<ul>
			        					<li><span class="glyphicon glyphicon-thumbs-up"></span> <?php echo $this->lang->line('cms_general_label_status'); ?>: <strong><?php echo ($_post->post_status == 1) ? $this->lang->line('cms_general_label_publish') : $this->lang->line('cms_general_label_unpublish'); ?></strong> <a href="#" class="open_edit"><?php echo $this->lang->line('cms_general_label_edit'); ?></a>
											<div class="post_edit_option">
												<?php $options = array('0' => $this->lang->line('cms_general_label_unpublish'), '1' => $this->lang->line('cms_general_label_publish')); ?>
												<?php echo form_dropdown('post_status', $options, $_post->post_status); ?>
												<?php echo form_button(array('class' => 'btn btn-cms', 'id' => 'edit-status', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_accept'), 'data-id' => $_post->id)); ?>
												<a href="#" class="cancel"><?php echo $this->lang->line('cms_general_label_cancel'); ?></a>
											</div><!-- end post_status_option -->
			        					</li>
			        					<li><span class="glyphicon glyphicon-calendar"></span> <?php echo $this->lang->line('cms_general_title_date_published'); ?>: <strong><?php echo date('d F Y h:i a', strtotime($_post->published_at)); ?></strong><a href="#" class="open_edit"><?php echo $this->lang->line('cms_general_label_edit'); ?></a>
											<div class="post_edit_option">
												<div class="form-group">
			                						<div class='input-group date' id='datetimepicker1' data-date-format="YYYY/MM/DD hh:mm:ss a" data-datetime="<?php echo $_post->published_at; ?>">
			                    						<input type='text' class="form-control" name="published_at" id="published_at" />
			                    						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			                						</div>
			            						</div>
												<?php echo form_button(array('class' => 'btn btn-cms', 'id' => 'edit-published', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_accept'), 'data-id' => $_post->id)); ?>
												<a href="#" class="cancel"><?php echo $this->lang->line('cms_general_label_cancel'); ?></a>
											</div><!-- end post_status_option -->
			        					</li>
			        				</ul>
									<button type="submit" class="btn pull-right btn-cms"><?php echo $this->lang->line('cms_general_label_update'); ?></button>
								</div><!-- end content-widget -->
							</div><!-- end wrapper-widget -->

							<div class="wrapper-widget">
								<div class="title-widget">
									<h4><?php echo $this->lang->line('cms_general_label_categories'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
								</div>
								<div class="content-widget">
									<ul>
										<?php
											if (isset($_categories['masters']) && count($_categories['masters'])) {
												foreach ($_categories['masters'] as $master) : ?>
										<li>
											<div class="checkbox">
					  							<label>
					  						<?php $select = (in_array($master->term_id, $_categoriesPost)) ? TRUE : FALSE; ?>
					  						<?php echo form_checkbox('post_category[]', $master->term_id, $select); ?>
					  						<?php echo $master->name; ?>
												</label>
											</div><!-- end checkbox -->

											<?php
												if (isset($_categories['childrens'])) {
													$ci =& get_instance();
													echo $ci->childrenCategories($_categories['childrens'], $master->term_id, $_post->id);
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
								<?php
									$CI =& get_instance();
									$CI->load->model('Posts_Model');
									$featured = $CI->Posts_Model->getFeaturedImage($_post->id);
									if (count($featured) > 0) {
								?>
									<figure>
										<img src="<?php echo $featured->guid ?>" class="img-responsive img-thumbnail" />
									</figure>
									<p class="text-center"><a class="text-center remove-img-featured" data-id="<?php echo $featured->id; ?>" href=""><small><?php echo $this->lang->line('cms_general_label_remove_featured_image'); ?></small></a></p>
								<?php } else { ?>
									<input type="file" name="image" id="image" />
								<?php } ?>
								</div><!-- end content-widget -->
							</div><!-- end wrapper-widget -->

						</div><!-- end sidebar-right -->

					</div><!-- end col-sm-4 -->

					<?php echo form_close(); ?>

				</div><!-- end row -->

			<?php else : ?>

				<div class="alert alert-warning"><?php echo $this->lang->line('cms_general_label_no_found'); ?></div>

			<?php endif; ?>

			</div><!-- end panel-body -->

		</div><!-- end panel -->

	</div><!-- end col-sm-12 -->

</div><!-- end row -->