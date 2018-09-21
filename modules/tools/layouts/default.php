<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h5 class="float-left"><?php $this->_('SPECIAL_FEATURES') ?></h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover"><?php 
			
						foreach ($this->tools as $tool) {
					
						?><tr>
							<td><?php print $tool['title'] ?></td>
							<td class="text-right">
								<a href="<?php print $tool['url'] ?>">
									<button type="submit" class="btn btn-sm btn-primary pull-right m-t-n-xs"><i class="fa fa-play fa-fw"></i> <?php $this->_('RUN')?></button>
								</a>
							</td>
						</tr>
						<?php
						
						}
				
					?></table>
				</div>
			</div>
		</div>
	</div>
</div>