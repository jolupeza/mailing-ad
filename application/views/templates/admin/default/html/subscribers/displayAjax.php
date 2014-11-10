	<div class="search-status">
		<div class="row">
			<div class="col-sm-8">
				<ul class="list-inline mnu-status">
					<li <?php echo ($_status == 'all') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax"><?php echo $this->lang->line('cms_general_label_all'); ?> (<?php echo $_num_total; ?>)</a></small></li>

					<?php if ($_active > 0) : ?>
					<li <?php echo ($_status == '1') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/1"><?php echo $this->lang->line('cms_general_label_active'); ?> (<?php echo $_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_no_active > 0) : ?>
					<li <?php echo ($_status == '0') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/0"><?php echo $this->lang->line('cms_general_label_no_active'); ?> (<?php echo $_no_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_trush > 0) : ?>
					<li <?php echo ($_status == '2') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/2"><?php echo $this->lang->line('cms_general_label_trush'); ?> (<?php echo $_trush; ?>)</a></small></li>
					<?php endif; ?>
				</ul>
			</div><!-- end col-sm-8 -->

			<div class="col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" />
	  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</div>
		</div>
	</div><!-- end search-status -->

	<div class="filters-goto">
		<div class="row">
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-6">
						<?php
							if (isset($_lists) && count($_lists) > 0) {
								$options = array('0' => '- Seleccione lista de correo -');

								foreach ($_lists as $list) {
									$options[$list->id] = $list->name;
								}
							}
							$href = 'data-href="' . base_url() . 'admin/subscribers/displayAjax/'. $_status . '/' . $_sort_by . '/' . $_sort_order . '/"';
							$data_search = 'data-search="' . $_search . '"';

							$select = ($_list == 'all') ? 0 : $_list;

							echo form_dropdown('lists', $options, $select, 'id="select-list" class="form-control"' . $href . $data_search);
						?>
					</div>
					<div class="col-sm-6"></div>
				</div>
			</div><!-- end col-sm-8 -->
			<div class="col-sm-4">
			<?php if (isset($_pagination) && strlen($_pagination)) : ?>
				<div class="container-pagination text-right">
					<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
					<small>
						<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
						<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
						<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_list; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
					</small>
				</div>
			<?php endif; ?>
			</div><!-- end col-sm-4 -->
		</div><!-- end row -->
	</div><!-- end filters-goto -->

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th class="th-small"><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_search; ?>">Id <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/name/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_name'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/email/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_email'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/company/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_company'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><?php echo $this->lang->line('cms_general_label_status'); ?></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/subscribers/displayAjax/<?php echo $_status; ?>/date_birth/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_list; ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_date_birth'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_subs) && sizeof($_subs) > 0) : ?>
			<?php foreach ($_subs as $item) : ?>
			<tr>
				<td class="text-center"><?php echo $item->id; ?></td>
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

	<?php if (isset($_pagination) && strlen($_pagination)) : ?>
	<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
	<?php endif; ?>