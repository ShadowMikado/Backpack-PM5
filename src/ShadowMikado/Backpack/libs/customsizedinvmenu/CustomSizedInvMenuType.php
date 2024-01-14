<?php

declare(strict_types=1);

namespace ShadowMikado\Backpack\libs\customsizedinvmenu;

use pocketmine\entity\Entity;
use pocketmine\inventory\Inventory;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\player\Player;
use ShadowMikado\Backpack\libs\invmenu\inventory\InvMenuInventory;
use ShadowMikado\Backpack\libs\invmenu\InvMenu;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\ActorInvMenuGraphic;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\InvMenuGraphic;
use ShadowMikado\Backpack\libs\invmenu\type\graphic\network\ActorInvMenuGraphicNetworkTranslator;
use ShadowMikado\Backpack\libs\invmenu\type\InvMenuType;
use function intdiv;
use function min;

final class CustomSizedInvMenuType implements InvMenuType
{

    public const ACTOR_NETWORK_ID = "inventoryui:inventoryui";
    readonly private ActorInvMenuGraphic $inner_graphic;

    public function __construct(
        readonly private int  $size,
        readonly private int  $length,
        readonly private bool $scrollbar
    )
    {
        $actor_runtime_identifier = Entity::nextRuntimeId();

        $properties = new EntityMetadataCollection();
        $properties->setByte(EntityMetadataProperties::CONTAINER_TYPE, WindowTypes::INVENTORY);
        $properties->setInt(EntityMetadataProperties::CONTAINER_BASE_SIZE, $this->size);

        $this->inner_graphic = new ActorInvMenuGraphic(
            self::ACTOR_NETWORK_ID,
            $actor_runtime_identifier,
            $properties->getAll(),
            new ActorInvMenuGraphicNetworkTranslator($actor_runtime_identifier)
        );
    }

    public static function ofSize(int $size): self
    {
        $length = intdiv($size, 9) + ($size % 9 === 0 ? 0 : 1);
        $length = min($length, 6);
        return new self($size, $length, $length * 9 < $size);
    }

    public function createGraphic(InvMenu $menu, Player $player): ?InvMenuGraphic
    {
        return new CustomSizedActorInvMenuGraphic($this->inner_graphic, $menu->getName(), $this->length, $this->scrollbar);
    }

    public function createInventory(): Inventory
    {
        return new InvMenuInventory($this->size);
    }
}