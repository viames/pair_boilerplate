<?php
use Pair\Utilities;
?>
<div class="row">
	<div class="col-12">
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
								<td><a href="tokens/edit/<?php print $o->id ?>"><?php print htmlspecialchars($o->code) ?></a></td>
								<td><?php print htmlspecialchars($o->description) ?></td>
								<td class="text-center"><?php print $o->enabled ?></td>
								<td class="text-center"><?php print htmlspecialchars($o->getRelated('createdBy')->getFullName()) ?></td>
								<td class="text-center"><?php print Utilities::getTimeago($o->creationDate) ?></td>
								<td class="text-center"><?php print Utilities::getTimeago($o->lastUse) ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div><?php
				
					print $this->getPaginationBar();

				} else {
						
					Utilities::printNoDataMessageBox();
					
				}
				
				?>
			</div>
		</div>
	</div>
</div>