<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('TRANSLATIONS') ?></h4>
			</div>
			<div class="card-body">
				<div class="list-filter">
					<a href="translator/default/page-1"><?php $this->_('ALL') ?></a><?php
		
				foreach ($this->filter as $f) {
					?><a href="<?php print $f->href ?>" class="<?php print ($f->active ? 'active' : '') ?>"><?php print $f->text ?></a><?php
				}
			
				?>
				</div>
				<hr>
				<div class="table-responsive" id="pageTranslator"><?php

				if (count($this->locales)) {
				
					?><table class="table table-hover">
						<thead>
							<tr>
								<th><?php $this->sortable($this->lang('LANGUAGE'), 1, 2) ?></th>
								<th><?php $this->sortable($this->lang('COUNTRY'), 3, 4) ?></th>
								<th><?php $this->sortable($this->lang('REPRESENTATION'), 5, 6) ?></th>
								<th><?php $this->_('PERCENTAGE') ?></th>
								<th><?php $this->_('TRANSLATED_LINES') ?></th>
								<th><?php $this->_('DEFAULT') ?></th>
								<th><?php $this->_('DETAILS') ?></th>
							</tr>
						</thead>
						<tbody><?php
			
						foreach ($this->locales as $locale) {
				
							?><tr>
								<td><?php print htmlspecialchars($locale->languageName) ?>
								<td><?php print htmlspecialchars($locale->countryName) ?></td>
								<td class="text-center"><?php print $locale->representation ?></td>
								<td class="text-center" style="width:30%"><?php print $locale->progressBar ?></td>
								<td class="text-center"><?php print $locale->complete ?></td>
								<td class="text-center"><?php print $locale->defaultIcon ?></td>
								<td class="text-center"><a href="translator/details/<?php print $locale->id ?>" title="<?php $this->_('SEE_DETAILS') ?>"><i class="fa fa-eye fa-lg"></i></a>					
							</tr><?php 
						
								}
					
							?>
							</tbody>
					</table><?php
				
						print $this->getPaginationBar();
			
					} else {

						Pair\Helpers\Utilities::printNoDataMessageBox();

					}
	
				?></div>
			</div>
		</div>
	</div>
</div>