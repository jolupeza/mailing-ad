	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-heading">

					<h2><?php echo $this->lang->line('cms_general_title_send_campaign'); ?></h2>

				</div><!-- end panel-heading -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->

	<div class="row">

		<div class="col-sm-12">

			<div class="panel">

				<div class="panel-body info-static">

					<div class="row">

						<div class="col-sm-6">

							<ul>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_name') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->name; ?></span>
								</li>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_sender') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->sender; ?></span>
								</li>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_email_sender') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->email_sender; ?></span>
								</li>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_email_reply') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->email_reply; ?></span>
								</li>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_title_newsletters') ?>: </span>
									<span class="info-descrip"><?php echo $_news->name; ?></span>
								</li>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_subject') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->subject; ?></span>
								</li>


								<?php if (count($_lists) > 0) : ?>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_title_lists') ?>: </span>
									<span class="info-descrip">
										<ul>
											<?php foreach($_lists as $list) : ?>
											<li><?php echo $list; ?></li>
											<?php endforeach; ?>
										</ul>
									</span>
								</li>
								<?php endif; ?>
							</ul>

						</div><!-- end col-sm-6 -->
						<div class="col-sm-3">
							<ul>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_date_created') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->created_at; ?></span>
								</li>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_date_submit') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->submit_at; ?></span>
								</li>
								<?php /*
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_initiated') ?>: </span>
									<?php $date_initiated = (date('now')) ?>
									<span class="info-descrip"><?php echo $_camp->submit_at; ?></span>
								</li>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_finalized') ?>: </span>
									<span class="info-descrip"><?php echo $_camp->submit_at; ?></span>
								</li>
								*/ ?>
							</ul>
						</div><!-- end col-sm-3 -->
						<div class="col-sm-3">
							<ul>
								<li>
									<span class="info-title"><?php echo $this->lang->line('cms_general_label_total_send') ?>: </span>
									<span class="info-descrip"><?php echo $stats->total->delivered; ?></span>
								</li>
							</ul>

						<?php if ($_camp->status == 1) : ?>
							<?php
								$attr = array(
									'id' 		=> 'send_camp',
									'class' 	=> 'btn btn-cms',
									'type' 		=> 'button',
									'value' 	=> $this->lang->line('cms_general_label_add'),
									'content' 	=> $this->lang->line('cms_general_label_send') . '<i class="fa fa-send"></i>',
									'data-id'	=>	$_camp->id
								);

								if ($_camp->send == 1) {
									$attr['disabled'] = 'disabled';
								}
							?>
							<?php echo form_button($attr); ?>
							<?php //echo form_button(array('id' => 'send_camp', 'class' => 'btn btn-cms', 'type' => 'button', 'value' => $this->lang->line('cms_general_label_add'), 'content' => $this->lang->line('cms_general_label_send') . '<i class="fa fa-send"></i>', 'data-id' => $_camp->id)); ?>
						<?php endif; ?>

					<?php if ($this->user->has_permission('view_reports')) : ?>
						<?php if ($_camp->send == 1) : ?>
							<h4>Reportes</h4>
							<?php if ($stats->total->delivered > 0) : ?>
							<a href="<?php echo base_url(); ?>admin/campaigns/generateReport/delivered/<?php echo $_camp->mailgun_id; ?>" class="btn btn-primary btn-lg btn-block">Delivered</a>
							<?php endif; ?>

							<?php if ($stats->total->opened > 0) : ?>
							<a href="<?php echo base_url(); ?>admin/campaigns/generateReport/opened/<?php echo $_camp->mailgun_id; ?>" class="btn btn-primary btn-lg btn-block">Opens</a>
							<?php endif; ?>

							<?php if ($stats->total->clicked > 0) : ?>
							<a href="<?php echo base_url(); ?>admin/campaigns/generateReport/clicked/<?php echo $_camp->mailgun_id; ?>" class="btn btn-primary btn-lg btn-block">Clicks</a>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>

						</div><!-- end col-sm-3 -->

				  	</div><!-- end row -->

				</div><!-- end panel-body .info-static -->

			</div><!-- end panel -->

		</div><!-- end col-sm-12 -->

	</div><!-- end row -->