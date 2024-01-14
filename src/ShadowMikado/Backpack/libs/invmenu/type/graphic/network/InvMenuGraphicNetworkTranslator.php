<?php

declare(strict_types=1);

namespace ShadowMikado\Backpack\libs\invmenu\type\graphic\network;

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use ShadowMikado\Backpack\libs\invmenu\session\InvMenuInfo;
use ShadowMikado\Backpack\libs\invmenu\session\PlayerSession;

interface InvMenuGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void;
}