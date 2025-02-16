<div class="card">
	<div class="card-header">
		<h5><?php $this->_('DETAILS_OF_TRANSLATION', $this->locale->getEnglishNames()) ?></h5>
	</div>
	<div class="card-body">
		<div class="table-responsive"><?php

if (count($this->locale->details)) {

	?><table class="table table-hover">
		<thead>
			<tr>
				<th class="text-center"><?php $this->_('MODULE') ?></th>
				<th class="text-center"><?php $this->_('PERCENTAGE') ?></th>
				<th class="text-center"><?php $this->_('TRANSLATED_LINES') ?></th>
				<th class="text-center"><?php $this->_('EDITED') ?></th>
				<th class="text-center"><?php $this->_('EDIT') ?></th>
			</tr>
		</thead>
		<tbody><?php

		foreach ($this->locale->details as $detail) {

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
			
	Pair\Helpers\Utilities::printNoDataMessageBox();
			
}	
	
		?></div>
	</div>
</div>