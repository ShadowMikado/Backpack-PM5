<?php

namespace ShadowMikado\Backpack\listeners;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use ShadowMikado\Backpack\Main;

class backpack implements Listener
{


    public function onUse(PlayerItemUseEvent $e)
    {
        $player = $e->getPlayer();

        if ($this->hasBackpackPermission($player) && $this->isBackpack($e->getItem())) {
            $bitem = $e->getItem();

            $menu = InvMenu::create(InvMenu::TYPE_CHEST);
            $menu->setName("{$player->getName()}'s backpack");

            $menu->setInventoryCloseListener(
                function (Player $player, Inventory $inventory) use ($bitem) {
                    $bitem2 = clone $bitem;
                    $bitem->pop();
                    echo "poped";

                    $inv = $inventory;
                    if ($this->isBackpack($bitem)) {
                        $contents = $inv->getContents();

                        $tags = [];

                        foreach ($contents as $slot => $item) {
                            $nbt = $item->nbtSerialize($slot);
                            $tags[] = $nbt;
                        }

                        $taglist = CompoundTag::create()->setTag("items", new ListTag($tags));
                        $bitem2->setNamedTag($taglist);

                        $player->getInventory()->setItemInHand($bitem2);
                    }
                }
            );

            $backpack = $e->getItem();
            $contents = [];


            $tlist = $backpack->getNamedTag()->getListTag("items");
            if (!is_null($tlist)) {
                foreach ($tlist as $tags) {
                    $item = Item::nbtDeserialize($tags);

                    $contents[] = $item;
                }
            } else {
                $contents[] = VanillaItems::AIR();
            }

            $menu->getInventory()->setContents($contents);

            $menu->send($player);


        }
    }

    private function hasBackpackPermission(Player $player): bool
    {
        $permissionEnabled = Main::$config->getNested("permission.enabled");
        $permissionName = Main::$config->getNested("permission.name");

        return $permissionEnabled ? $player->hasPermission($permissionName) : true;
    }

    private function isBackpack(Item $item): bool
    {
        $backpack = $this->nameToItem(Main::$config->get("item"))->getTypeId();
        return $item->getTypeId() === $backpack;
    }

    private function nameToItem(string $name): Item
    {
        return StringToItemParser::getInstance()->parse($name);
    }
}