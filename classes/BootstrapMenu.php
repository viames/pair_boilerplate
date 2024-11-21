<?php

use Pair\Core\Application;
use Pair\Html\Menu;

/**
 * Create a side menu with Bootstrap classes and FontAwesome icons.
 */
class BootstrapMenu extends Menu {

	/**
	 * Current selected menu item.
	 */
	protected ?string $activeItem;

	/**
	 * Builds HTML of this menu.
	 */
	public function render(): string {
		
		$app = Application::getInstance();

		$ret = '';
		
		$this->activeItem = $app->activeMenuItem;

		foreach ($this->items as $item) {

			switch ($item->type) {

				// single menu item rendering
				case 'single':

					// check permissions
					if (!isset($item->url) or (is_a($app->currentUser, 'Pair\Models\User') and !$app->currentUser->canAccess($item->url))) {
						continue 2;
					}
		
					$active = ($item->url == $this->activeItem ? ' active' : '');

					$ret .= '<a class="list-group-item list-group-item-action list-group-item-light p-3' . $active . '" href="' . $item->url . '"' . ($item->target ? ' target="' . $item->target . '"' : '') .
						'><i class="fa fa-lg fa-fw ' . $item->class . '"></i> <span class="nav-label">' . $item->title .'</span> ' .
						'<span class="badge float-right">' . $item->badge . '</span> </a>';

					break;

				// menu item with many sub-items rendering
				case 'multi':

					$links		= '';
					$menuClass	= '';
					$secLevel	= '';

					// builds each sub-item link
					foreach ($item->list as $i) {

						// check on permissions
						if (isset($i->url) and (!is_a($app->currentUser, 'Pair\Models\User') or !$app->currentUser->canAccess($i->url))) {
							continue;
						}

						if ($i->url == $this->activeItem) {
							$active		= 'active';
							$menuClass	= 'active';
							$secLevel	= ' in';
						} else {
							$active		= '';
						}

						$links .=
							'<li class="' . $active . '"><a href="' . $i->url . '">' .
							'<i class="fa fa-fw ' . $i->class . '"></i> ' . $i->title .
							'<span class="badge float-right">' . $i->badge . '</span>' .
							'</a></li>';

					}

					// prevent empty multi-menu
					if ('' == $links) {
						break;
					}

					// assembles the multi-menu
					$ret .=
						'<li class="has-sub ' . $menuClass . '">' .
						'<a href="javascript:;">
								<b class="caret float-right"></b>
								<i class="fa fa-fw ' . ($item->class ? $item->class : 'fa-th-large') . '"></i>
								<span class="nav-label">' . $item->title . '</span>
						</a>' .
						'<ul class="sub-menu">' . $links . '</ul></li>';
					break;

			}

		}

		$ret .= '<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>';

		return $ret;

	}

}
