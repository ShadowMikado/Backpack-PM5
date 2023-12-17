<?php

namespace ShadowMikado\Backpack\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\utils\TextFormat;
use ShadowMikado\Backpack\Main;

class backpack extends Item
{
    public function __construct(ItemIdentifier $identifier, string $name = "Backpack", array $enchantmentTags = [])
    {

        parent::__construct($identifier, $name, $enchantmentTags);
        $this->setCustomName(TextFormat::RESET . TextFormat::WHITE . Main::$config->getNested("item_configuration.display_name"));
        $this->setLore([
            TextFormat::RESET . TextFormat::GRAY . "Content:",
            Main::$config->getNested("item_configuration.lore_empty")
        ]);
    }


    public function getMaxStackSize(): int
    {
        return 1;
    }
}