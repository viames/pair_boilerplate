<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('LOCALES') ?></h4>
			</div>
			<div class="card-body">
				<div class="list-filter">
					<a href="locales/default/page-1"><?php $this->_('ALL') ?></a><?php
				foreach ($this->filter as $f) {
					?><a href="<?php print $f->href ?>" class="<?php print ($f->active ? 'active' : '') ?>"><?php print $f->text ?></a><?php
				}
				
				?>
				</div>
				<hr>
				<div style="overflow:hidden">
					<a href="locales/new" class="btn btn-primary btn-sm float-right"><?php $this->_('NEW_LOCALE') ?></a>
				</div>
				<?php
	
				if (count($this->locales)) {
	
					?><div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
								<th><?php $this->_('REPRESENTATION') ?></th>
								<th><?php $this->_('LANGUAGE') ?></th>
								<th><?php $this->_('OFFICIAL_LANGUAGE') ?></th>
								<th><?php $this->_('COUNTRY') ?></th>
								<th><?php $this->_('DEFAULT_COUNTRY') ?></th>
								<th><?php $this->_('APP_DEFAULT') ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->locales as $o) { ?>
								<tr>
									<td><a href="locales/edit/<?php print $o->id ?>"><?php print $o->representation ?></a></td>
									<td><?php print htmlspecialchars($o->languageName) ?></td>
									<td class="text-center"><?php print ($o->officialLanguage ? '<i class="fa fa-check text-success"></i>' : '') ?></td>
									<td><?php print htmlspecialchars($o->countryName) ?></td>
									<td class="text-center"><?php print ($o->defaultCountry ? '<i class="fa fa-check text-success"></i>' : '') ?></td>
									<td class="text-center"><?php print ($o->appDefault ? '<i class="fa fa-check text-success"></i>' : '') ?></td>
								</tr><?php
							} ?>
							</tbody>
						</table>
					</div><?php
	
					print $this->getPaginationBar();
						
				} else {
						
					Pair\Support\Utilities::printNoDataMessageBox();
						
				}
			
			?></div>
		</div>
	</div>
</div>