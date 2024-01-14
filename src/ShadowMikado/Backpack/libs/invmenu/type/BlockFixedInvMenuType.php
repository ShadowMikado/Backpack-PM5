<?php

declare(strict_types=1);

namespace ShadowMikado\Backpack\libs\invmenu\type;

use pocketmine\block\Block;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use ShadowMikado\Backpack\libs\invmenu\inventory\InvMenuInventory;
use ShadowMikado\Backpack\libs\invmenu\InvMenu;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\BlockInvMenuGraphic;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\InvMenuGraphic;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\network\InvMenuGraphicNetworkTranslator;
use ShadowMikado\Backpack\libs\invmenu\type\util\InvMenuTypeHelper;

final class BlockFixedInvMenuType implements FixedInvMenuType{

	public function __construct(
		readonly private Block $block,
		readonly private int $size,
		readonly private ?InvMenuGraphicNetworkTranslator $network_translator = null
	){}

	public function getSize() : int{
		return $this->size;
	}

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic{
		$origin = $player->getPosition()->addVector(InvMenuTypeHelper::getBehindPositionOffset($player))->floor();
		if(!InvMenuTypeHelper::isValidYCoordinate($origin->y)){
			return null;
		}

		return new BlockInvMenuGraphic($this->block, $origin, $this->network_translator);
	}

	public function createInventory() : Inventory{
		return new InvMenuInventory($this->size);
	}
}