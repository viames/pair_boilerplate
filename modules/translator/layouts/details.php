<?php

use Pair\Helpers\Utilities;

?>
<div class="card">
	<div class="card-header">
		<h5><?php BoilerplateLayout::printText('DETAILS_OF_TRANSLATION', $state->locale->getEnglishNames()); ?></h5>
	</div>
	<div class="card-body">
		<div class="table-responsive"><?php

if (count($state->locale->details)) {

	?><table class="table table-hover">
		<thead>
			<tr>
				<th class="text-center"><?php BoilerplateLayout::printText('MODULE'); ?></th>
				<th class="text-center"><?php BoilerplateLayout::printText('PERCENTAGE'); ?></th>
				<th class="text-center"><?php BoilerplateLayout::printText('TRANSLATED_LINES'); ?></th>
				<th class="text-center"><?php BoilerplateLayout::printText('EDITED'); ?></th>
				<th class="text-center"><?php BoilerplateLayout::printText('EDIT'); ?></th>
			</tr>
		</thead>
		<tbody><?php

		foreach ($state->locale->details as $detail) {

			?><tr>
				<td class="text-left align-middle"><?php print htmlspecialchars(ucfirst($detail->moduleName)) ?></td>
				<td class="text-center align-middle" style="width:30%"><?php print $detail->progressBar ?></td>
				<td class="text-center align-middle"><?php print $detail->count . '/' . $detail->default ?></td>
				<td class="text-center align-middle small"><?php print $detail->dateChanged ?></td>
				<td class="text-center align-middle"><?php print $detail->editButton ?></td>
			</tr><?php 
						
		}
					
		?></tbody>
	</table><?php
				
} else {
			
	Utilities::showNoDataAlert();
			
}	
	
		?></div>
	</div>
</div>
