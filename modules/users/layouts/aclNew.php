<?php

use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-header">
			<h5 class="float-left"><?php print Translator::do('NEW_ACL'); ?> <?php print htmlspecialchars($state->groupName); ?></h5>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<?php if (count($state->rules)) { ?>
					<form action="users/aclAdd" method="post">
						<?php $state->form->printControl('groupId'); ?>
						<table class="table table-hover">
							<thead>
								<tr>
									<th style="width:100px">
										<div class="selectAllRows"><?php print Translator::do('SELECT_ALL'); ?></div>
										<div class="deselectAllRows hidden"><?php print Translator::do('DESELECT_ALL'); ?></div>
									</th>
									<th><?php print Translator::do('MODULE_NAME'); ?></th>
									<th><?php print Translator::do('MODULE_ACTION'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($state->rules as $rule) { ?>
									<tr>
										<td class="cnt"><input type="checkbox" value="<?php print $rule->id; ?>" name="aclChecked[]" /></td>
										<td class="lft"><?php print htmlspecialchars($rule->moduleName); ?></td>
										<td class="cnt"><?php print htmlspecialchars($rule->actionLabel); ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="buttonBar">
							<button type="submit" class="btn btn-primary" value="addAcl" name="action"><i class="fa fa-asterisk"></i> <?php print Translator::do('ADD'); ?></button>
							<a href="users/aclList/<?php print $state->groupId; ?>" class="btn btn-secondary"><i class="fa fa-times"></i> <?php print Translator::do('CANCEL'); ?></a>
						</div>
					</form>
				<?php } else { ?>
					<?php Utilities::showNoDataAlert(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
