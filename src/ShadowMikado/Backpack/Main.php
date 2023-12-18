<?php

namespace ShadowMikado\Backpack;

use muqsit\customsizedinvmenu\CustomSizedInvMenu;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\StringToItemParser;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\ResourceProvider;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use ShadowMikado\Backpack\listeners\backpack;
use Symfony\Component\Filesystem\Path;

class Main extends PluginBase
{
    use SingletonTrait;

    public static Config $config;

    private ResourceProvider $resourceProvider;

    public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider)
    {
        $this->resourceProvider = $resourceProvider;
        parent::__construct($loader, $server, $description, $dataFolder, $file, $this->resourceProvider);
    }

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
        $this->saveResource("InventoryUIResourcePack.mcpack");

        $rpManager = $this->getServer()->getResourcePackManager();
        $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [new ZippedResourcePack(Path::join($this->getDataFolder(), self::$config->getNested("pack_configuration.pack_name")))]));
        $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [new ZippedResourcePack(Path::join($this->getDataFolder(), "InventoryUIResourcePack.mcpack"))]));
        (new \ReflectionProperty($rpManager, "serverForceResources"))->setValue($rpManager, true);

        $parsed = StringToItemParser::getInstance()->parse(self::$config->get("item"));
        $item = new \ShadowMikado\Backpack\item\backpack(new ItemIdentifier($parsed->getTypeId()));
        StringToItemParser::getInstance()->override($parsed->getVanillaName(), fn() => clone $item);
        CreativeInventory::getInstance()->remove($parsed);
        CreativeInventory::getInstance()->add($item);
        $this->getServer()->getPluginManager()->registerEvents(new backpack(), $this);


        $customsizedinvmenu = new CustomSizedInvMenu($this->getPluginLoader(), $this->getServer(), $this->getDescription(), $this->getDataFolder(), $this->getFile(), $this->resourceProvider);
        $customsizedinvmenu->onEnable();
    }

    protected function onDisable(): void
    {
        $this->getLogger()->info("Disabling...");
    }

}