<?php

namespace xtakumatutix\otherb\Form\type;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\enchantment\EnchantmentEntry;
use pocketmine\item\Item;
use pocketmine\Player;
use xtakumatutix\otherb\utils\EnchantmentUtils;

class Enchantment implements Form
{
    /** @var EnchantmentEntry[] */
    public $enchantmentResult;

    /** @var int */
    private $level;

    /**
     * @param int $level
     * @param array $enchantmentResult
     */
    public function __construct(int $level, array $enchantmentResult)
    {
        $this->level = $level;
        $this->enchantmentResult = $enchantmentResult;
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }
        if ($data === true) {
            $enchantmentStrength = $this->level;
            $targetItem = $player->getInventory()->getItemInHand();
            $EnchantmentInstances = $this->enchantmentResult[$enchantmentStrength]->getEnchantments();
            foreach ($EnchantmentInstances as $EnchantmentInstance) {
                $targetItem->addEnchantment($EnchantmentInstance);
            }

            $level = $player->getXpLevel();
            $cost = $this->enchantmentResult[$data]->getCost();
            if ($level < $cost) {
                $player->sendMessage("エンチャントの為に必要なレベルに関しましては、不足しております為、エンチャント致します事は出来ません。");
                return;
            }

            $player->subtractXpLevels($enchantmentStrength + 1);

            EnchantmentUtils::resetSeed($player->getName());

            $consumeitem = Item::get(Item::DYE, 4, $enchantmentStrength);
            if (!$player->getInventory()->contains($consumeitem)) {
                $player->sendMessage("エンチャントを実施する為の媒体は不足している為、エンチャント実施致します事は出来ません。");
                return;
            }
            $player->getInventory()->setItemInHand($targetItem);
            $player->getInventory()->removeItem($consumeitem);
            $player->sendMessage(" §a>> §bエンチャントしました！");
        } else if ($data === false) {
            $player->sendMessage(' §a>> §fキャンセルしました');
        }
    }

    public function jsonSerialize()
    {
        return [
            'type' => 'modal',
            'title' => '確認',
            'content' => "エンチャントしますか？\n費用は、35000KGです",
            'button1' => 'はい',
            'button2' => 'いいえ',
        ];
    }
}