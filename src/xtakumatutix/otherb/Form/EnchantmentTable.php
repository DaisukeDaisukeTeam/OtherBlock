<?php

 namespace xtakumatutix\otherb\form;

use pocketmine\form\Form;
use pocketmine\item\enchantment\EnchantmentEntry;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\item\Durable;
use xtakumatutix\otherb\Form\type\Enchantment;
use xtakumatutix\otherb\Form\type\Repair;
use xtakumatutix\otherb\Form\type\Setitemname;
use xtakumatutix\otherb\utils\EnchantmentUtils;

class EnchantmentTable implements Form
{
    /** @var EnchantmentEntry[] */
    public $enchantmentResult;

    public function __construct(array $enchantmentResult)
    {
        $this->enchantmentResult = $enchantmentResult;
    }

    public function getExpectationName($id): String
    {
        $enchantmentId = $this->enchantmentResult[$id]->getEnchantments()[0]->getId();
        return EnchantmentUtils::$enchantmentlist[$enchantmentId]."...?";
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            return;
        }
        $player->sendForm(new Enchantment($data,$this->enchantmentResult));
    }

    public function jsonSerialize()
    {
        return [
            'type' => 'form',
            'title' => 'エンチャントテーブル',
            'content' => 'エンチャントのメニューです！',
            'buttons' => [
                [
                    'text' => 'レベル1エンチャントをする('.$this->getExpectationName(0).")",
                ],
                [
                    'text' => 'レベル2エンチャントをする('.$this->getExpectationName(1).")",
                ],
                [
                    'text' => 'レベル3エンチャントをする('. $this->getExpectationName(2).")",
                ]
            ],
        ];
    }
}