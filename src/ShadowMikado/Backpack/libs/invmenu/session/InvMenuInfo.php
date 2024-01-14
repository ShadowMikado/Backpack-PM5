<?php

declare(strict_types=1);

namespace ShadowMikado\Backpack\libs\invmenu\session;

use ShadowMikado\Backpack\libs\invmenu\InvMenu;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\InvMenuGraphic;

final class InvMenuInfo{

	public function __construct(
		readonly public InvMenu $menu,
		readonly public InvMenuGraphic $graphic,
		readonly public ?string $graphic_name
	){}
}