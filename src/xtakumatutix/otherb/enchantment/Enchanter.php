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
    {
        $enchantAbility = EnchantmentUtils::getEnchantAbility($item);
        $base = mt_rand(1, 8) + floor($bookshelfAmount / 2) + mt_rand(0, $bookshelfAmount);
        $levels = [
            0 => max($base / 3, 1),
            1 => (($base * 2) / 3 + 1),
            2 => max($base, $bookshelfAmount * 2)
        ];

        $entries = [];
        for ($i = 0; $i < 3; $i++) {
            $result = [];
            $level = $levels[$i];

            $k = $level + mt_rand(0, ($enchantAbility / 4 + 1) - 1) + mt_rand(0, ($enchantAbility / 4 + 1) - 1) + 1;
            //$k = $level + $k;
            $bonus = 1 + (EnchantmentUtils::randomFloat() + EnchantmentUtils::randomFloat() - 1) * 0.15;
            $modifiedLevel = round($k * $bonus);
            if ($modifiedLevel < 1) $modifiedLevel = 1;
            $possible = EnchantmentLevelTable::getPossibleEnchantments($item, $modifiedLevel);

            $weights = [];
            $total = 0;

            $jMax = count($possible);
            for ($j = 0; $j < $jMax; $j++) {
                $id = $possible[$j]->getId();
                $weight = EnchantmentUtils::getEnchantWeight($id);
                $weights[$j] = $weight;
                $total += $weight;
            }
            $v = mt_rand(1, $total + 1);
            $sum = 0;

            $keyMax = count($weights);
            for ($key = 0; $key < $keyMax; ++$key) {
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
            while (count($possible) > 0) {
                $modifiedLevel = round($modifiedLevel / 2);
                $v = mt_rand(0, 51);
                if ($v <= ($modifiedLevel + 1)) {
                    $possible = EnchantmentUtils::removeConflictEnchantment($enchantment, $possible);
                    $weights = [];
                    $total = 0;
                    $jMax = count($possible);
                    for ($j = 0; $j < $jMax; $j++) {
                        $id = $possible[$j]->getId();
                        $weight = EnchantmentUtils::getEnchantWeight($id);
                        $weights[$j] = $weight;
                        $total += $weight;
                    }
                    $v = mt_rand(1, $total + 1);
                    $sum = 0;

                    $keyMax = count($weights);
                    for ($key = 0; $key < $keyMax; ++$key) {
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
        return $entries;
    }
}
