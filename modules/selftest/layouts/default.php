<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h5 class="float-left"><?php $this->_('SELF_TEST') ?></h5>
				<a class="btn btn-primary btn-sm float-right" href="selftest/default"><i class="fa fa-redo"></i> <?php $this->_('REFRESH') ?></a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<tbody><?php
		
						foreach ($this->sections as $name=>$tests) {

							?><tr><th colspan="2"><?php print $name ?></th></tr><?php

							foreach ($tests as $item) {
						
								?><tr>
									<td><?php print $item->label ?></td>
									<td><?php print $item->result ? PAIR_CHECK_ICON : PAIR_TIMES_ICON ?></td>
								</tr><?php
								
							}
		
						}
	
						?></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>