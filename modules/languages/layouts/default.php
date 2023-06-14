<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php $this->_('LANGUAGES') ?></h4>
				<div class="list-filter">
					<a href="languages/default/page-1"><?php $this->_('ALL') ?></a><?php
				
				foreach ($this->filter as $f) {
					?><a href="<?php print $f->href ?>" class="<?php print ($f->active ? 'active' : '') ?>"><?php print $f->text ?></a><?php
				}
				
				?>
				</div>
			</div>
			<div class="card-body">
				<hr>
				<a href="languages/new" class="btn btn-primary"><i class="fa fa-plus"></i> <?php $this->_('NEW_LANGUAGE') ?></a>
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
									<td><a href="languages/edit/<?php print $o->id ?>"><?php print htmlspecialchars($o->englishName) ?></a></td>
									<td><?php print htmlspecialchars($o->nativeName) ?></td>
									<td class="text-center"><?php print htmlspecialchars($o->code) ?></td>
									<td class="text-center"><?php print htmlspecialchars($o->defaultCountry) ?></td>
									<td class="text-right"><?php print $o->localeCount ?></td>
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