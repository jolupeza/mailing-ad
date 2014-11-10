	<div class="row">
		<div class="col-sm-12">
			<div class="panel">
				<div class="panel-heading">
					<h2><?php echo $this->lang->line('cms_general_title_edit_category'); ?> <a href="<?php echo base_url(); ?>admin/terms/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_add_new'); ?></a></h2>
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
					<?php if (isset($_term) && sizeof($_term) > 0) : ?>
						<?php echo form_open('', array('id' => 'form_edit_term', 'role' => 'form'), array('token' => $_token, 'id' => $_term->term_id)); ?>

						<div class="row">
							<div class="col-sm-6 col-offset-sm-6">
								<!-- Title -->
								<div class="form-group">
									<?php echo form_label($this->lang->line('cms_general_label_name'), 'name'); ?>
							        <?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), $_term->name, 'required'); ?>
							  	</div>

							  	<!-- Slug -->
								<div class="form-group">
									<?php echo form_label($this->lang->line('cms_general_label_slug'), 'slug'); ?>
							        <?php echo form_input(array('id' => 'slug', 'name' => 'slug', 'class' => 'form-control'), $_term->slug, 'required'); ?>
							  	</div>

							  	<!-- Category Parent -->
							  	<div class="form-group">
									<?php echo form_label($this->lang->line('cms_general_label_parent'), 'parent'); ?>
									<?php
										if (isset($_categories) && count($_categories)) {
											$options = array('0' => 'Ninguna');
											foreach ($_categories as $cat) {
												$options[$cat->term_id] = $cat->name;
											}
										}
									?>
							        <?php echo form_dropdown('parent', $options, $_term->parent, 'class="form-control"'); ?>
							  	</div>

							  	<!-- Description -->
								<div class="form-group">
									<?php echo form_label($this->lang->line('cms_general_label_description'), 'description'); ?>
							        <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'class' => 'form-control', 'rows' => '3'), $_term->description); ?>
							  	</div>

								<button type="submit" class="btn pull-right btn-cms"><?php echo $this->lang->line('cms_general_label_update'); ?></button>
							</div><!-- end col-sm-6 -->
						</div><!-- end row -->
						<?php echo form_close(); ?>
					<?php else : ?>

					<div class="alert alert-warning"><?php echo $this->lang->line('cms_general_label_no_found'); ?></div>

					<?php endif; ?>

				</div><!-- end panel-body -->
			</div><!-- end panel -->
		</div><!-- end col-sm-12 -->
	</div><!-- end row -->