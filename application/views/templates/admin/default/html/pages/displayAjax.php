	<div class="search-status">
		<div class="row">
			<div class="col-sm-8">
			<?php if ($_countTotal > 0) : ?>
				<ul class="list-inline mnu-status">
					<li <?php echo ($_status == 0) ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/pages/displayPageAjax"><?php echo $this->lang->line('cms_general_label_all'); ?> (<?php echo $_countTotal; ?>)</a></small></li>

					<?php if ($_countPublish > 0) : ?>
					<li <?php echo ($_status == 1) ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/pages/displayPageAjax/1"><?php echo $this->lang->line('cms_general_label_published'); ?> (<?php echo $_countPublish; ?>)</a></small></li>
					<?php endif; ?>

					<?php if ($_countTrush > 0) : ?>
					<li <?php echo ($_status == 2) ? 'class="active"' : ''; ?>><small><a class="link-ajax" href="<?php echo base_url(); ?>admin/pages/displayPageAjax/2"><?php echo $this->lang->line('cms_general_label_trush'); ?> (<?php echo $_countTrush; ?>)</a></small></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			</div><!-- end col-sm-8 -->

			<div class="col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" />
	  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/pages/displayPageAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
				</div>
			</div>
		</div>
	</div><!-- end search-status -->

	<div class="filters-goto">
		<div class="row">
			<div class="col-sm-8">
				Filtros buscador
			</div><!-- end col-sm-8 -->
			<div class="col-sm-4">
			<?php if (isset($_masters) && sizeof($_masters) > 0) : ?>
				<?php if (isset($_pagination) && strlen($_pagination)) : ?>
				<div class="container-pagination text-right">
					<span><?php echo $this->pagination->total_rows; ?> <?php echo $this->lang->line('cms_general_label_items'); ?></span>
					<small>
						<input type="text" class="goto" value="<?php echo $this->pagination->cur_page; ?>" />
						<?php echo ' ' . $this->lang->line('cms_general_label_of') . ' ' . ceil($this->pagination->total_rows / $this->pagination->per_page); ?>
						<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/pages/displayPageAjax/<?php echo $_status; ?>/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
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
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/pages/displayPageAjax/<?php echo $_status; ?>/id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>">Id <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/pages/displayPageAjax/<?php echo $_status; ?>/post_title/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_title'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><?php echo $this->lang->line('cms_general_label_author'); ?></th>
				<th><span class="glyphicon glyphicon-comment"></span></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/pages/displayPageAjax/<?php echo $_status; ?>/published_at/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_date'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_masters) && sizeof($_masters) > 0) : ?>
			<?php foreach ($_masters as $post) : ?>
			<tr>
				<td class="text-center"><?php echo $post->id; ?></td>
				<td class="view-option-post">
					<?php if ($_status == '2') : ?>
						<?php echo $post->post_title; ?>
					<?php  else : ?>
						<a href="<?php echo base_url(); ?>admin/pages/edit/<?php echo $post->id; ?>"><?php echo $post->post_title; ?></a>
					<?php endif; ?>
					<div class="opt-post">
						<ul class="list-inline">
							<?php if ($post->post_status == 2) : ?>
							<li><small><a href="<?php echo base_url(); ?>admin/pages/action/<?php echo $post->id; ?>"><?php echo $this->lang->line('cms_general_label_restore'); ?></a></small></li>
							<li><small><a class="text-danger" href="<?php echo base_url(); ?>admin/pages/delete/<?php echo $post->id; ?>"><?php echo $this->lang->line('cms_general_label_delete_permanent'); ?></a></small></li>
							<?php else : ?>
							<li><small><a href="<?php echo base_url(); ?>admin/pages/edit/<?php echo $post->id; ?>"><?php echo $this->lang->line('cms_general_label_edit'); ?></a></small></li>
								<?php
									$CI =& get_instance();
									$CI->load->model('Posts_Model');
									if (!$CI->Posts_Model->verifyPagesChildren($post->id)) :
								?>
							<li><small><a class="text-danger" href="<?php echo base_url(); ?>admin/pages/action/<?php echo $post->id; ?>/2"><?php echo $this->lang->line('cms_general_label_trush'); ?></a></small></li>
								<?php endif; ?>
							<li><small><a href="#">Ver</a></small></li>
							<?php endif; ?>
						</ul>
					</div>
				</td>
				<td><?php echo $post->user; ?></td>
				<td class="text-center"><span class="glyphicon glyphicon-comment"></span></td>
				<td class="text-center">
					<span><?php echo date($this->config->item('cms_date_format'), strtotime($post->published_at)); ?></span>
					<span>
					<?php
						if ($post->post_status == 1) {
							$post_status = $this->lang->line('cms_general_label_publish');
						} else if ($post->post_status == 2) {
							$post_status = $this->lang->line('cms_general_label_trush');
						} else {
							$post_status = $this->lang->line('cms_general_label_unpublish');
						}
					?>

						<?php echo $post_status; ?>
					</span>
				</td>
			</tr>
			<?php
				if (isset($_childrens)) {
					$ci =& get_instance();
					echo $ci->nested($_childrens, $post->id);
				}
			?>
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