	<div class="search-status">
		<div class="row">
			<div class="col-sm-offset-8 col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" />
	  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/templates/displayAjax/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</div>
		</div>
	</div><!-- end search-status -->

	<div class="filters-goto">
		<div class="row">
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-6">
					</div>
					<div class="col-sm-6"></div>
				</div>
			</div><!-- end col-sm-8 -->
			<div class="col-sm-4">
			<?php if (isset($_templates) && sizeof($_templates) > 0) : ?>
				<?php if (isset($_pagination) && strlen($_pagination)) : ?>
				<div class="container-pagination text-right">
					<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
					<small>
						<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
						<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
						<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/templates/displayAjax/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
					</small>
				</div>
				<?php endif; ?>
			<?php endif; ?>
			</div><!-- end col-sm-4 -->
		</div><!-- end row -->
	</div><!-- end filters-goto -->

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/templates/displayAjax/id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>">Id <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/templates/displayAjax/name/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_title_templates'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/templates/displayAjax/description/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_description'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/templates/displayAjax/status/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_status'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><?php echo $this->lang->line('cms_general_label_actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_templates) && sizeof($_templates) > 0) : ?>
			<?php foreach ($_templates as $item) : ?>
			<tr>
				<td class="text-center"><?php echo $item->id; ?></td>
				<td>
				<?php if ($this->user->has_permission('edit_any_tmp_email')) : ?>
					<a href="<?php echo base_url(); ?>admin/templates/edit/<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
				<?php else : ?>
					<?php echo $item->name; ?>
				<?php endif; ?>
				</td>
				<td><?php echo $item->description; ?></td>
				<td class="text-center">
				<?php if ($this->user->has_permission('edit_any_tmp_email')) : ?>
					<a href="#" data-status="<?php echo $item->status; ?>" data-id="<?php echo $item->id; ?>" class="ico-status"><?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></a>
				<?php else : ?>
					<?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?>
				<?php endif; ?>
				</td>
				<td class="text-center">
				<?php if ($this->user->has_permission('edit_any_tmp_email')) : ?>
					<a href="<?php echo base_url(); ?>admin/templates/edit/<?php echo $item->id; ?>" title="Editar"><i class="fa fa-edit fa-lg"></i></a>
				<?php else : ?>
					<i class="fa fa-edit fa-lg"></i>
				<?php endif; ?>

				<?php if ($this->user->has_permission('del_any_tmp_email')) : ?>
					<a class="delete" href="<?php echo base_url(); ?>admin/templates/delete/<?php echo $item->id; ?>" title="Eliminar" data-id="<?php echo $item->id; ?>"><i class="fa fa-times text-danger fa-lg"></i></a>
				<?php else : ?>
					<i class="fa fa-times text-danger fa-lg"></i>
				<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-danger" colspan="5"><?php echo $this->lang->line('cms_general_label_no_found'); ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<?php if (isset($_pagination) && strlen($_pagination)) : ?>
	<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
	<?php endif; ?>