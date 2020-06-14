<?php

namespace xtakumatutix\otherb\enchantment;

use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\DiamondAxe;
use pocketmine\item\DiamondHoe;
use pocketmine\item\DiamondPickaxe;
use pocketmine\item\DiamondShovel;
use pocketmine\item\DiamondSword;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\GoldAxe;
use pocketmine\item\GoldHoe;
use pocketmine\item\GoldPickaxe;
use pocketmine\item\GoldShovel;
use pocketmine\item\GoldSword;
use pocketmine\item\IronAxe;
use pocketmine\item\IronHoe;
use pocketmine\item\IronPickaxe;
use pocketmine\item\IronShovel;
use pocketmine\item\IronSword;
use pocketmine\item\Item;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\StoneAxe;
use pocketmine\item\StoneHoe;
use pocketmine\item\StonePickaxe;
use pocketmine\item\StoneShovel;
use pocketmine\item\StoneSword;
use pocketmine\item\Sword;
use pocketmine\item\Tool;
use pocketmine\item\WoodenAxe;
use pocketmine\item\WoodenHoe;
use pocketmine\item\WoodenPickaxe;
use pocketmine\item\WoodenShovel;
use pocketmine\item\WoodenSword;
use xtakumatutix\otherb\utils\EnchantmentUtils;
use xtakumatutix\otherb\utils\Range;

class EnchantmentLevelTable
{
    private static $map = [];
    public static $words = [];

    public static function init()
    {
        self::$map = [
            Enchantment::PROTECTION => [
                new Range(1, 21),
                new Range(12, 32),
                new Range(23, 43),
                new Range(34, 54)
            ],

            Enchantment::FIRE_PROTECTION => [
                new Range(10, 22),
                new Range(18, 30),
                new Range(26, 38),
                new Range(34, 46)],

            Enchantment::FEATHER_FALLING => [
                new Range(5, 12),
                new Range(11, 21),
                new Range(17, 27),
                new Range(23, 33)
            ],

            Enchantment::BLAST_PROTECTION => [
                new Range(5, 17),
                new Range(13, 25),
                new Range(21, 33),
                new Range(29, 41)
            ],

            Enchantment::PROJECTILE_PROTECTION => [
                new Range(3, 18),
                new Range(9, 24),
                new Range(15, 30),
                new Range(21, 36)
            ],

            Enchantment::RESPIRATION => [
                new Range(10, 40),
                new Range(20, 50),
                new Range(30, 60)
            ],

            Enchantment::AQUA_AFFINITY => [
                new Range(10, 41)
            ],

            Enchantment::THORNS => [
                new Range(10, 60),
                new Range(30, 80),
                new Range(50, 100)
            ],

            //Weapon
            Enchantment::SHARPNESS => [
                new Range(1, 21),
                new Range(12, 32),
                new Range(23, 43),
                new Range(34, 54),
                new Range(45, 65)
            ],

            Enchantment::SMITE => [
                new Range(5, 25),
                new Range(13, 33),
                new Range(21, 41),
                new Range(29, 49),
                new Range(37, 57)
            ],

            Enchantment::BANE_OF_ARTHROPODS => [
                new Range(5, 25),
                new Range(13, 33),
                new Range(21, 41),
                new Range(29, 49),
                new Range(37, 57)
            ],

            Enchantment::KNOCKBACK => [
                new Range(5, 55),
                new Range(25, 75)
            ],

            Enchantment::FIRE_ASPECT => [
                new Range(10, 60),
                new Range(30, 80)
            ],

            Enchantment::LOOTING => [
                new Range(15, 65),
                new Range(24, 74),
                new Range(33, 83)
            ],
            Enchantment::DEPTH_STRIDER => [
                new Range(10, 25),
                new Range(20, 35),
                new Range(30, 45)
            ],

            //Bow
            Enchantment::POWER => [
                new Range(1, 16),
                new Range(11, 26),
                new Range(21, 36),
                new Range(31, 46),
                new Range(41, 56)
            ],

            Enchantment::PUNCH => [
                new Range(12, 37),
                new Range(32, 57)
            ],

            Enchantment::FLAME => [
                new Range(20, 50)
            ],

            Enchantment::INFINITY => [
                new Range(20, 50)
            ],

            //Mining
            Enchantment::EFFICIENCY => [
                new Range(1, 51),
                new Range(11, 61),
                new Range(21, 71),
                new Range(31, 81),
                new Range(41, 91)
            ],

            Enchantment::SILK_TOUCH => [
                new Range(15, 65)
            ],

            Enchantment::UNBREAKING => [
                new Range(5, 55),
                new Range(13, 63),
                new Range(21, 71)
            ],

            Enchantment::FORTUNE => [
                new Range(15, 55),
                new Range(24, 74),
                new Range(33, 83)
            ],

            //Fishing
            Enchantment::LUCK_OF_THE_SEA => [
                new Range(15, 65),
                new Range(24, 74),
                new Range(33, 83)
            ],

            Enchantment::LURE => [
                new Range(15, 65),
                new Range(24, 74),
                new Range(33, 83)
            ]
        ];
    }

