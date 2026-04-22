<?php

use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<h5 class="float-left"><?php print Translator::do('ACCESS_LIST_OF_GROUP', $state->groupName); ?></h5>
				<?php if ($state->missingAcl) { ?>
					<a class="btn btn-primary btn-sm float-right" href="users/aclNew/<?php print $state->groupId; ?>"><i class="fa fa-plus-circle"></i> <?php print Translator::do('ADD'); ?></a>
				<?php } ?>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<?php if (count($state->acl)) { ?>
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?php print Translator::do('MODULE_NAME'); ?></th>
									<th><?php print Translator::do('MODULE_ACTION'); ?></th>
									<th><?php print Translator::do('DELETE'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($state->acl as $item) { ?>
									<tr>
										<td class="lft"><?php print htmlspecialchars($item->moduleName); ?></td>
										<td class="text-center"><?php print htmlspecialchars($item->actionLabel); ?></td>
										<td class="text-center">
											<?php if ($item->canDelete) { ?>
												<a class="btn btn-secondary btn-sm" href="users/aclDelete/<?php print $item->id; ?>"><i class="fa fa-times"></i></a>
											<?php } else { ?>
												<i class="fa fa-times disabled"></i>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					<?php } else { ?>
						<?php Utilities::showNoDataAlert(); ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
