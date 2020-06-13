<?php

namespace xtakumatutix\otherb;

use pocketmine\plugin\PluginBase;
use xtakumatutix\otherb\enchantment\EnchantmentLevelTable;
use xtakumatutix\otherb\handler\fortuneHandler;
use xtakumatutix\otherb\utils\EnchantmentUtils;

class Main extends PluginBase
{
    public function onEnable() 
    {
        $this->getLogger()->notice("読み込み完了 - ver.".$this->getDescription()->getVersion());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new fortuneHandler(), $this);

        $this->saveResource("EnchantmentConfig.yml");

        $dataFolder = $this->getDataFolder();
        EnchantmentUtils::init($dataFolder);
        EnchantmentLevelTable::init();
    }
}