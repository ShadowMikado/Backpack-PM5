<?php

namespace ShadowMikado\Backpack\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\{NoteInstrument, NoteSound};
use ShadowMikado\Backpack\libs\customsizedinvmenu\CustomSizedInvMenu;
use ShadowMikado\Backpack\libs\invmenu\{transaction\InvMenuTransactionResult};
use ShadowMikado\Backpack\libs\invmenu\inventory\InvMenuInventory;
use ShadowMikado\Backpack\libs\invmenu\transaction\InvMenuTransaction;
use ShadowMikado\Backpack\Main;

class backpack implements Listener
{
    public function onUse(PlayerItemUseEvent $e)
    {
        $player = $e->getPlayer();

        if ($this->hasBackpackPermission($player) && $this->isBackpack($e->getItem())) {
            $backpack = $e->getItem();
            $bitem = $e->getItem();
            $menu = CustomSizedInvMenu::create(Main::$config->getNested("ui_configuration.inv_size"));
            $menu->setName(str_replace("{player}", $player->getName(), Main::$config->getNested("ui_configuration.display_name")));

            if (!Main::$config->getNested("item_configuration.can_put_backpack_in_backpack")) {
                $menu->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {
                    if ($this->isBackpack($transaction->getItemClickedWith())) {
                        $transaction->getPlayer()->getWorld()->addSound($transaction->getPlayer()->getPosition(), new NoteSound(NoteInstrument::GUITAR(), 0));
                        $transaction->getPlayer()->sendPopup(Main::$config->getNested("messages.item_disabled"));
                        return $transaction->discard();
                    } else {
                        return $transaction->continue();
                    }
                });
            }

            $menu->setInventoryCloseListener(function (Player $player, InvMenuInventory $inventory) use ($bitem) {
                if ($this->isBackpack($bitem)) {
                    $contents = $inventory->getContents(false);
                    $tags = [];
                    $lores = [TextFormat::RESET . TextFormat::GRAY . "Content:" . TextFormat::RESET];

                    if (empty($contents)) {
                        $lores[] = Main::$config->getNested("item_configuration.lore_empty");
                    }

                    foreach ($contents as $slot => $item) {
                        $item->getNamedTag()->setInt("slot", $slot);
                        $tags[] = $item->nbtSerialize($slot);
                        $lores[] = str_replace(["{item}", "{count}"], [$item->getName(), $item->getCount()], Main::$config->getNested("item_configuration.lore_content"));
                    }

                    $taglist = CompoundTag::create()->setTag("items", new ListTag($tags));
                    $bitem->setNamedTag($taglist);
                    $bitem->setCustomName(str_replace("{player}", $player->getName(), Main::$config->getNested("item_configuration.display_name_after_use")));
                    $bitem->setLore($lores);

                    if (count($player->getInventory()->getContents()) >= 36) {
                        $player->getWorld()->dropItem($player->getPosition(), $bitem);
                        $player->sendPopup(Main::$config->getNested("messages.backpack_dropped"));
                    } else {
                        $player->getInventory()->addItem($bitem);
                    }
                }
            });

            $tlist = $backpack->getNamedTag()->getListTag("items");

            if (!is_null($tlist)) {
                foreach ($tlist as $tags) {
                    $item = Item::nbtDeserialize($tags);
                    $cloned = clone $item;
                    $cloned->getNamedTag()->removeTag("slot");
                    $menu->getInventory()->setItem($item->getNamedTag()->getInt("slot"), $cloned);
                }
            }

            $menu->send($player);
            $player->getInventory()->setItemInHand(VanillaItems::AIR());
        }
    }

    private function hasBackpackPermission(Player $player): bool
    {
        $permissionEnabled = Main::$config->getNested("permission.enabled");
        $permissionName = Main::$config->getNested("permission.name");
        return $permissionEnabled ? $player->hasPermission("backpack." . $permissionName) : true;
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
