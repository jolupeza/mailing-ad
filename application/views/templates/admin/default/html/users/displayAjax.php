	<div class="search-status">
		<div class="row">
			<div class="col-sm-8">
				<ul class="list-inline mnu-status">
					<li <?php echo ($_status == 'all') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/users/displayAjax/"><?php echo $this->lang->line('cms_general_label_all'); ?> (<?php echo $_num_total; ?>)</a></small></li>

					<?php if ($_active > 0) : ?>
					<li <?php echo ($_status == '1') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/users/displayAjax/<?php echo $_role; ?>/1"><?php echo $this->lang->line('cms_general_label_active'); ?> (<?php echo $_active; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_no_active > 0) : ?>
					<li <?php echo ($_status == '0') ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/users/displayAjax/<?php echo $_role; ?>/0"><?php echo $this->lang->line('cms_general_label_no_active'); ?> (<?php echo $_no_active; ?>)</a></small></li>
					<?php endif; ?>
				</ul>
			</div><!-- end col-sm-8 -->

			<div class="col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" />
	  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/users/displayAjax/<?php echo $_role; ?>/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</div>
		</div>
	</div><!-- end search-status -->

	<div class="filters-goto">
		<div class="row">
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-6">
						<!-- Filtro por roles -->
						<select class="form-control filter-role">
						  	<option value="0"><?php echo $this->lang->line('cms_general_label_select_role'); ?></option>
						  	<?php if ($_roles && count($_roles)) : ?>
						  		<?php foreach ($_roles as $role) : ?>
						  			<?php $selected = ($role->id == $_role) ? 'selected="selected"' : ''; ?>
						  		<option value="<?php echo $role->id; ?>" <?php echo $selected; ?>><?php echo $role->role; ?></option>
						  		<?php endforeach; ?>
						  	<?php endif; ?>
						</select>
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
						<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/users/displayAjax/<?php echo $_role; ?>/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
					</small>
				</div>
				<?php endif; ?>
			</div><!-- end col-sm-4 -->
		</div><!-- end row -->
	</div><!-- end filters-goto -->

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th class="th-small"><a class="link-ajax" href="<?php echo base_url(); ?>admin/users/displayAjax/<?php echo $_role; ?>/<?php echo $_status; ?>/id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>">Id <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/users/displayAjax/<?php echo $_role; ?>/<?php echo $_status; ?>/user/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_user'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/users/displayAjax/<?php echo $_role; ?>/<?php echo $_status; ?>/name/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_name'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><?php echo $this->lang->line('cms_general_label_email'); ?></th>
				<th><?php echo $this->lang->line('cms_general_label_status'); ?></th>
				<th><?php echo $this->lang->line('cms_general_label_role'); ?></th>
				<th><?php echo $this->lang->line('cms_general_label_last_access'); ?></th>
				<th><?php echo $this->lang->line('cms_general_label_actions'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_users) && sizeof($_users) > 0) : ?>
			<?php foreach ($_users as $item) : ?>
			<tr>
				<td class="text-center"><?php echo $item->id; ?></td>
				<td><?php echo $item->user; ?></td>
				<td><?php echo $item->name; ?></td>
				<td><?php echo $item->email; ?></td>
				<td class="text-center"><a href="javascript:void(0);" data-status="<?php echo $item->status; ?>" data-id="<?php echo $item->id; ?>" class="ico-status"><?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></a></td>
				<td class="text-center"><?php echo $item->role; ?></td>
				<td class="text-center">
				<?php
					$last_login = $item->last_login;
					if ($last_login != '0000-00-00 00:00:00') {
						$seconds = strtotime('now') - strtotime($last_login);
						echo elapsed_time($seconds);
					} else {
						echo '';
					}
				?>
				</td>
				<td class="text-center">
					<a href="<?php echo base_url(); ?>admin/users/edit/<?php echo $item->id; ?>"><i class="fa fa-edit fa-lg"></i></a>
					<a class="ico-del text-danger" href="javascript:void(0);" title="<?php echo $this->lang->line('cms_general_label_del'); ?>" data-id="<?php echo $item->id; ?>"><i class="fa fa-times fa-lg"></i></a>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-danger" colspan="8"><?php echo $this->lang->line('cms_general_label_no_found'); ?></td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<?php if (isset($_pagination) && strlen($_pagination)) : ?>
	<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
	<?php endif; ?>