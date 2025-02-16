<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('LANGUAGES') ?></h4>
			</div>
			<div class="card-body">
				<div class="list-filter">
					<a href="languages/default/page-1"><?php $this->_('ALL') ?></a><?php
				foreach ($this->filter as $f) {
					?><a href="<?php print $f->href ?>" class="<?php print ($f->active ? 'active' : '') ?>"><?php print $f->text ?></a><?php
				}
				
				?>
				</div>
				<hr>
				<div style="overflow:hidden">
					<a href="languages/new" class="btn btn-primary btn-sm float-right"><?php $this->_('NEW_LANGUAGE') ?></a>
				</div>
				<?php
	
				if (count($this->languages)) {
	
					?><div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
								<th><?php $this->_('ENGLISH_NAME') ?></th>
								<th><?php $this->_('NATIVE_NAME') ?></th>
								<th><?php $this->_('ISO_639_1') ?></th>
								<th><?php $this->_('DEFAULT_COUNTRY') ?></th>
								<th><?php $this->_('LOCALES') ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->languages as $o) { ?>
								<tr>
									<td><a href="languages/edit/<?php print $o->id ?>"><?php $o->printHtml('englishName') ?></a></td>
									<td><?php $o->printHtml('nativeName') ?></td>
									<td class="text-center"><?php $o->printHtml('code') ?></td>
									<td class="text-center"><?php $o->printHtml('defaultCountry') ?></td>
									<td class="text-right"><?php $o->printHtml('localeCount') ?></td>
								</tr><?php
								} ?>
							</tbody>
						</table>
					</div><?php
	
					print $this->getPaginationBar();
						
				} else {
						
					Pair\Helpers\Utilities::printNoDataMessageBox();
						
				}
			
			?></div>
		</div>
	</div>
</div>