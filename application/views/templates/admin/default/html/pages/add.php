<section class="main-content">
	<div class="row">
		<?php echo form_open_multipart('', array('id' => 'form_add_page', 'role' => 'form'), array('token' => $_token)); ?>
		<div class="col-md-8">
			<h2><?php echo $this->lang->line('cms_general_add_new'); ?></h2>
			<?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <?php echo validation_errors('<p><small>', '</small></p>'); ?>
            </div>
            <?php endif; ?>

			<!-- Title -->
			<div class="form-group">
				<?php echo form_label($this->lang->line('cms_general_label_title'), 'post_title', array('class' => 'sr-only')); ?>
		        <?php echo form_input(array('id' => 'post_title', 'name' => 'post_title', 'class' => 'form-control'), set_value('post_title'), 'required'); ?>
		  	</div>

		  	<!-- Content -->
			<div class="form-group">
				<?php echo form_label($this->lang->line('cms_general_label_content'), 'post_content', array('class' => 'sr-only')); ?>
		        <?php echo form_textarea(array('id' => 'post_content', 'name' => 'post_content', 'class' => 'form-control edit-wysiwg'), set_value('post_content')); ?>
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
						<h4><?php echo $this->lang->line('cms_general_label_categories'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
					</div>
					<div class="content-widget">
						<?php
							if (isset($_pages) && count($_pages)) {
								$options = array('0' => $this->lang->line('cms_general_label_no_parent'));
								foreach ($_pages as $page) {
									$options[$page->id] = $page->post_title;
								}
							}
						?>
			        	<?php echo form_dropdown('post_parent', $options, '0', 'class="form-control"'); ?>
					</div><!-- end content-widget -->
				</div><!-- end wrapper-widget -->

				<div class="wrapper-widget">
					<div class="title-widget">
						<h4><?php echo $this->lang->line('cms_general_label_featured_image'); ?> <span class="oc-panel glyphicon glyphicon-chevron-up"></span></h4>
					</div>
					<div class="content-widget">
						<!-- Image Featured -->
						<input type="file" name="image" id="image" />
					</div><!-- end content-widget -->
				</div><!-- end wrapper-widget -->
			</div>
		</div><!-- end col-md-4 -->
		<?php echo form_close(); ?>
	</div><!-- end row -->
</section>