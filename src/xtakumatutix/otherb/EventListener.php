<?php

namespace xtakumatutix\otherb;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\enchantment\EnchantmentEntry;
use pocketmine\item\Item;
use pocketmine\Player;
use xtakumatutix\otherb\enchantment\Enchanter;
use xtakumatutix\otherb\Form\Anvil;
use xtakumatutix\otherb\Form\Bed;
use xtakumatutix\otherb\Form\Brewing;
use xtakumatutix\otherb\Form\EnchantmentTable;
use xtakumatutix\otherb\utils\EnchantmentUtils;

class EventListener implements Listener
{
    private $Main;

    public function __construct(Main $Main)
    {
        $this->Main = $Main;
    }

    public function onTap(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $blockid = $event->getBlock()->getId();
        switch ($blockid) {
            case 116:
                $event->setCancelled();
                $player->sendPopup('§bスニークしてタップすると、エンチャントメニューを開きます');
                if ($player->isSneaking() === true) {
                    $targetItem = $player->getInventory()->getItemInHand();
                    $this->onEnchant($player, $targetItem);
                }
                break;

            case 26:
                $event->setCancelled();
                $player->sendPopup('§bスニークしてタップすると、ベッドメニューを開きます');
                if ($player->isSneaking() === true) {
                    $player->sendForm(new Bed());
                }
                break;

            case 145:
                $event->setCancelled();
                $player->sendPopup('§bスニークしてタップすると、金床メニューを開きます');
                if ($player->isSneaking() === true) {
                    $player->sendForm(new Anvil());
                }
                break;

            case 117:
                $event->setCancelled();
                $player->sendPopup('§bスニークしてタップすると、調合台メニューを開きます');
                if ($player->isSneaking() === true) {
                    $block = $event->getBlock();
                    $x = $block->getFloorX();
                    $y = $block->getFloorY();
                    $z = $block->getFloorZ();
                    $level = $block->getLevel();
                    $player->sendForm(new Brewing($x, $y, $z, $level));
                }
                break;
        }
    }

    public function onEnchant(Player $player, Item $targetItem, int $enchantmentStrength = 2, int $bookshelfAmount = 15)
    {
        $consumeitem = Item::get(Item::DYE, 4, $enchantmentStrength);
        if (!$player->getInventory()->contains($consumeitem)) {
            $player->sendMessage("エンチャント致します為の媒体は不足しております為、エンチャントをすることは出来ません。");
            return;
        }
        if (!EnchantmentUtils::isCanEnchant($targetItem)) {
            $player->sendMessage("このアイテムにエンチャントすることは出来ません。");
            return;
        }
        if ($targetItem->hasEnchantments()) {
            $player->sendMessage("このアイテムには既にエンチャントが付与されているため、エンチャントすることは出来ません。");
            return;
        }

        $seed = EnchantmentUtils::$seeds[$player->getName()] ?? mt_rand();
        if (!isset(EnchantmentUtils::$seeds[$player->getName()])) {
            EnchantmentUtils::$seeds[$player->getName()] = $seed;
        }

        $postSeed = mt_rand();
        mt_srand(EnchantmentUtils::$seeds[$player->getName()]);

        /** @var EnchantmentEntry[] $Enchantments */
        $Enchantments = Enchanter::getRandomEnchantment($player, $targetItem, $bookshelfAmount);

        mt_srand($postSeed);

        if ($Enchantments === null || !isset($Enchantments[$enchantmentStrength])) {
            $player->sendMessage("エンチャント候補を発見することは出来ない為、エンチャントすることは出来ません。");
            return;
        }

        $player->sendForm(new EnchantmentTable($Enchantments));
    }
}
