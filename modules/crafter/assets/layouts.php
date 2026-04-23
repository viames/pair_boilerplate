<?php

// layout della pagina default
$layouts['default-page'] =
'<?php

use Pair\Helpers\Utilities;

?><div class="card">
	<div class="card-header">
		<div class="float-end">
			<a class="p-1 btn btn-sm btn-outline-primary mt-1 float-end" href="{linkAdd}"><i class="fal fa-plus-large fa-fw"></i></a>
		</div>
		<h4 class="card-title">{pageTitle}</h4>
	</div>
	<div class="card-body">
		<?php

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

			print {paginationBar};

		} else {

			Utilities::showNoDataAlert();

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
		<form action="{formAction}" method="post">
			<fieldset>{fields}
			</fieldset>
			<div class="hr-line-dashed"></div>
			<div class="row mb-4">
				<div class="col-md-9 offset-md-3">
					<button type="submit" class="btn btn-primary"><?php $this->_(\'INSERT\') ?></button>
					<a href="{cancelUrl}" class="btn btn-secondary"><?php $this->_(\'CANCEL\') ?></a>
				</div>
			</div>
		</form>
	</div>
</div>';

$layouts['new-field'] = '
				<div class="row mb-4">
					<div class="col-md-3">{fieldLabel}</div>
					<div class="col-md-9">{fieldControl}</div>
				</div>';

// layout della pagina edit
$layouts['edit-page'] =
'<div class="card">
	<div class="card-header">
		<h4 class="card-title">{pageTitle}</h4>
	</div>
	<div class="card-body">
		<form action="{formAction}" method="post">
			{hiddenFields}
			<fieldset>{fields}
			</fieldset>
			<div class="hr-line-dashed"></div>
			<div class="row mb-4">
				<div class="col-md-9 offset-md-3">
					<button type="submit" class="btn btn-primary"><?php $this->_(\'CHANGE\') ?></button>
					<a href="{cancelUrl}" class="btn btn-secondary"><?php $this->_(\'CANCEL\') ?></a><?php
					if ($this->{object}->isDeletable()) { ?>
					<a href="{deleteUrl}" class="btn btn-link confirm-delete float-end"><?php $this->_(\'DELETE\') ?></a><?php
					} ?>
				</div>
			</div>
		</form>
	</div>
</div>';

$layouts['edit-field'] = '
				<div class="row mb-4">
					<div class="col-md-3">{fieldLabel}</div>
					<div class="col-md-9">{fieldControl}</div>
				</div>';
