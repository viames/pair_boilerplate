<div class="card">
	<div class="card-header">
		<h5 class="float-left"><?php print $this->_('TEMPLATE_PLUGINS') ?></h5>
		<a class="btn btn-primary btn-sm float-right" href="templates/new"><i class="fal fa-plus-circle"></i> <?php print $this->_('NEW_TEMPLATE') ?></a>
	</div>
	<div class="card-body"><?php
	
	if (count($this->templates)) {
	
		?>	
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?php $this->_('NAME') ?></th>
						<th><?php $this->_('DEFAULT') ?></th>
						<th><?php $this->_('COMPATIBLE') ?></th>
						<th><?php $this->_('PALETTE') ?></th>
						<th><?php $this->_('RELEASE_DATE') ?></th>
						<th><?php $this->_('INSTALL_DATE') ?></th>
						<th><?php $this->_('DERIVED') ?></th>
						<th><?php $this->_('DOWNLOAD') ?></th>
						<th><?php $this->_('DELETE') ?></th>
					</tr>
				</thead>
				<tbody><?php
	
				foreach ($this->templates as $template) {

					?>
					<tr>
						<td><?php print htmlspecialchars($template->name . ' v' . $template->version) ?></td>
						<td class="text-center"><?php print $template->defaultIcon ?></td>
						<td class="text-center"><?php print $template->compatible ?></td>
						<td class="text-center small"><?php print $template->paletteSamples ?></td>
						<td class="text-center small"><?php print $template->formatDateTime('dateReleased', $this->lang('DATE_FORMAT')) ?></td>
						<td class="text-center small"><?php print $template->formatDateTime('dateInstalled', $this->lang('DATE_FORMAT')) ?></td>
						<td class="text-center"><?php print $template->derivedIcon ?></td>
						<td class="text-center"><?php print $template->downloadIcon ?></td>
						<td class="text-center"><?php print $template->deleteIcon ?></td>
					</tr><?php 
		
				}
	
				?>
				</tbody>
			</table>
		</div><?php

	print $this->getPaginationBar();

} else {

	Pair\Support\Utilities::printNoDataMessageBox();

}

	?>
	</div>
</div>