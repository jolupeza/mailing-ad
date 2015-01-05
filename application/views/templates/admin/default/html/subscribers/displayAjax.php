	<div class="search-status">
		<div class="row">
			<div class="col-sm-8">
				<ul class="list-inline mnu-status">
					<li <?php echo ($_status == 'all') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/all/<?php echo $_sort_by;  ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/"><?php echo $this->lang->line('cms_general_label_all'); ?> (<?php echo $_num_total; ?>)</a></small></li>

					<?php if ($_active > 0) : ?>
					<li <?php echo ($_status == '1') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/1/<?php echo $_sort_by;  ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/"><?php echo $this->lang->line('cms_general_label_active'); ?> (<?php echo $_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_no_active > 0) : ?>
					<li <?php echo ($_status == '0') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/0/<?php echo $_sort_by;  ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/"><?php echo $this->lang->line('cms_general_label_no_active'); ?> (<?php echo $_no_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_trush > 0) : ?>
					<li <?php echo ($_status == '2') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/2/<?php echo $_sort_by;  ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/"><?php echo $this->lang->line('cms_general_label_trush'); ?> (<?php echo $_trush; ?>)</a></small></li>
					<?php endif; ?>
				</ul>

				<?php if ($_search != 'all') : ?><p><strong>Búsqueda: </strong><?php echo urldecode($_search); ?> </p><?php endif; ?>
			</div><!-- end col-sm-8 -->

			<div class="col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" />
	  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</div>
		</div>
	</div><!-- end search-status -->

	<div class="filters-goto">
		<div class="row">
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-4">
					<?php if ($_status == 2) : ?>
						<button type="button" class="btn btn-danger" id="btn-delete-all" data-href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>/<?php echo $_offset; ?>/"><?php echo $this->lang->line('cms_general_label_del'); ?></button>
					<?php endif; ?>
						<button type="button" class="btn btn-primary" id="btn-edit-all" data-href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>/<?php echo $_offset; ?>/"><?php echo $this->lang->line('cms_general_label_change_status'); ?></button>
					</div>
					<div class="col-sm-4">
						<?php
							$options = array('0' => '- Seleccione lista de correo -');
							if (isset($_lists) && count($_lists) > 0) {

								foreach ($_lists as $list) {
									$options[$list->id] = $list->name;
								}
							}
							$href = 'data-href="' . base_url() . 'admin/subscribers/displayAjax/'. $_status . '/' . $_sort_by . '/' . $_sort_order . '/"';
							$data_search = 'data-search="' . $_search . '" data-limit="' . $_limit . '"';

							$select = ($_list == 'all') ? 0 : $_list;

							echo form_dropdown('lists', $options, $select, 'id="select-list" class="form-control"' . $href . $data_search);
						?>
					</div>
					<div class="col-sm-4">
						<small><?php echo $this->lang->line('cms_general_num_rows'); ?></small>
						<select name="num-reg" id="num-reg" data-href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>/">
						<?php
							for ($i=5; $i <= 35; $i+=5) {
								$selected = ($_limit == $i) ? 'selected="selected"' : '';
								$selected = ($_limit == 0 && $i == 35) ? 'selected="selected"' : $selected;
						?>
							<option value="<?php echo ($i == 35) ? 'all' : $i; ?>" <?php echo $selected; ?>><?php echo ($i == 35) ? 'Todos' : $i; ?></option>
						<?php
							}
						?>
						</select>
					</div>
				</div>
			</div><!-- end col-sm-8 -->
			<div class="col-sm-4">
			<?php if (isset($_subs) && sizeof($_subs) > 0) : ?>
				<div class="container-pagination text-right">
					<?php if (isset($_pagination) && strlen($_pagination)) : ?>
						<div class="container-pagination text-right">
							<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
							<small>
								<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
								<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
								<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
							</small>
						</div>
					<?php else : ?>
						<span><?php echo $_total; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
					<?php endif; ?>
				</div><!-- end container-pagination -->
			<?php endif; ?>
			</div><!-- end col-sm-4 -->
		</div><!-- end row -->
	</div><!-- end filters-goto -->

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th><input type="checkbox" id="subs_all" /></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/name/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_name'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/email/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_email'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/company/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_company'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><?php echo $this->lang->line('cms_general_label_status'); ?></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/date_birth/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_limit; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_date_birth'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_subs) && sizeof($_subs) > 0) : ?>
			<?php foreach ($_subs as $item) : ?>
			<tr>
				<td class="text-center td-xsmall">
					<input type="checkbox" name="subs_id[]" class="chk_subs" value="<?php echo $item->id; ?>" data-status="<?php echo $item->status; ?>" />
				</td>
				<td class="view-option-post">
				<?php if ($this->user->has_permission('edit_any_subs')) : ?>
					<a href="<?php echo base_url(); ?>admin/subscribers/edit/<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
				<?php else : ?>
					<?php echo $item->name; ?>
				<?php endif; ?>

				<?php if ($this->user->has_permission('edit_any_subs') || $this->user->has_permission('del_any_subs')) : ?>
					<div class="opt-post">
						<ul class="list-inline">
					<?php if ($item->status == 2) : ?>
						<?php if ($this->user->has_permission('edit_any_subs')) : ?>
							<li><small><a class="ico-action" href="#" data-status="0" data-id="<?php echo $item->id; ?>" data-return="admin/subscribers/displayAjax"><?php echo $this->lang->line('cms_general_label_restore'); ?></a></small></li>
						<?php endif; ?>
						<?php if ($this->user->has_permission('del_any_subs')) : ?>
							<li><small><a class="text-danger ico-del" href="#" data-id="<?php echo $item->id; ?>"><?php echo $this->lang->line('cms_general_label_delete_permanent'); ?></a></small></li>
						<?php endif; ?>
					<?php else : ?>
						<?php if ($this->user->has_permission('edit_any_subs')) : ?>
							<li><small><a href="<?php echo base_url(); ?>admin/subscribers/edit/<?php echo $item->id; ?>"><?php echo $this->lang->line('cms_general_label_edit'); ?></a></small></li>
						<?php endif; ?>
						<?php if ($this->user->has_permission('del_any_subs')) : ?>
							<li><small><a class="text-danger ico-action" href="#" data-status="2" data-id="<?php echo $item->id; ?>" data-return="admin/subscribers/displayAjax/2"><?php echo $this->lang->line('cms_general_label_trush'); ?></a></small></li>
						<?php endif; ?>
					<?php endif; ?>
						</ul>
					</div><!-- end opt-post -->
				<?php endif; ?>
				</td>
				<td><?php echo $item->email; ?></td>
				<td><?php echo $item->company; ?></td>
				<td class="text-center">
				<?php if ($this->user->has_permission('edit_any_subs')) : ?>
					<a href="#" data-status="<?php echo $item->status; ?>" data-id="<?php echo $item->id; ?>" class="ico-status"><?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></a>
				<?php else : ?>
					<?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?>
				<?php endif; ?>
				</td>
				<td class="text-center"><?php echo $item->date_birth; ?></td>
			</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-danger" colspan="6"><?php echo $this->lang->line('cms_general_label_no_found'); ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<div class="pull-right container-pagination">
	<?php if (isset($_pagination) && strlen($_pagination)) : ?>
		<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?>
	<?php else : ?>
		<?php if (isset($_subs) && sizeof($_subs) > 0) : ?>
			<span><?php echo $_total; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
		<?php endif; ?>
	<?php endif; ?>
	</div><!-- end container-pagination -->