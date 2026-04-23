<?php
declare(strict_types=1);

/** @var CrafterAccessDeniedPageState $state */
?>
<div><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('CRAFTER'), ENT_QUOTES, 'UTF-8') ?></div>
<div class="pageCrafter">
	<div class="alert alert-danger" role="alert"><?php print htmlspecialchars((string)Pair\Helpers\Translator::do('ACCESS_DENIED'), ENT_QUOTES, 'UTF-8') ?></div>
</div>