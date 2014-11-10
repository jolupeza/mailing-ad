<div class="row">

	<div class="col-sm-12">

		<div class="panel">

			<div class="panel-heading">
				<h2><?php echo $this->lang->line('cms_general_label_permissions'); ?>: <?php echo $_role->role; ?></h2>
			</div><!-- end panel-heading -->

			<div class="panel-body">
				<div class="content">
					<?php echo form_open('', array('id' => 'form_perms_role', 'class' => 'form-horizontal', 'role' => 'form'), array('token' => $_token)); ?>
					<?php if (isset($_permissions) && sizeof($_permissions) > 0) : ?>
						<table class="table table-hover table-striped">
							<thead>
								<tr>
									<th class="th-small">Id</th>
									<th><?php echo $this->lang->line('cms_general_label_permission'); ?></th>
									<th>Habilitado</th>
									<th>Denegado</th>
									<th>Ignorar</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($_permissions as $item) : ?>
								<tr>
									<td class="text-center"><input type="checkbox" name="permId[]" value="<?php echo $item['id']; ?>" /></td>
									<td><?php echo $item['nombre']; ?></td>
									<td class="text-center"><input type="radio" name="perm_<?php echo $item['id']; ?>" value="1" <?php if ($item['valor'] == 1) : ?>checked="checked"<?php endif; ?> /></td>
		                            <td class="text-center"><input type="radio" name="perm_<?php echo $item['id']; ?>" value="" <?php if ($item['valor'] == "") : ?>checked="checked"<?php endif; ?> /></td>
		                            <td class="text-center"><input type="radio" name="perm_<?php echo $item['id']; ?>" value="x" <?php if ($item['valor'] === "x") : ?>checked="checked"<?php endif; ?> /></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>

						<?php echo form_button(array('class' => 'btn btn-cms', 'type' => 'submit', 'value' => $this->lang->line('cms_general_label_save'), 'content' => $this->lang->line('cms_general_label_save') . '<i class="fa fa-save"></i>')); ?>

					<?php endif; ?>
					<?php echo form_close(); ?>

					<?php if (isset($_pagination) && strlen($_pagination)) : ?>
					<div class="pull-right container-pagination"><span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span><?php echo $_pagination; ?></div>
					<?php endif; ?>

				</div><!-- end content -->
			</div><!-- end panel-body -->
		</div><!-- end panel -->

	</div><!-- end col-sm-12 -->
</div><!-- end row -->