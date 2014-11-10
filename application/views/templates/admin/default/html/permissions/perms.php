<div class="row">

	<div class="col-sm-12">

		<div class="panel">

			<div class="panel-heading">
				<h2><?php echo $this->lang->line('cms_general_label_permissions'); ?></h2>
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
						  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/permissions/perms/"><span class="glyphicon glyphicon-search"></span></button></span>
									</div>
								</div>
							</div>
						</div><!-- end search-status -->

						<div class="filters-goto">
							<div class="row">
								<div class="col-sm-8">
									<button class="btn btn-cms" data-toggle="modal" data-target="#modalAddPerm"><?php echo $this->lang->line('cms_general_label_add'); ?> <i class="fa fa-plus"></i></button>
								</div><!-- end col-sm-8 -->

								<div class="col-sm-4">
								<?php if (isset($_pagination) && strlen($_pagination)) : ?>
									<div class="container-pagination text-right">
										<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
										<small>
											<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
											<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
											<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/permissions/perms/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
										</small>
									</div>
								<?php endif; ?>
								</div><!-- end col-sm-4 -->
							</div><!-- end row -->
						</div><!-- end filters-goto -->

					<?php if (isset($_permissions) && sizeof($_permissions) > 0) : ?>
						<form id="frmPerms" action="<?php echo base_url(); ?>permissions/delPerms" method="POST" role="form">
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th class="th-small">Id</th>
										<th><?php echo $this->lang->line('cms_general_label_permission'); ?></th>
										<th><?php echo $this->lang->line('cms_general_label_key'); ?></th>
										<th><?php echo $this->lang->line('cms_general_label_actions'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($_permissions as $item) : ?>
									<tr id="perm-<?php echo $item->id; ?>">
										<td class="text-center"><input type="checkbox" name="idPermiso[]" value="<?php echo $item->id; ?>" /></td>
										<td><?php echo $item->title; ?></td>
										<td><?php echo $item->name; ?></td>
										<td class="text-center">
											<a href="#" class="edit-perm" data-id="<?php echo $item->id; ?>" data-toggle="modal" data-target="#modalEditPerm"><i class="fa fa-edit fa-lg"></i></a>
											<a class="del-perm text-danger" href="javascript:void(0);" title="<?php echo $this->lang->line('cms_general_label_del'); ?>" data-id="<?php echo $item->id; ?>"><i class="fa fa-times fa-lg"></i></a>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</form>
					<?php endif; ?>

						<?php if (isset($_pagination) && strlen($_pagination)) : ?>
						<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
						<?php endif; ?>

				</div><!-- end content -->
			</div><!-- end panel-body -->
		</div><!-- end panel -->

	</div><!-- end col-sm-12 -->
</div><!-- end row -->

<!-- Modal -->
<div class="modal fade" id="modalAddPerm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
    		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

			<form action="" id="addPerm" class="form-horizontal" role="form">

      			<div class="modal-header">
        			<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('cms_general_label_add_permission'); ?></h4>
      			</div>
      			<div class="modal-body">

					<div class="form-group">
						<label for="title" class="col-sm-2 control-label"><?php echo $this->lang->line('cms_general_label_permission') ?></label>
						<div class="col-sm-10">
  							<input type="text" class="form-control" id="title" name="title" required />
						</div>
					</div>

					<div class="form-group">
						<label for="name" class="col-sm-2 control-label"><?php echo $this->lang->line('cms_general_label_key') ?></label>
						<div class="col-sm-10">
  							<input type="text" class="form-control" id="name" name="name" required />
						</div>
					</div>

      			</div><!-- end modal-body -->
      			<div class="modal-footer">
        			<button id="add-perm" type="button" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_add'); ?></button>
      			</div><!-- end modal-footerr -->

  			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalEditPerm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
    		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

			<form action="" id="editPerm" class="form-horizontal" role="form">

      			<div class="modal-header">
        			<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('cms_general_label_edit_permission'); ?></h4>
      			</div>
      			<div class="modal-body">

					<div class="form-group">
						<label for="titleEdit" class="col-sm-2 control-label"><?php echo $this->lang->line('cms_general_label_permission') ?></label>
						<div class="col-sm-10">
  							<input type="text" class="form-control" id="titleEdit" name="titleEdit" required />
						</div>
					</div>

					<div class="form-group">
						<label for="nameEdit" class="col-sm-2 control-label"><?php echo $this->lang->line('cms_general_label_key') ?></label>
						<div class="col-sm-10">
  							<input type="text" class="form-control" id="nameEdit" name="nameEdit" required />
						</div>
					</div>

      			</div><!-- end modal-body -->
      			<div class="modal-footer">
      				<input type="hidden" name="perm_id" id="perm_id" />
        			<button id="edit-perm" type="button" class="btn btn-cms"><?php echo $this->lang->line('cms_general_label_save'); ?></button>
      			</div><!-- end modal-footerr -->

  			</form>
		</div>
	</div>
</div>