<div class="row">
	<div class="col-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title"><?php $this->_('COUNTRIES') ?></h4>
				<div class="list-filter">
					<a href="countries/default/page-1"><?php $this->_('ALL') ?></a><?php
				
				foreach ($this->filter as $f) {
					?><a href="<?php print $f->href ?>" class="<?php print ($f->active ? 'active' : '') ?>"><?php print $f->text ?></a><?php
				}
				
				?>
				</div>
			</div>
			<div class="panel-body">
				<hr>
				<a href="countries/new" class="btn btn-primary"><i class="fa fa-plus"></i> <?php $this->_('NEW_COUNTRY') ?></a>
				<?php
	
				if (count($this->countries)) {
	
					?><div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
								<th><?php $this->_('ENGLISH_NAME') ?></th>
								<th><?php $this->_('NATIVE_NAME') ?></th>
								<th><?php $this->_('ISO_3166_1') ?></th>
								<th><?php $this->_('OFFICIAL_LANGUAGES') ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($this->countries as $o) { ?>
								<tr>
									<td><a href="countries/edit/<?php print $o->id ?>"><?php print htmlspecialchars($o->englishName) ?></a></td>
									<td><?php print htmlspecialchars($o->nativeName) ?></td>
									<td class="text-center"><?php print htmlspecialchars($o->code) ?></td>
									<td><?php print $o->officialLanguages ?></td>
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