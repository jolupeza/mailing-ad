<section class="main-content">
	<h2><?php echo $this->lang->line('cms_general_edit_menus'); ?></h2>

	<?php if (validation_errors()) : ?>
    <div class="alert alert-danger">
        <?php echo validation_errors('<p><small>', '</small></p>'); ?>
    </div>
    <?php endif; ?>

	<div class="panel panel-cms">
		<div class="panel-body">
			<p><?php echo $this->lang->line('cms_general_legend_edit_menus'); ?>
				<select name="list-menus" id="list-menus">
					<?php foreach($_navMenu as $nm) : ?>
					<?php $selected = ($nm->term_id == $this->uri->segment(4)) ? 'selected="selected"' : ''; ?>
					<option value="<?php echo $nm->term_id ?>" <?php echo $selected; ?>><?php echo $nm->name; ?></option>
					<?php endforeach; ?>
				</select>
				<button id="select-menu" data-href="admin/menus/index/" type="button" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_choose'); ?></button>
				o <a class="inverse" href="<?php echo base_url(); ?>admin/menus/addMenu"><?php echo $this->lang->line('cms_general_label_new_menu'); ?></a>
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="panel-group" id="accordion">
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
			  								'data-object'	=>		'page'
			  							);
			  						?>
			  						<?php echo form_checkbox($datos); ?>
			  						<?php echo $master->post_title; ?>
										</label>
									</div><!-- end checkbox -->

									<?php
										if (isset($_pages['childrens'])) {
											$ci =& get_instance();
											echo $ci->childrenPages($_pages['childrens'], $master->id);
										}
									?>

								</li>
								<?php 	endforeach;
									}
								?>
							</ul>
      					</div>
      					<div class="panel-footer">
							<button type="button" class="btn pull-right btn-default add-menu-item"><?php echo $this->lang->line('cms_general_label_add_menu'); ?></button>
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
	      								<input type="url" class="form-control" id="link-url" name="link-url" value="http://" required="required" />
	  								</div>
	  								<div class="form-group">
	    								<label for="link-name"><?php echo $this->lang->line('cms_general_label_link_text'); ?></label>
	      								<input type="text" class="form-control" id="link-name" name="link-name" required="required" />
	  								</div>
	  								<input type="hidden" name="type" value="<?php echo $this->lang->line('cms_general_label_custom_date_time_format') ?>">
	      					</div>
	      					<div class="panel-footer">
								<button id="add-menu-item-link" type="button" class="btn pull-right btn-default"><?php echo $this->lang->line('cms_general_label_add_menu'); ?></button>
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
			  								'data-object'	=>		'category'
			  							);
			  						?>
			  						<?php echo form_checkbox($datos); ?>
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
      					</div>
      					<div class="panel-footer">
							<button type="submit" class="btn pull-right btn-default add-menu-item"><?php echo $this->lang->line('cms_general_label_add_menu'); ?></button>
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
  						<em><?php echo $this->lang->line('cms_general_label_name_menu'); ?>: </em> <input type="text" name="name_menu" id="name_menu" value="<?php echo $_mainMenu->name; ?>" required />
						<button type="submit" class="btn pull-right btn-default"><?php echo $this->lang->line('cms_general_label_save_button'); ?></button>
  					</h4>
				</div><!-- end panel-heading -->

  				<div class="panel-body">
    				<h4><?php echo $this->lang->line('cms_general_label_structure_menu'); ?></h4>
    				<p><?php echo $this->lang->line('cms_general_label_help_menu'); ?></p>

    				<ul class="menu ui-sorteable" id="menu-to-edit">
					<?php if (isset($_postItems) && count($_postItems) > 0) : ?>

						<?php foreach ($_postItems as $postItem) : ?>

						<li id="menu-item-<?php echo $postItem['id']; ?>" class="menu-item menu-item-depth-0 menu-item-<?php echo $postItem['_menu_item_object']; ?> menu-item-edit-inactive">
  							<dl class="menu-item-bar">
  								<dt class="menu-item-handle">
  									<span class="item-title">
  										<span class="menu-item-title"><?php echo $postItem['post_title']; ?></span>
  										<span class="is-submenu">subelemento</span>
  									</span>
  									<span class="item-controls pull-right">
  										<?php
  											$menutype = '';
  											switch ($postItem['_menu_item_object']) {
  												case 'custom':
  													$menutype = $this->lang->line('cms_general_label_custom_date_time_format');
  													break;

  												case 'page':
  													$menutype = $this->lang->line('cms_general_title_page');
  													break;

  												case 'category':
  													$menutype = $this->lang->line('cms_general_title_category');
  													break;
  											}
  										?>
  										<span class="item-type"><?php echo $menutype; ?></span>
 										<span class="sh-settings glyphicon glyphicon-arrow-down" id="sh-settings-<?php echo $postItem['id']; ?>"></span>
  									</span>
  								</dt>
  							</dl>
  							<div class="menu-item-settings" id="menu-item-settings-<?php echo $postItem['id']; ?>">
							<?php if ($postItem['_menu_item_object'] == 'custom') : ?>
    							<div class="row">
    								<div class="col-md-12">
    									<div class="form-group">
    										<label for="edit-menu-item-url-<?php echo $postItem['id']; ?>"><em>URL</em></label>
    										<input type="text" class="form-control edit-menu-item-url" name="menu-item-url[<?php echo $postItem['id']; ?>]" id="edit-menu-item-url-<?php echo $postItem['id']; ?>" value="<?php echo $postItem['_menu_item_url']; ?>" />
    									</div><!-- end form-group -->
    								</div><!-- end col-md-6 -->
    							</div><!-- end row -->
  							<?php endif; ?>
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="edit-menu-item-<?php echo $postItem['id']; ?>"><em><?php echo $this->lang->line('cms_general_label_navigation'); ?></em></label>
  											<input type="text" class="form-control edit-menu-item-title" name="menu-item-title[<?php echo $postItem['id']; ?>]" id="edit-menu-item-<?php echo $postItem['id']; ?>" value="<?php echo $postItem['post_title']; ?>" />
  											<div class="checkbox">
  												<label>
													<?php $checked = ($postItem['_menu_item_target'] == '_blank') ? 'checked="checked"' : ''; ?>
  													<input type="checkbox" id="edit-menu-item-target-<?php echo $postItem['id']; ?>" name="menu-item-target[<?php echo $postItem['id']; ?>]" <?php echo $checked; ?> /> <?php echo $this->lang->line('cms_general_label_attr_target'); ?>
  												</label>
  											</div><!-- end checkbox -->
  										</div><!-- end form-group -->
  									</div><!-- end col-md-6 -->
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="edit-menu-item-attr-title-<?php echo $postItem['id']; ?>"><em><?php echo $this->lang->line('cms_general_label_attr_title'); ?></em></label>
  												<input type="text" class="form-control edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $postItem['id']; ?>]" id="edit-menu-item-attr-title-<?php echo $postItem['id']; ?>" value="<?php echo $postItem['post_excerpt']; ?>" />
  										</div><!-- end form-group -->
  									</div><!-- end col-md-6 -->
  								</div><!-- end row -->
  								<div class="row">
  									<div class="col-md-6">
  										<div class="form-group">
  											<label for="edit-menu-item-classes-<?php echo $postItem['id']; ?>"><em><?php echo $this->lang->line('cms_general_label_class_css'); ?></em></label>
  												<input type="text" class="form-control edit-menu-item-classes" name="menu-item-classes[<?php echo $postItem['id']; ?>]" id="edit-menu-item-classes-<?php echo $postItem['id']; ?>" value="<?php echo $postItem['_menu_item_classes']; ?>" />
  										</div><!-- end form-group -->
  									</div><!-- end col-md-6 -->
  								</div><!-- end row -->
  								<div class="menu-item-footer">
  									<p><a href="#" class="del-mnu-item text-danger" data-idmenu="<?php echo $_mainMenu->term_id; ?>" data-id="<?php echo $postItem['id']; ?>"><?php echo $this->lang->line('cms_general_label_del'); ?></a> | <a href="#" data-id="<?php echo $postItem['id']; ?>" class="cancel-mnu-item"><?php echo $this->lang->line('cms_general_label_cancel'); ?></a></p>
  								</div>

  								<input type="hidden" class="menu-item-data-db-id" name="menu-item-db-id[<?php echo $postItem['id']; ?>]" value="<?php echo $postItem['id']; ?>" />
  								<input type="hidden" class="menu-item-data-object-id" name="menu-item-object-id[<?php echo $postItem['id']; ?>]" value="<?php echo $postItem['_menu_item_object_id']; ?>" />
  								<input type="hidden" class="menu-item-data-object" name="menu-item-object[<?php echo $postItem['id']; ?>]" value="<?php echo $postItem['_menu_item_object']; ?>" />
  								<input type="hidden" class="menu-item-data-parent-id" name="menu-item-parent-id[<?php echo $postItem['id']; ?>]" value="<?php echo $postItem['_menu_item_menu_item_parent']; ?>" />
  								<input type="hidden" class="menu-item-data-position" name="menu-item-position[<?php echo $postItem['id']; ?>]" value="<?php echo $postItem['menu_order']; ?>" />
  								<input type="hidden" class="menu-item-data-type" name="menu-item-type[<?php echo $postItem['id']; ?>]" value="<?php echo $postItem['_menu_item_type']; ?>" />
  							</div><!-- end menu-item-settings-->
  						</li>
  					<?php endforeach; ?>
					<?php endif; ?>
    				</ul>

    				<input type="hidden" name="menu-id" value="<?php echo $_mainMenu->term_id; ?>" />

				</div><!-- end panel-body -->
				<div class="panel-footer">
					<a id="delMenu" data-idmenu="<?php echo $_mainMenu->term_id; ?>" class="text-danger" href="#"><?php echo $this->lang->line('cms_general_menu_delete'); ?></a>
					<button type="submit" class="btn pull-right btn-default"><?php echo $this->lang->line('cms_general_label_save_button'); ?></button>
				</div>
			</div><!-- end panel-cms -->
			<?php echo form_close(); ?>
		</div><!-- end col-md-8 -->
	</div><!-- end row -->
</section><!-- end main-content -->