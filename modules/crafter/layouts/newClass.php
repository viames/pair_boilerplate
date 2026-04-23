<?php
declare(strict_types=1);

/** @var CrafterNewClassPageState $state */
?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CRAFTER'), ENT_QUOTES, 'UTF-8') ?></h4>
			</div><?php
		
			if (count($state->unmappedTables)) {
			
				?><div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('TABLE_NAME'), ENT_QUOTES, 'UTF-8') ?></th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody><?php 
						
							foreach ($state->unmappedTables as $t) {
						
								?><tr>
									<td><?php print $t ?></td>
									<td><a href="crafter/newClassWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CREATE_CLASS'), ENT_QUOTES, 'UTF-8') ?></a></td>
									<td><a href="crafter/newModuleWizard/<?php print $t ?>" class="btn btn-primary btn-sm"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CREATE_MODULE'), ENT_QUOTES, 'UTF-8') ?></a></td>
								</tr><?php 
								
							}
							
						?></tbody>
					</table>
				</div><?php
			
			} else {
				
				Pair\Helpers\Utilities::showNoDataAlert();
				
			}
			
		?></div>
	</div>
</div>
