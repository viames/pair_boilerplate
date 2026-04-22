<?php

use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php BoilerplateLayout::printText('TRANSLATIONS'); ?></h4>
			</div>
			<div class="card-body">
				<div class="list-filter">
					<?php Utilities::printAlphaFilter($state->selectedFilter); ?>
				</div>
				<hr>
				<div class="table-responsive" id="pageTranslator"><?php

				if (count($state->locales)) {
				
					?><table class="table table-hover">
						<thead>
							<tr>
								<th><?php BoilerplateLayout::printSortable(BoilerplateLayout::translate('LANGUAGE'), 1, 2); ?></th>
								<th><?php BoilerplateLayout::printSortable(BoilerplateLayout::translate('COUNTRY'), 3, 4); ?></th>
								<th><?php BoilerplateLayout::printSortable(BoilerplateLayout::translate('REPRESENTATION'), 5, 6); ?></th>
								<th><?php BoilerplateLayout::printText('PERCENTAGE'); ?></th>
								<th><?php BoilerplateLayout::printText('TRANSLATED_LINES'); ?></th>
								<th><?php BoilerplateLayout::printText('DEFAULT'); ?></th>
								<th><?php BoilerplateLayout::printText('DETAILS'); ?></th>
							</tr>
						</thead>
						<tbody><?php
			
						foreach ($state->locales as $locale) {
				
							?><tr>
								<td><?php print htmlspecialchars($locale->languageName) ?>
								<td><?php print htmlspecialchars($locale->countryName) ?></td>
								<td class="text-center"><?php print $locale->representation ?></td>
								<td class="text-center" style="width:30%"><?php print $locale->progressBar ?></td>
								<td class="text-center"><?php print $locale->complete ?></td>
								<td class="text-center"><?php print $locale->defaultIcon ?></td>
								<td class="text-center"><a href="translator/details/<?php print $locale->id ?>" title="<?php print htmlspecialchars(BoilerplateLayout::translate('SEE_DETAILS')) ?>"><i class="fa fa-eye fa-lg"></i></a>
							</tr><?php 
						
								}
					
							?>
							</tbody>
					</table><?php
				
						print $state->paginationBar;
			
					} else {

						Utilities::showNoDataAlert();

					}
	
				?></div>
			</div>
		</div>
	</div>
</div>
