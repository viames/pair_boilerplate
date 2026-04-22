<?php

use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

?>
<?php if (count($state->groups)) { ?>
	<div class="row">
		<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
					<h5 class="float-left"><?php print Translator::do('GROUPS'); ?></h5>
					<a class="btn btn-primary btn-sm float-right" href="groups/new"><i class="fa fa-plus-circle"></i> <?php print Translator::do('NEW_GROUP'); ?></a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?php print Translator::do('NAME'); ?></th>
									<th><?php print Translator::do('IS_DEFAULT'); ?></th>
									<th><?php print Translator::do('USERS'); ?></th>
									<th><?php print Translator::do('DEFAULT_MODULE'); ?></th>
									<th><?php print Translator::do('ACCESS_LIST'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($state->groups as $group) { ?>
									<tr<?php if ($group->highlightMissingAcl) { print ' class="alert"'; } ?>>
										<td><a href="groups/edit/<?php print $group->id; ?>"><?php print htmlspecialchars($group->name); ?></a></td>
										<td class="text-center"><?php print ($group->isDefault ? '<i class="fa fa-check fa-lg text-success"></i>' : ''); ?></td>
										<td class="text-center"><?php print $group->userCount; ?></td>
										<td class="text-center small"><?php print htmlspecialchars($group->moduleName); ?></td>
										<td class="text-center">
											<div class="iconText">
												<?php print $group->aclCount; ?>
												<a href="users/aclList/<?php print $group->id; ?>" class="btn btn-secondary btn-sm"><i class="fa fa-unlock-alt"></i></a>
											</div>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php print $state->paginationBar; ?>
<?php } else { ?>
	<?php Utilities::showNoDataAlert(); ?>
<?php } ?>
