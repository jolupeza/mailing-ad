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
				</div><!-- end panel-heading -->
			</div><!-- end panel -->
		</div><!-- end col-sm-12 -->
	</div><!-- end row -->

	<div class="row">
		<div class="col-sm-12">
			<div class="panel">
				<div class="panel-body">
					<?php echo form_open('', array('id' => 'form_add_term', 'role' => 'form'), array('token' => $_token)); ?>

					<div class="row">
						<div class="col-sm-6 col-offset-sm-6">
							<!-- Name -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_name'), 'name'); ?>
						        <?php echo form_input(array('id' => 'name', 'name' => 'name', 'class' => 'form-control'), set_value('name'), 'required'); ?>
						  	</div>

						  	<!-- Slug -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_slug'), 'slug'); ?>
						        <?php echo form_input(array('id' => 'slug', 'name' => 'slug', 'class' => 'form-control'), set_value('slug')); ?>
						  	</div>

						  	<!-- Category Parent -->
						  	<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_parent'), 'parent'); ?>
								<?php
									$options = array('0' => 'Ninguna');
									if (isset($_categories) && count($_categories)) {
										foreach ($_categories as $cat) {
											$options[$cat->term_id] = $cat->name;
										}
									}
								?>
						        <?php echo form_dropdown('parent', $options, '0', 'class="form-control"'); ?>
						  	</div>

							<!-- Description -->
							<div class="form-group">
								<?php echo form_label($this->lang->line('cms_general_label_description'), 'description'); ?>
						        <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'class' => 'form-control', 'rows' => '3'), set_value('description')); ?>
						  	</div>

						  	<button type="submit" class="btn pull-right btn-cms"><?php echo $this->lang->line('cms_general_label_add'); ?></button>
						</div><!-- end col-sm-6 -->
					</div><!-- end row -->
					<?php echo form_close(); ?>
				</div><!-- end panel-body -->
			</div><!-- end panel -->
		</div><!-- end col-sm-12 -->
	</div><!-- end row -->