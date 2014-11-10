<div class="row">

	<div class="col-sm-12">

		<div class="panel">

			<div class="panel-heading">
				<h2><?php echo $this->lang->line('cms_general_label_roles'); ?> <a href="<?php echo base_url(); ?>admin/permissions/add" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_add'); ?> <i class="fa fa-plus"></i></a></h2>
			</div><!-- end panel-heading -->

			<div class="panel-body">
				<div class="content">

						<div class="search-status">
							<div class="row">
								<div class="col-sm-8">

								</div><!-- end col-sm-8 -->

								<div class="col-sm-4">
									<div class="input-group">
						  				<input type="text" class="form-control text-search" />
						  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/permissions/index/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
									</div>
								</div>
							</div>
						</div><!-- end search-status -->

						<div class="filters-goto">
							<div class="row">
								<div class="col-sm-8">
								</div><!-- end col-sm-8 -->
								<div class="col-sm-4">
								<?php if (isset($_pagination) && strlen($_pagination)) : ?>
									<div class="container-pagination text-right">
										<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
										<small>
											<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
											<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
											<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/permissions/index/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
										</small>
									</div>
								<?php endif; ?>
								</div><!-- end col-sm-4 -->
							</div><!-- end row -->
						</div><!-- end filters-goto -->

						<table class="table table-hover table-striped">
							<thead>
								<tr>
									<th class="th-small"><a href="<?php echo base_url(); ?>admin/permissions/index/id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>">Id <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
									<th><a href="<?php echo base_url(); ?>admin/permissions/index/role/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_name'); ?> <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
									<th><?php echo $this->lang->line('cms_general_label_status'); ?></th>
									<th><?php echo $this->lang->line('cms_general_label_actions'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php if (isset($_roles) && sizeof($_roles) > 0) : ?>
								<?php foreach ($_roles as $item) : ?>
								<tr>
									<td class="text-center"><?php echo $item->id; ?></td>
									<td><a href="<?php echo base_url(); ?>admin/permissions/edit/<?php echo $item->id; ?>"><?php echo $item->role; ?></a></td>
									<td class="text-center">
										<a href="javascript:void(0);" data-status="<?php echo $item->status; ?>" data-id="<?php echo $item->id; ?>" class="ico-status"><?php echo ($item->status == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?>
										</a></td>
									<td class="text-center">
										<a href="<?php echo base_url(); ?>admin/permissions/edit/<?php echo $item->id; ?>" title="<?php echo $this->lang->line('cms_general_label_edit_permission'); ?>"><i class="fa fa-edit fa-lg"></i></a>
										<a href="<?php echo base_url(); ?>admin/permissions/permsRole/<?php echo $item->id; ?>" title="<?php echo $this->lang->line('cms_general_label_view_permissions'); ?>"><i class="fa fa-lock fa-lg"></i></a>
										<a class="ico-del text-danger" href="javascript:void(0);" title="<?php echo $this->lang->line('cms_general_label_del'); ?>" data-id="<?php echo $item->id; ?>"><i class="fa fa-times fa-lg"></i></a>
									</td>
								</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td class="text-danger" colspan="4"><?php echo $this->lang->line('cms_general_label_no_found'); ?></td>
								</tr>
							<?php endif; ?>
							</tbody>
						</table>

						<?php if (isset($_pagination) && strlen($_pagination)) : ?>
						<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
						<?php endif; ?>

				</div><!-- end content -->
			</div><!-- end panel-body -->
		</div><!-- end panel -->

	</div><!-- end col-sm-12 -->
</div><!-- end row -->