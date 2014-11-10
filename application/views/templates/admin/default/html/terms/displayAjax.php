	<div class="search-status">
		<div class="row">
			<div class="col-sm-8">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit.
			</div><!-- end col-sm-8 -->

			<div class="col-sm-4">
				<div class="input-group">
	  				<input type="text" class="form-control text-search" />
	  				<span class="input-group-addon"><button type="button" id="search-btn" data-href="<?php echo base_url(); ?>admin/terms/displayTermsAjax/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/"><span class="glyphicon glyphicon-search"></span></button></span>
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
						<button id="goto-btn" class="btn btn-cms" data-limit="<?php echo $_limit; ?>" data-maxpage="<?php echo ceil($this->pagination->total_rows / $this->pagination->per_page); ?>" data-href="<?php echo base_url(); ?>admin/terms/displayTermsAjax/<?php echo $_sort_by; ?>/<?php echo $_sort_order; ?>/<?php echo $_search; ?>/"><?php echo $this->lang->line('cms_general_label_go'); ?></button>
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
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/terms/displayTermsAjax/term_id/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>">Id <span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/terms/displayTermsAjax/name/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_name'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/terms/displayTermsAjax/description/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_description'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/terms/displayTermsAjax/slug/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_label_slug'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
				<th><a class="link-ajax" href="<?php echo base_url(); ?>admin/terms/displayTermsAjax/count/<?php echo (($_sort_order == 'asc') ? 'desc' : 'asc'); ?>/<?php echo $_search; ?>"><?php echo $this->lang->line('cms_general_title_posts'); ?><span class="glyphicon <?php echo ($_sort_order == 'asc') ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></a></th>
			</tr>
		</thead>
		<tbody>
		<?php if (isset($_masters) && sizeof($_masters) > 0) : ?>
			<?php foreach ($_masters as $master) : ?>
			<tr>
				<td class="text-center"><?php echo $master->term_id; ?></td>
				<td class="view-option-post">
					<a href="<?php echo base_url(); ?>admin/terms/edit/<?php echo $master->term_id; ?>"><?php echo $master->name; ?></a>
					<div class="opt-post">
						<ul class="list-inline">
							<li>
								<small>
									<a href="<?php echo base_url(); ?>admin/terms/edit/<?php echo $master->term_id; ?>"><?php echo $this->lang->line('cms_general_label_edit'); ?></a>
								</small>
							</li>
							<li><small><a class="text-danger" href="<?php echo base_url(); ?>admin/terms/delete/<?php echo $master->term_id; ?>"><?php echo $this->lang->line('cms_general_label_delete'); ?></a></small></li>
							<li><small><a href="#">Ver</a></small></li>
						</ul>
					</div>
				</td>
				<td><?php echo $master->description; ?></td>
				<td class="text-center"><?php echo $master->slug; ?></td>
				<td class="text-center"><?php echo $master->count; ?></td>
			</tr>
				<?php
					if (isset($_childrens)) {
						$ci =& get_instance();
						echo $ci->nested($_childrens, $master->term_id);
					}
				?>
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