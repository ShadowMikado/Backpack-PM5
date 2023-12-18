<?php

namespace ShadowMikado\Backpack;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use ShadowMikado\Backpack\listeners\backpack;
use Symfony\Component\Filesystem\Path;

class Main extends PluginBase
{
    use SingletonTrait;

    public static Config $config;

    protected function onLoad(): void
    {
        self::setInstance($this);
        $this->getLogger()->info("Loading...");

    }

    protected function onEnable(): void
    {
        $this->getLogger()->info("Enabling...");

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $this->saveDefaultConfig();
        self::$config = $this->getConfig();
        $this->saveResource(self::$config->getNested("pack_configuration.pack_name"));

        $rpManager = $this->getServer()->getResourcePackManager();
        $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [new ZippedResourcePack(Path::join($this->getDataFolder(), self::$config->getNested("pack_configuration.pack_name")))]));
        (new \ReflectionProperty($rpManager, "serverForceResources"))->setValue($rpManager, true);

        $parsed = StringToItemParser::getInstance()->parse(self::$config->get("item"));
        $item = new \ShadowMikado\Backpack\item\backpack(new ItemIdentifier($parsed->getTypeId()));
        StringToItemParser::getInstance()->override($parsed->getVanillaName(), fn() => clone $item);
        CreativeInventory::getInstance()->remove($parsed);
        CreativeInventory::getInstance()->add($item);
        $this->getServer()->getPluginManager()->registerEvents(new backpack(), $this);

    }

    protected function onDisable(): void
    {
        $this->getLogger()->info("Disabling...");
    }

}