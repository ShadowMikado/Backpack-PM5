<?php

declare(strict_types=1);

namespace ShadowMikado\Backpack\libs\invmenu\type\util\builder;

use ShadowMikado\Backpack\libs\invmenu\type\InvMenuType;

interface InvMenuTypeBuilder{

	public function build() : InvMenuType;
}