<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('LOCALES') ?></h4>
				<div class="list-filter">
					<a href="locales/default/page-1"><?php $this->_('ALL') ?></a><?php
				
				foreach ($this->filter as $f) {
					?><a href="<?php print $f->href ?>" class="<?php print ($f->active ? 'active' : '') ?>"><?php print $f->text ?></a><?php
				}
				
				?>
				</div>
			</div>
			<div class="card-body">
				<hr>
				<a href="locales/new" class="btn btn-primary"><i class="fa fa-plus"></i> <?php $this->_('NEW_LOCALE') ?></a>
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
						
					Pair\Utilities::printNoDataMessageBox();
						
				}
			
			?></div>
		</div>
	</div>
</div>