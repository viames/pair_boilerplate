<?php

use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<h5 class="float-left"><?php print Translator::do('USER_LIST'); ?></h5>
				<a class="btn btn-primary btn-sm float-right" href="users/new"><i class="fa fa-plus-circle"></i> <?php print Translator::do('NEW_USER'); ?></a>
			</div>
			<div class="card-body">
				<?php if (count($state->users)) { ?>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?php print Translator::do('NAME'); ?></th>
									<th><?php print Translator::do('USERNAME'); ?></th>
									<th><?php print Translator::do('EMAIL'); ?></th>
									<th><?php print Translator::do('GROUP'); ?></th>
									<th><?php print Translator::do('ENABLED'); ?></th>
									<th><?php print Translator::do('LAST_LOGIN'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($state->users as $user) { ?>
									<tr>
										<td>
											<?php if ($user->canEdit) { ?>
												<a href="users/edit/<?php print $user->id; ?>"><?php print htmlspecialchars($user->fullName); ?></a>
											<?php } else { ?>
												<?php print htmlspecialchars($user->fullName); ?>
											<?php } ?>
										</td>
										<td>
											<?php if ($user->canEdit) { ?>
												<a href="users/edit/<?php print $user->id; ?>"><?php print htmlspecialchars($user->username); ?></a>
											<?php } else { ?>
												<?php print htmlspecialchars($user->username); ?>
											<?php } ?>
										</td>
										<td class="cnt"><?php print htmlspecialchars($user->email); ?></td>
										<td class="small cnt"><?php print htmlspecialchars($user->groupName); ?></td>
										<td class="text-center"><?php print ($user->enabled ? '<i class="fa fa-check fa-lg text-success"></i>' : '<i class="fa fa-times fa-lg text-danger"></i>'); ?></td>
										<td class="small text-center"><?php print ($user->lastLogin !== '' ? Utilities::getTimeago($user->lastLogin) : ''); ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php print $state->paginationBar; ?>
				<?php } else { ?>
					<?php Utilities::showNoDataAlert(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
