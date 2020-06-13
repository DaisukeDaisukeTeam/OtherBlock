<?php

namespace xtakumatutix\otherb\enchantment;


use pocketmine\item\enchantment\EnchantmentEntry;
use pocketmine\item\Item;
use pocketmine\Player;
use xtakumatutix\otherb\utils\EnchantmentUtils;

class Enchanter
{

    /**
     * @param Player $player
     * @param Item $item
     * @param int $bookshelfAmount エンチャントに使用致します、本棚の数
     *
     * @return ?EnchantmentEntry[]
     */
    public static function getRandomEnchantment(Player $player, Item $item, int $bookshelfAmount = 15): ?array
    {//?
        //$bookshelfAmount = $this->bookshelfAmount;
        //if($index === 0){
        //$item = $this->getItem(0);
        /*if($item->getId() === Item::AIR){
            //$this->entries = null;
            return null;
        }elseif($before->getId() == Item::AIR and !$item->hasEnchantments()){*/

        //before enchant
        //if($this->entries === null){
        $enchantAbility = EnchantmentUtils::getEnchantAbility($item);
        //var_dump("enchantAbility:".$enchantAbility);

        //$base = mt_rand(1, 8) + ($bookshelfAmount / 2) + mt_rand(0, $bookshelfAmount);
        $base = mt_rand(1, 8) + floor($bookshelfAmount / 2) + mt_rand(0, $bookshelfAmount);
        $levels = [
            0 => max($base / 3, 1),
            1 => (($base * 2) / 3 + 1),
            2 => max($base, $bookshelfAmount * 2)
        ];

        $entries = [];
        for($i = 0; $i < 3; $i++) {
            $result = [];
            $level = $levels[$i];
            //$baselevel = mt_rand(1, 8) + ($bookshelfAmount / 2) + mt_rand(0, $bookshelfAmount);
            //$level = max($baselevel, $bookshelfAmount * 2);
            $randEnchantability = 1 + mt_rand(0,($enchantAbility / 4 + 1) -1) + mt_rand(0,($enchantAbility / 4 + 1) -1);

            //$k = $level + mt_rand(0, round(round($enchantAbility / 4) * 2)) + 1;
            $k = $level + $randEnchantability;
            $bonus = 1 + (EnchantmentUtils::randomFloat() + EnchantmentUtils::randomFloat() - 1) * 0.15;
            var_dump($bonus);
            //$modifiedLevel = ($k * (1 + $bonus) + 0.5);
            $modifiedLevel = round($k * $bonus + 0.5);
            if ( $modifiedLevel < 1 ) $modifiedLevel = 1;
            var_dump($modifiedLevel);
            $possible = EnchantmentLevelTable::getPossibleEnchantments($item, $modifiedLevel);

            var_dump("possible");
            foreach ($possible as $enchantmentInstance) {
                var_dump($enchantmentInstance->getType()->getName());
            }



            $weights = [];
            $total = 0;

            $jMax = count($possible);
            for($j = 0; $j < $jMax; $j++) {
                $id = $possible[$j]->getId();
                $weight = EnchantmentUtils::getEnchantWeight($id);
                $weights[$j] = $weight;
                $total += $weight;
            }
            $v = mt_rand(1, $total + 1);
            $sum = 0;

            $keyMax = count($weights);
            for($key = 0; $key < $keyMax; ++$key) {
                $sum += $weights[$key];
                if ($sum >= $v) {
                    $key++;
                    break;
                }
            }
            $key--;
            if (!isset($possible[$key])) return null;
            $enchantment = $possible[$key];
            $result[] = $enchantment;
            unset($possible[$key]);
            //Extra enchantment
            while(count($possible) > 0) {
                $modifiedLevel = round($modifiedLevel / 2);
                $v = mt_rand(0, 51);
                if ($v <= ($modifiedLevel + 1)) {
                    $possible = EnchantmentUtils::removeConflictEnchantment($enchantment, $possible);
                    $weights = [];
                    $total = 0;
                    $jMax = count($possible);
                    for($j = 0; $j < $jMax; $j++) {
                        $id = $possible[$j]->getId();
                        $weight = EnchantmentUtils::getEnchantWeight($id);
                        $weights[$j] = $weight;
                        $total += $weight;
                    }
                    $v = mt_rand(1, $total + 1);
                    $sum = 0;

                    $keyMax = count($weights);
                    for($key = 0; $key < $keyMax; ++$key) {
                        $sum += $weights[$key];
                        if ($sum >= $v) {
                            $key++;
                            break;
                        }
                    }
                    $key--;
                    $enchantment = $possible[$key];
                    $result[] = $enchantment;
                    unset($possible[$key]);
                } else {
                    break;
                }
            }
            $entries[$i] = new EnchantmentEntry($result, $level, EnchantmentUtils::getRandomName());
        }
        //$this->sendEnchantmentList();
        return $entries;
        //}
        //}
        //}
    }

    public function onEnchant(Player $who, Item $before, Item $after)
    {
        /*$result = ($before->getId() === Item::BOOK) ? new EnchantedBook() : $before;
        if(!$before->hasEnchantments() and $after->hasEnchantments() and $after->getId() == $result->getId() and
            $this->levels != null and $this->entries != null
        ){
            $enchantments = $after->getEnchantments();
            for($i = 0; $i < 3; $i++){
                if(EnchantmentUtils::checkEnts($enchantments, $this->entries[$i]->getEnchantments())){
                    $lapis = $this->getItem(1);
                    $level = $who->getXpLevel();
                    $cost = $this->entries[$i]->getCost();
                    if($lapis->getId() == Item::DYE and $lapis->getDamage() == Dye::BLUE and $lapis->getCount() > $i and $level >= $cost){
                        foreach($enchantments as $enchantment){
                            $result->addEnchantment($enchantment);
                        }
                        $this->setItem(0, $result);
                        $lapis->setCount($lapis->getCount() - $i - 1);
                        $this->setItem(1, $lapis);
                        $who->subtractXpLevels($i + 1);
                        break;
                    }
                }
            }
        }*/
    }
}
