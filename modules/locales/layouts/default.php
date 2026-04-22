<?php

use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print Translator::do('LOCALES'); ?></h4>
			</div>
			<div class="card-body">
				<div class="list-filter"><?php Utilities::printAlphaFilter(); ?></div>
				<hr>
				<div style="overflow:hidden">
					<a href="locales/new" class="btn btn-primary btn-sm float-right"><?php print Translator::do('NEW_LOCALE'); ?></a>
				</div>
				<?php if (count($state->locales)) { ?>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?php print Translator::do('REPRESENTATION'); ?></th>
									<th><?php print Translator::do('LANGUAGE'); ?></th>
									<th><?php print Translator::do('OFFICIAL_LANGUAGE'); ?></th>
									<th><?php print Translator::do('COUNTRY'); ?></th>
									<th><?php print Translator::do('DEFAULT_COUNTRY'); ?></th>
									<th><?php print Translator::do('APP_DEFAULT'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($state->locales as $locale) { ?>
									<tr>
										<td><a href="locales/edit/<?php print $locale->id; ?>"><?php print htmlspecialchars($locale->representation); ?></a></td>
										<td><?php print htmlspecialchars($locale->languageName); ?></td>
										<td class="text-center"><?php print ($locale->officialLanguage ? '<i class="fa fa-check text-success"></i>' : ''); ?></td>
										<td><?php print htmlspecialchars($locale->countryName); ?></td>
										<td class="text-center"><?php print ($locale->defaultCountry ? '<i class="fa fa-check text-success"></i>' : ''); ?></td>
										<td class="text-center"><?php print ($locale->appDefault ? '<i class="fa fa-check text-success"></i>' : ''); ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php print $state->paginationBar; ?>
				<?php } else { ?>
					<?php Utilities::showNoDataAlert(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
