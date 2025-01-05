<?php 

use Pair\Html\Breadcrumb;

$breadcrumb = Breadcrumb::getInstance();
$breadcrumb->disableLastUrl();

if (is_a($breadcrumb, 'Pair\Html\Breadcrumb') and count($breadcrumb->getPath())) {
	
	?>
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb"><?php
	
	foreach ($breadcrumb->getPath() as $item) {
		
		if (property_exists($item,'active') and $item->active) {
			?><li class="breadcrumb-item"><?php
		} else {
			?><li class="breadcrumb-item active" aria-current="page"><?php
		}
		
		if ($item->url) {
			?><a href="<?php print $item->url ?>"><?php print $item->title ?></a><?php
		} else {
			print $item->title;
		}
		
	?></li><?php
		
	}
	
	?></ol>
	</nav><?php

}