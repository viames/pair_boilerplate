<?php

/**
 * @version	$Id$
 * @author	Viames Marino
 */

// layout della pagina default
$layouts['default-page'] =
'<div class="row">
	<div class="col-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">{pageTitle}</h4>
			</div>
			<div class="panel-body">
				<a href="{linkAdd}" class="btn btn-primary"><i class="fa fa-plus"></i> {newElement}</a>
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
						
					Pair\Utilities::printNoDataMessageBox();
						
				}
			
			?></div>
		</div>
	</div>
</div>';

$layouts['default-table-header'] = "\t\t\t\t\t\t\t\t<th>{tableHeader}</th>";

$layouts['default-table-row'] = "\t\t\t\t\t\t\t<tr>{tableCells}</tr>";

$layouts['default-table-cell'] = "\t\t\t\t\t\t\t\t<td>{tableCell}</td>";

// layout della pagina new
$layouts['new-page'] =
'<div class="row">
	<div class="col-md-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">{pageTitle}</h4>
			</div>
			<div class="panel-body">
				<form action="{formAction}" method="post">
					<fieldset>{fields}
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><i class="fa fa-asterisk"></i> <?php $this->_(\'INSERT\') ?></button>
							<a href="{cancelUrl}" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_(\'CANCEL\') ?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>';
		
$layouts['new-field'] = '
						<div class="form-group row">
							<label class="col-md-3">{fieldLabel}</label>
							<div class="col-md-9">{fieldControl}</div>
						</div>';

// layout della pagina edit
$layouts['edit-page'] =
'<div class="row">
	<div class="col-md-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">{pageTitle}</h4>
			</div>
			<div class="panel-body">
				<form action="{formAction}" method="post">
					{hiddenFields}
					<fieldset>{fields}
					</fieldset>
					<div class="hr-line-dashed"></div>
					<div class="form-group row">
						<div class="col-md-push-3 col-md-9">
							<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php $this->_(\'CHANGE\') ?></button>
							<a href="{cancelUrl}" class="btn btn-secondary"><i class="fa fa-times"></i> <?php $this->_(\'CANCEL\') ?></a><?php
							if ($this->{object}->isDeletable()) { ?>
							<a href="{deleteUrl}" class="btn btn-link confirm-delete pull-right float-right"><i class="fa fa-trash"></i> <?php $this->_(\'DELETE\') ?></a><?php
							} ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>';

$layouts['edit-field'] = '
						<div class="form-group row">
							<label class="col-md-3">{fieldLabel}</label>
							<div class="col-md-9">{fieldControl}</div>
						</div>';
