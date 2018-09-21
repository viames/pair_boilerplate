<div class="row">
	<div class="col-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('LOCALES') ?></h4>
			</div>
			<div class="panel-body">
				<a href="locales/new" class="btn btn-primary"><i class="fa fa-plus"></i> <?php $this->_('NEW_LOCALE') ?></a>
				<hr><?php
	
				if (count($this->locales)) {
	
					?><div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
								<th><?php $this->_('REPRESENTATION') ?></th>
								<th><?php $this->_('LANGUAGE') ?></th>
								<th><?php $this->_('COUNTRY') ?></th>
								<th><?php $this->_('OFFICIAL_LANGUAGE') ?></th>
								<th><?php $this->_('DEFAULT_COUNTRY') ?></th>
								<th><?php $this->_('APP_DEFAULT') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($this->locales as $o) { ?>
								<tr>
									<td><a href="locales/edit/<?php print $o->id ?>"><?php print $o->representation ?></a></td>
									<td><?php print htmlspecialchars($o->languageName) ?></td>
									<td><?php print htmlspecialchars($o->countryName) ?></td>
									<td class="text-center"><?php print ($o->officialLanguage ? '<i class="fa fa-check text-success"></i>' : '') ?></td>
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