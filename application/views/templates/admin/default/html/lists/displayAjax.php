	<div class="search-status">
		<div class="row">
			<div class="col-sm-8">
				<ul class="list-inline mnu-status">
					<li <?php echo ($_status == 'all') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax"><?php echo $this->lang->line('cms_general_label_all'); ?> (<?php echo $_num_total; ?>)</a></small></li>

					<?php if ($_active > 0) : ?>
					<li <?php echo ($_status == '1') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/1"><?php echo $this->lang->line('cms_general_label_active'); ?> (<?php echo $_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_no_active > 0) : ?>
					<li <?php echo ($_status == '0') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/0"><?php echo $this->lang->line('cms_general_label_no_active'); ?> (<?php echo $_no_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_trush > 0) : ?>
					<li <?php echo ($_status == '2') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/2"><?php echo $this->lang->line('cms_general_label_trush'); ?> (<?php echo $_trush; ?>)</a></small></li>
					<?php endif; ?>
				</ul>
			</div><!-- end col-sm-8 -->

			<div class="col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" />
	  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/lists/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</div>
		</div>
	</div><!-- end search-status -->

	<div class="filters-goto">
		<div class="row">
			<div class="col-sm-8">
				Filtros
			</div><!-- end col-sm-8 -->
			<div class="col-sm-4">
			<?php if (isset($_pagination) && strlen($_pagination)) : ?>
				<div class="container-pagination text-right">
					<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
					<small>
						<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
						<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
						<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/lists/displayAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
					</small>
				</div>
			<?php endif; ?>
			</div><!-- end col-sm-4 -->
		</div><!-- end row -->
	</div><!-- end filters-goto -->

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th class="th-small"><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/<?php echo $_status; ?>/id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>">Id <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/<?php echo $_status; ?>/name/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_name'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/<?php echo $_status; ?>/description/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_description'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/<?php echo $_status; ?>/access_level/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_access_level'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><?php echo $this->lang->line('cms_general_title_subscribers'); ?></th>
				<th><?php echo $this->lang->line('cms_general_label_status'); ?></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/lists/displayAjax/<?php echo $_status; ?>/created_at/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_date_created'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_lists) && sizeof($_lists) > 0) : ?>
			<?php foreach ($_lists as $item) : ?>
			<tr>
				<td class="text-center"><?php echo $item->id; ?></td>
				<td class="view-option-post">
				<?php if ($this->user->has_permission('edit_any_lists')) : ?>
					<a href="<?php echo base_url(); ?>admin/lists/edit/<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
				<?php else : ?>
					<?php echo $item->name; ?>
				<?php endif; ?>

				<?php if ($this->user->has_permission('edit_any_lists') || $this->user->has_permission('del_any_lists')) : ?>
					<div class="opt-post">
						<ul class="list-inline">
						<?php if ($item->status == 2) : ?>
							<?php if ($this->user->has_permission('edit_any_lists')) : ?>
							<li><small><a class="ico-action" href="#" data-status="0" data-id="<?php echo $item->id; ?>" data-return="admin/lists/displayAjax"><?php echo $this->lang->line('cms_general_label_restore'); ?></a></small></li>
							<?php endif; ?>

							<?php if ($this->user->has_permission('del_any_lists')) : ?>
							<li><small><a class="text-danger ico-del" href="#" data-id="<?php echo $item->id; ?>"><?php echo $this->lang->line('cms_general_label_delete_permanent'); ?></a></small></li>
							<?php endif; ?>
						<?php else : ?>
							<?php if ($this->user->has_permission('edit_any_lists')) : ?>
							<li><small><a href="<?php echo base_url(); ?>admin/lists/edit/<?php echo $item->id; ?>"><?php echo $this->lang->line('cms_general_label_edit'); ?></a></small></li>
							<?php endif; ?>

							<?php if ($this->user->has_permission('del_any_lists')) : ?>
							<li><small><a class="text-danger ico-action" href="#" data-status="2" data-id="<?php echo $item->id; ?>" data-return="admin/lists/displayAjax"><?php echo $this->lang->line('cms_general_label_trush'); ?></a></small></li>
							<?php endif; ?>
						<?php endif; ?>
						</ul>
					</div>
				<?php endif; ?>
				</td>
				<td><?php echo $item->description; ?></td>
				<td class="text-center"><?php echo $item->access_level; ?></td>
				<td class="text-center">
					<?php
						$CI =& get_instance();
						$this->load->model('Subscribers_model');
						$count = $this->Subscribers_model->get('subscriber_lists', 'count(subscriber_id) as total', array('list_id' => $item->id));
					?>
					<?php echo $count[0]->total; ?>
					<i class='fa fa-users'></i>
				</td>
				<td class="text-center">
				<?php if ($this->user->has_permission('edit_any_lists')) : ?>
					<a href="#" data-status="<?php echo $item->status; ?>" data-id="<?php echo $item->id; ?>" class="ico-status"><?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></a>
				<?php else : ?>
					<?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?>
				<?php endif; ?>
				</td>
				<td class="text-center"><?php echo date($this->config->item('cms_date_format'), strtotime($item->created_at)); ?></td>
			</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-danger" colspan="7"><?php echo $this->lang->line('cms_general_label_no_found'); ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<?php if (isset($_pagination) && strlen($_pagination)) : ?>
	<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
	<?php endif; ?>