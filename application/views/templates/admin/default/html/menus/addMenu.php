<section class="main-content">
	<h2><?php echo $this->lang->line('cms_general_edit_menus'); ?></h2>

	<?php if (validation_errors()) : ?>
    <div class="alert alert-danger">
        <?php echo validation_errors('<p><small>', '</small></p>'); ?>
    </div>
    <?php endif; ?>

	<?php if (isset($_navMenu) && count($_navMenu) > 0) : ?>
	<div class="panel panel-cms">
		<div class="panel-body">
			<p><?php echo $this->lang->line('cms_general_legend_edit_menus'); ?>
				<select name="list-menus" id="list-menus">
					<option value="0">-- Seleccionar --</option>
					<?php foreach($_navMenu as $nm) : ?>
					<option value="<?php echo $nm->term_id ?>"><?php echo $nm->name; ?></option>
					<?php endforeach; ?>
				</select>
				<button id="select-menu" data-href="admin/menus/index/" type="button" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_choose'); ?></button>
				o <a class="inverse" href="<?php echo base_url(); ?>admin/menus/addMenu"><?php echo $this->lang->line('cms_general_label_new_menu'); ?></a>
			</p>
		</div>
	</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-4">
			<div class="panel-group disabled" id="accordion">
  				<div class="panel panel-cms">
    				<div class="panel-heading">
      					<h4 class="panel-title">
        					<a data-toggle="collapse" data-parent="#accordion" href="#collapsePages"><?php echo $this->lang->line('cms_general_title_pages'); ?>
        					<span class="caret pull-right"></span></a>
      					</h4>
    				</div>
    				<div id="collapsePages" class="panel-collapse collapse in">
      					<div class="panel-body">
        					<ul>
								<?php
									if (isset($_pages['masters']) && count($_pages['masters'])) {
										foreach ($_pages['masters'] as $master) : ?>
								<li>
									<div class="checkbox">
			  							<label>
			  						<?php
			  							$datos = array(
			  								'name'			=>		'post_page[]',
			  								'class'			=>		'chk-opt',
			  								'value'			=>		$master->id,
			  								'data-title' 	=>		$master->post_title,
			  								'data-type'		=>		$this->lang->line('cms_general_title_page'),
			  								'data-object'	=>		'page',
			  								'disabled'		=>		'disabled'
			  							);
			  						?>
			  						<?php echo form_checkbox($datos); ?>
			  						<?php echo $master->post_title; ?>
										</label>
									</div><!-- end checkbox -->

									<?php
										if (isset($_pages['childrens'])) {
											$ci =& get_instance();
											echo $ci->childrenPages($_pages['childrens'], $master->id, 'disabled');
										}
									?>

								</li>
								<?php 	endforeach;
									}
								?>
							</ul>
      					</div>
      					<div class="panel-footer">
							<button type="button" class="btn pull-right btn-default add-menu-item" disabled="disabled"><?php echo $this->lang->line('cms_general_label_add_menu'); ?></button>
						</div>
    				</div>
  				</div>

  				<div class="panel panel-cms">
    				<div class="panel-heading">
      					<h4 class="panel-title">
        					<a data-toggle="collapse" data-parent="#accordion" href="#collapseLinks"><?php echo $this->lang->line('cms_general_title_links'); ?>
        					<span class="caret pull-right"></span></a>
      					</h4>
    				</div>
    				<div id="collapseLinks" class="panel-collapse collapse">
						<form role="form" id="frm-link-custom">
	      					<div class="panel-body">
	  								<div class="form-group">
	    								<label for="link-url">URL</label>
	      								<input type="url" class="form-control" id="link-url" name="link-url" value="http://" required="required" disabled />
	  								</div>
	  								<div class="form-group">
	    								<label for="link-name"><?php echo $this->lang->line('cms_general_label_link_text'); ?></label>
	      								<input type="text" class="form-control" id="link-name" name="link-name" required="required" disabled />
	  								</div>
	  								<input type="hidden" name="type" value="<?php echo $this->lang->line('cms_general_label_custom_date_time_format') ?>" />
	      					</div>
	      					<div class="panel-footer">
								<button id="add-menu-item-link" type="button" class="btn pull-right btn-default" disabled="diabled"><?php echo $this->lang->line('cms_general_label_add_menu'); ?></button>
							</div>
						</form>
    				</div>
  				</div>

  				<div class="panel panel-cms">
    				<div class="panel-heading">
      					<h4 class="panel-title">
        					<a data-toggle="collapse" data-parent="#accordion" href="#collapseCategories"><?php echo $this->lang->line('cms_general_title_categories'); ?>
        					<span class="caret pull-right"></span></a>
      					</h4>
    				</div>
    				<div id="collapseCategories" class="panel-collapse collapse">
      					<div class="panel-body">
        					<ul>
								<?php
									if (isset($_categories['masters']) && count($_categories['masters'])) {
										foreach ($_categories['masters'] as $master) : ?>
								<li>
									<div class="checkbox">
			  							<label>
			  						<?php
			  							$datos = array(
			  								'name'			=>		'post_category[]',
			  								'class'			=>		'chk-opt',
			  								'value'			=>		$master->term_id,
			  								'data-title' 	=>		$master->name,
			  								'data-type'		=>		$this->lang->line('cms_general_title_category'),
			  								'data-object'	=>		'category',
			  								'disabled'		=>		'disabled'
			  							);
			  						?>
			  						<?php echo form_checkbox($datos); ?>
			  						<?php echo $master->name; ?>
										</label>
									</div><!-- end checkbox -->

									<?php
										if (isset($_categories['childrens'])) {
											$ci =& get_instance();
											echo $ci->childrenCategories($_categories['childrens'], $master->term_id, 'disabled');
										}
									?>

								</li>
								<?php 	endforeach;
									}
								?>
							</ul>
      					</div>
      					<div class="panel-footer">
							<button type="submit" class="btn pull-right btn-default add-menu-item" disabled="disabled"><?php echo $this->lang->line('cms_general_label_add_menu'); ?></button>
						</div>
    				</div>
  				</div>
			</div>
		</div><!-- end .col-md-4 -->

		<div class="col-md-8">
			<?php echo form_open('', array('role' => 'form'), array('token' => $_token)); ?>
			<div class="panel panel-cms structure-menu">
				<div class="panel-heading">
  					<h4 class="panel-title">
  						<em><?php echo $this->lang->line('cms_general_label_name_menu'); ?>: </em> <input type="text" name="name_menu" id="name_menu" value="" required="required" placeholder="<?php echo $this->lang->line('cms_general_label_name_menu_placeholder'); ?>" />
  						<button type="submit" class="btn pull-right btn-default"><?php echo $this->lang->line('cms_general_label_create_menu'); ?></button>
  					</h4>
				</div><!-- end panel-heading -->
  				<div class="panel-body">
					<p><?php echo $this->lang->line('cms_general_label_help_add_menu'); ?></p>
				</div><!-- end panel-body -->
				<div class="panel-footer">
					<button type="submit" class="btn pull-right btn-default"><?php echo $this->lang->line('cms_general_label_create_menu'); ?></button>
				</div>
			</div><!-- end panel-cms -->
			<?php echo form_close(); ?>
		</div><!-- end col-md-8 -->
	</div><!-- end row -->
</section><!-- end main-content -->