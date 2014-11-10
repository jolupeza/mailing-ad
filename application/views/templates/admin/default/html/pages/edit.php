<section class="main-content">
	<?php if (isset($_page) && sizeof($_page) > 0) : ?>
	<div class="row">
		<?php echo form_open_multipart('', array('id' => 'form_edit_page', 'role' => 'form'), array('token' => $_token, 'id' => $_page->id)); ?>
		<div class="col-md-8">
			<h2><?php echo $this->lang->line('cms_general_title_edit_page'); ?> <a href="<?php echo base_url(); ?>admin/pages/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?></a></h2>
			<?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <?php echo validation_errors('<p><small>', '</small></p>'); ?>
            </div>
            <?php endif; ?>

			<!-- Title -->
			<div class="form-group">
				<?php echo form_label($this->lang->line('cms_general_label_title'), 'post_title', array('class' => 'sr-only')); ?>
		        <?php echo form_input(array('id' => 'post_title', 'name' => 'post_title', 'class' => 'form-control'), $_page->post_title, 'required'); ?>
		  	</div>

			<!-- Slug -->
		  	<p><small><?php echo $this->lang->line('cms_general_label_permalink'); ?>: <?php echo base_url(); ?> <?php echo form_input(array('id' => 'post_name', 'name' => 'post_name'), $_page->post_name, 'required'); ?>/</small></p>

		  	<!-- Content -->
			<div class="form-group">
				<?php echo form_label($this->lang->line('cms_general_label_content'), 'post_content', array('class' => 'sr-only')); ?>
		        <?php echo form_textarea(array('id' => 'post_content', 'name' => 'post_content', 'class' => 'form-control edit-wysiwg'), $_page->post_content); ?>
		  	</div>
		</div><!-- end col-md-8 -->
		<div class="col-md-4">
			<div class="sidebar-right">
				<div class="wrapper-widget">
					<div class="title-widget">
						<h4><?php echo $this->lang->line('cms_general_label_publish'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
					</div>
					<div class="content-widget">
						<ul>
        					<li><span class="glyphicon glyphicon-thumbs-up"></span> <?php echo $this->lang->line('cms_general_label_status'); ?>: <strong><?php echo ($_page->post_status == 1) ? $this->lang->line('cms_general_label_publish') : $this->lang->line('cms_general_label_unpublish'); ?></strong>
        						<?php
									$CI =& get_instance();
									$CI->load->model('Posts_Model');
									if (!$CI->Posts_Model->verifyPagesChildren($_page->id)) :
								?>
        						<a href="#" class="open_edit"><?php echo $this->lang->line('cms_general_label_edit'); ?></a>
        						<?php endif; ?>
								<div class="post_edit_option">
									<?php $options = array('0' => $this->lang->line('cms_general_label_unpublish'), '1' => $this->lang->line('cms_general_label_publish')); ?>
									<?php echo form_dropdown('post_status', $options, $_page->post_status); ?>
									<?php echo form_button(array('class' => 'btn btn-cms', 'id' => 'edit-status', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_accept'), 'data-id' => $_page->id)); ?>
									<a href="#" class="cancel"><?php echo $this->lang->line('cms_general_label_cancel'); ?></a>
								</div><!-- end post_status_option -->
        					</li>
        					<li><span class="glyphicon glyphicon-calendar"></span> <?php echo $this->lang->line('cms_general_title_date_published'); ?>: <strong><?php echo date('d F Y h:i a', strtotime($_page->published_at)); ?></strong><a href="#" class="open_edit"><?php echo $this->lang->line('cms_general_label_edit'); ?></a>
								<div class="post_edit_option">
									<div class="form-group">
                						<div class='input-group date' id='datetimepicker1' data-date-format="YYYY/MM/DD hh:mm:ss" data-datetime="<?php echo $_page->published_at; ?>">
                    						<input type='text' class="form-control" name="published_at" id="published_at" />
                    						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                						</div>
            						</div>
									<?php echo form_button(array('class' => 'btn btn-cms', 'id' => 'edit-published', 'value' => $this->lang->line('cms_general_label_edit'), 'content' => $this->lang->line('cms_general_label_accept'), 'data-id' => $_page->id)); ?>
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
						<?php
							if (isset($_pages) && count($_pages)) {
								$options = array('0' => $this->lang->line('cms_general_label_no_parent'));
								foreach ($_pages as $page) {
									if ($_page->id == $page->id) continue;
									$options[$page->id] = $page->post_title;
								}
							}
						?>
			        	<?php echo form_dropdown('post_parent', $options, $_page->post_parent, 'class="form-control"'); ?>
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
						$featured = $CI->Posts_Model->getFeaturedImage($_page->id);
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
		</div><!-- end col-md-4 -->
		<?php echo form_close(); ?>
	</div><!-- end row -->
	<?php else : ?>

	<div class="alert alert-warning"><?php echo $this->lang->line('cms_general_label_no_found'); ?></div>

	<?php endif; ?>
</section>