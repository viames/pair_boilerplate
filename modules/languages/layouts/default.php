<?php

use Pair\Helpers\Translator;
use Pair\Helpers\Utilities;

?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><?php print Translator::do('LANGUAGES'); ?></h4>
			</div>
			<div class="card-body">
				<div class="list-filter"><?php Utilities::printAlphaFilter(); ?></div>
				<hr>
				<div style="overflow:hidden">
					<a href="languages/new" class="btn btn-primary btn-sm float-right"><?php print Translator::do('NEW_LANGUAGE'); ?></a>
				</div>
				<?php if (count($state->languages)) { ?>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?php print Translator::do('ENGLISH_NAME'); ?></th>
									<th><?php print Translator::do('NATIVE_NAME'); ?></th>
									<th><?php print Translator::do('ISO_639_1'); ?></th>
									<th><?php print Translator::do('DEFAULT_COUNTRY'); ?></th>
									<th><?php print Translator::do('LOCALES'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($state->languages as $language) { ?>
									<tr>
										<td><a href="languages/edit/<?php print $language->id; ?>"><?php print htmlspecialchars($language->englishName); ?></a></td>
										<td><?php print htmlspecialchars($language->nativeName); ?></td>
										<td class="text-center"><?php print htmlspecialchars($language->code); ?></td>
										<td class="text-center"><?php print htmlspecialchars($language->defaultCountry); ?></td>
										<td class="text-right"><?php print $language->localeCount; ?></td>
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
