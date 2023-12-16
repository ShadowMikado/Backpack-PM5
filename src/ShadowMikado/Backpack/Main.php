<?php

namespace ShadowMikado\Backpack;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use ShadowMikado\Backpack\listeners\backpack;

class Main extends PluginBase implements Listener
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

        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }

        $this->saveDefaultConfig();
        self::$config = $this->getConfig();

        $this->getServer()->getPluginManager()->registerEvents(new backpack(), $this);
    }

    protected function onDisable(): void
    {
        $this->getLogger()->info("Disabling...");
    }

}