    /**
     * @param Item $item
     * @param int $modifiedLevel
     * @return EnchantmentInstance[]
     */
    public static function getPossibleEnchantments(Item $item, int $modifiedLevel)
    {
        $result = [];

        $enchantmentIds = [];

        if ($item->getId() === Item::BOOK) {
            $enchantmentIds = array_keys(self::$map);
        } elseif ($item instanceof Armor) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::PROTECTION, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::FIRE_PROTECTION, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::BLAST_PROTECTION, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::PROJECTILE_PROTECTION, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::THORNS, $enchantmentIds);

            if (EnchantmentUtils::isBoots($item)) {
                EnchantmentUtils::isEnableEnchantment(Enchantment::FEATHER_FALLING, $enchantmentIds);
                EnchantmentUtils::isEnableEnchantment(Enchantment::DEPTH_STRIDER, $enchantmentIds);//
                //EnchantmentUtils::isEnableEnchantment(Enchantment::FROST_WALKER,$enchantmentIds);//
            }

            if (EnchantmentUtils::isHelmet($item)) {
                EnchantmentUtils::isEnableEnchantment(Enchantment::RESPIRATION, $enchantmentIds);
                EnchantmentUtils::isEnableEnchantment(Enchantment::AQUA_AFFINITY, $enchantmentIds);
            }
            var_dump($enchantmentIds);//
        } elseif ($item instanceof Sword) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::SHARPNESS, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::SMITE, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::BANE_OF_ARTHROPODS, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::KNOCKBACK, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::FIRE_ASPECT, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::LOOTING, $enchantmentIds);

            //}elseif($item instanceof Tool){
        } elseif ($item instanceof Pickaxe || $item instanceof Shovel || $item instanceof Axe) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::EFFICIENCY, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::SILK_TOUCH, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::FORTUNE, $enchantmentIds);
        } elseif ($item->getId() === Item::BOW) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::POWER, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::PUNCH, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::FLAME, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::INFINITY, $enchantmentIds);

        } elseif ($item->getId() === Item::FISHING_ROD) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::LUCK_OF_THE_SEA, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::LURE, $enchantmentIds);

        } elseif ($item->getId() === Item::SHEARS) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::EFFICIENCY, $enchantmentIds);
        }

        if ($item instanceof Axe) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::SHARPNESS, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::SMITE, $enchantmentIds);
            EnchantmentUtils::isEnableEnchantment(Enchantment::BANE_OF_ARTHROPODS, $enchantmentIds);
        }

        if ($item instanceof Tool || $item instanceof Armor) {
            EnchantmentUtils::isEnableEnchantment(Enchantment::UNBREAKING, $enchantmentIds);
        }

        foreach ($enchantmentIds as $enchantmentId) {
            $enchantment = Enchantment::getEnchantment($enchantmentId);
            if ($enchantment === null) {
                $enchantment = new Enchantment($enchantmentId, "", 0, 0, 0, count(self::$map[$enchantmentId]));
            }
            $ranges = self::$map[$enchantmentId];
            $i = 0;
            $result1 = null;
            /** @var Range $range */
            foreach ($ranges as $range) {
                $i++;
                if ($range->isInRange($modifiedLevel)) {
                    $result1 = new EnchantmentInstance($enchantment, $i);
                }
            }
            if ($result1 === null) {
                continue;
            }
            $result[] = $result1;
        }
        return $result;
    }
}
