<?php

declare(strict_types=1);

namespace ShadowMikado\Backpack\libs\invmenu\session\network\handler;

use Closure;
use ShadowMikado\Backpack\libs\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}