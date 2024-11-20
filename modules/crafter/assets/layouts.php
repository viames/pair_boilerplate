<?php

// layout della pagina default
$layouts['default-page'] =
'<div class="card">
	<div class="card-header">
		<h4 class="card-title">{pageTitle}</h4>
	</div>
	<div class="card-body">
		<div style="overflow:hidden">
			<a href="{linkAdd}" class="btn btn-primary btn-sm float-right">{newElement}</a>
		</div>
		<hr><?php

		if (count({itemsArray})) {

			?><div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
{tableHeaders}
						</tr>
					</thead>
					<tbody>
{tableRows}
					</tbody>
				</table>
			</div><?php

			print $this->getPaginationBar();
				
		} else {
				
			Pair\Support\Utilities::printNoDataMessageBox();
				
		}
	
	?></div>
</div>';

$layouts['default-table-header'] = "\t\t\t\t\t\t\t\t<th>{tableHeader}</th>";

$layouts['default-table-row'] = "\t\t\t\t\t\t\t<tr>\n{tableCells}\n\t\t\t\t\t\t\t</tr>";

$layouts['default-table-cell'] = "\t\t\t\t\t\t\t\t<td>{tableCell}</td>";

// layout della pagina new
$layouts['new-page'] =
'<div class="card">
	<div class="card-header">
		<h4 class="card-title">{pageTitle}</h4>
	</div>
	<div class="card-body">
		<form action="{formAction}" method="post" class="form-horizontal">
			<fieldset>{fields}
			</fieldset>
			<div class="hr-line-dashed"></div>
			<div class="form-group row">
				<div class="col-md-push-3 col-md-9">
					<button type="submit" class="btn btn-primary"><?php $this->_(\'INSERT\') ?></button>
					<a href="{cancelUrl}" class="btn btn-secondary"><?php $this->_(\'CANCEL\') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>';
		
$layouts['new-field'] = '
						<div class="form-group">
							<label class="col-md-3 control-label">{fieldLabel}</label>
							<div class="col-md-9">{fieldControl}</div>
						</div>';

// layout della pagina edit
$layouts['edit-page'] =
'<div class="card">
	<div class="card-header">
		<h4 class="card-title">{pageTitle}</h4>
	</div>
	<div class="card-body">
		<form action="{formAction}" method="post" class="form-horizontal">
			{hiddenFields}
			<fieldset>{fields}
			</fieldset>
			<div class="hr-line-dashed"></div>
			<div class="form-group row">
				<div class="col-md-push-3 col-md-9">
					<button type="submit" class="btn btn-primary"><?php $this->_(\'CHANGE\') ?></button>
					<a href="{cancelUrl}" class="btn btn-secondary"><?php $this->_(\'CANCEL\') ?></a><?php
					if ($this->{object}->isDeletable()) { ?>
					<a href="{deleteUrl}" class="btn btn-link confirm-delete float-right"><?php $this->_(\'DELETE\') ?></a><?php
					} ?>
				</div>
			</div>
		</form>
	</div>
</div>';

$layouts['edit-field'] = '
						<div class="form-group">
							<label class="col-md-3 control-label">{fieldLabel}</label>
							<div class="col-md-9">{fieldControl}</div>
						</div>';
