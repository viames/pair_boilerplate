<?php

use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print Translator::do('COUNTRIES'); ?></h4>
			</div>
			<div class="card-body">
				<div class="list-filter"><?php Utilities::printAlphaFilter(); ?></div>
				<hr>
				<div style="overflow:hidden">
					<a href="countries/new" class="btn btn-primary btn-sm float-right"><?php print Translator::do('NEW_COUNTRY'); ?></a>
				</div>
				<?php if (count($state->countries)) { ?>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?php print Translator::do('ENGLISH_NAME'); ?></th>
									<th><?php print Translator::do('NATIVE_NAME'); ?></th>
									<th><?php print Translator::do('ISO_3166_1'); ?></th>
									<th><?php print Translator::do('OFFICIAL_LANGUAGES'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($state->countries as $country) { ?>
									<tr>
										<td><a href="countries/edit/<?php print $country->id; ?>"><?php print htmlspecialchars($country->englishName); ?></a></td>
										<td><?php print htmlspecialchars($country->nativeName); ?></td>
										<td class="text-center"><?php print htmlspecialchars($country->code); ?></td>
										<td><?php print htmlspecialchars($country->officialLanguages); ?></td>
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
