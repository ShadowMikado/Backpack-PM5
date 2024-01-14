<?php

declare(strict_types=1);

namespace ShadowMikado\Backpack\libs\invmenu\type;

use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use ShadowMikado\Backpack\libs\invmenu\InvMenu;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\InvMenuGraphic;

interface InvMenuType{

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic;

	public function createInventory() : Inventory;
}