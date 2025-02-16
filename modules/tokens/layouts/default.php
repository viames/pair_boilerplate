<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<?php if (count($this->tokens)) { ?>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?php $this->_('CODE') ?></th>
									<th><?php $this->_('DESCRIPTION') ?></th>
									<th><?php $this->_('ENABLED') ?></th>
									<th><?php $this->_('CREATED_BY') ?></th>
									<th><?php $this->_('CREATION_DATE') ?></th>
									<th><?php $this->_('LAST_USE') ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->tokens as $o) { ?>
							<tr>
								<td><a href="tokens/edit/<?php print $o->id ?>"><?php $o->printHtml('code') ?></a></td>
								<td><?php $o->printHtml('description') ?></td>
								<td class="text-center"><?php $o->printHtml('enabled') ?></td>
								<td class="text-center"><?php print htmlspecialchars($o->getRelated('createdBy')->getFullName()) ?></td>
								<td class="text-center"><?php print Pair\Helpers\Utilities::getTimeago($o->creationDate) ?></td>
								<td class="text-center"><?php print Pair\Helpers\Utilities::getTimeago($o->lastUse) ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div><?php
				
					print $this->getPaginationBar();

				} else {
						
					Pair\Helpers\Utilities::printNoDataMessageBox();
					
				}
				
				?>
			</div>
		</div>
	</div>
</div>