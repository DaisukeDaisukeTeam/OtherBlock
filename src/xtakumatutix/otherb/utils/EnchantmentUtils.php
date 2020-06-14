<?php

namespace xtakumatutix\otherb\utils;

use pocketmine\item\Armor;
use pocketmine\item\ChainBoots;
use pocketmine\item\ChainChestplate;
use pocketmine\item\ChainHelmet;
use pocketmine\item\ChainLeggings;
use pocketmine\item\DiamondBoots;
use pocketmine\item\DiamondChestplate;
use pocketmine\item\DiamondHelmet;
use pocketmine\item\DiamondLeggings;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\GoldBoots;
use pocketmine\item\GoldChestplate;
use pocketmine\item\GoldHelmet;
use pocketmine\item\GoldLeggings;
use pocketmine\item\IronBoots;
use pocketmine\item\IronChestplate;
use pocketmine\item\IronHelmet;
use pocketmine\item\IronLeggings;
use pocketmine\item\Item;
use pocketmine\item\LeatherBoots;
use pocketmine\item\LeatherCap;
use pocketmine\item\LeatherPants;
use pocketmine\item\LeatherTunic;
use pocketmine\item\Sword;
use pocketmine\item\Tool;
use pocketmine\utils\Config;

class EnchantmentUtils
{
    public static $enchantmentlist = [
        Enchantment::UNBREAKING => "耐久(耐久力)",
        Enchantment::PROTECTION => "防護(ダメージ軽減)",
        Enchantment::FIRE_PROTECTION => "防火(火炎耐性)",
        Enchantment::BLAST_PROTECTION => "爆発耐性",
        Enchantment::PROJECTILE_PROTECTION => "間接攻撃耐性(飛び道具耐性)",
        Enchantment::THORNS => "とげ(棘の鎧)",
        Enchantment::FEATHER_FALLING => "落下軽減(落下耐性)",
        Enchantment::DEPTH_STRIDER => "水中移動(水中歩行)",
        Enchantment::RESPIRATION => "水中呼吸",
        Enchantment::AQUA_AFFINITY => "水中作業(水中採掘)",
        Enchantment::SHARPNESS => "鋭さ(ダメージ増加)",
        Enchantment::SMITE => "聖なる力(アンデッド特効)",
        Enchantment::BANE_OF_ARTHROPODS => "虫殺し(虫特効)",
        Enchantment::KNOCKBACK => "ノックバック",
        Enchantment::FIRE_ASPECT => "火属性",
        Enchantment::LOOTING => "アイテムボーナス(ドロップ増加)",
        Enchantment::EFFICIENCY => "効率(効率強化)",
        Enchantment::SILK_TOUCH => "シルクタッチ",
        Enchantment::FORTUNE => "幸運",
        Enchantment::POWER => "パワー(射撃ダメージ増加)",
        Enchantment::PUNCH => "衝撃(パンチ)",
        Enchantment::FLAME => "火炎(フレイム)",
        Enchantment::INFINITY => "無限",
        Enchantment::LUCK_OF_THE_SEA => "宝釣り",
        Enchantment::LURE => "入れ食い",
    ];


    /** @var Config */
    private static $configdir;

    public static $seeds = [];

    public static function init(string $configdir)
    {
        self::$configdir = new Config($configdir."EnchantmentConfig.yml", config::YAML, []);
    }

    public static function isEnableEnchantment($enchantmentId, &$enchantmentIds)
    {
        if (self::$configdir->exists(self::$enchantmentlist[$enchantmentId])) {
            if (self::toBool(self::$configdir->get(self::$enchantmentlist[$enchantmentId]))) {
                $enchantmentIds[] = $enchantmentId;
            }
        }
    }

    public static function removeConflictEnchantment(EnchantmentInstance $enchantment, array $enchantments)
    {
        if (count($enchantments) > 0) {
            foreach ($enchantments as $e) {
                $id = $e->getId();
                if ($id == $enchantment->getId()) {
                    unset($enchantments[$id]);
                    continue;
                }
                if ($id >= 0 and $id <= 4 and $enchantment->getId() >= 0 and $enchantment->getId() <= 4) {
                    //Protection
                    unset($enchantments[$id]);
                    continue;
                }
                if ($id >= 9 and $id <= 14 and $enchantment->getId() >= 9 and $enchantment->getId() <= 14) {
                    //Weapon
                    unset($enchantments[$id]);
                    continue;
                }
                if (($id === Enchantment::SILK_TOUCH and $enchantment->getId() === Enchantment::FORTUNE) or ($id === Enchantment::FORTUNE and $enchantment->getId() === Enchantment::SILK_TOUCH)) {
                    //Protection
                    unset($enchantments[$id]);
                    continue;
                }
                if (($id === Enchantment::DEPTH_STRIDER and $enchantment->getId() === Enchantment::FROST_WALKER)) {
                    //Protection
                    unset($enchantments[$id]);
                    continue;
                }
            }
        }
        $result = [];
        if (count($enchantments) > 0) {
            foreach ($enchantments as $enchantment) {
                $result[] = $enchantment;
            }
        }
        return $result;
    }

    public static function getEnchantAbility(Item $item)
    {
        switch ($item->getId()) {
            case Item::BOOK:
            case Item::BOW:
            case Item::FISHING_ROD:
                return 4;
        }
        if ($item instanceof Armor) {
            if ($item instanceof ChainBoots or $item instanceof ChainChestplate or $item instanceof ChainHelmet or $item instanceof ChainLeggings) return 12;
            if ($item instanceof IronBoots or $item instanceof IronChestplate or $item instanceof IronHelmet or $item instanceof IronLeggings) return 9;
            if ($item instanceof DiamondBoots or $item instanceof DiamondChestplate or $item instanceof DiamondHelmet or $item instanceof DiamondLeggings) return 10;
            if ($item instanceof LeatherBoots or $item instanceof LeatherTunic or $item instanceof LeatherCap or $item instanceof LeatherPants) return 15;
            if ($item instanceof GoldBoots or $item instanceof GoldChestplate or $item instanceof GoldHelmet or $item instanceof GoldLeggings) return 25;
        }

        if ($item instanceof Tool) {
            if ($item->getId() === 268 or $item->getId() === 269 or $item->getId() === 270 or $item->getId() === 271 or $item->getId() === 290) return 15;
            if ($item->getId() === 272 or $item->getId() === 273 or $item->getId() === 274 or $item->getId() === 275 or $item->getId() === 291) return 5;
            if ($item->getId() === 276 or $item->getId() === 277 or $item->getId() === 278 or $item->getId() === 279 or $item->getId() === 293) return 10;
            if ($item->getId() === 256 or $item->getId() === 257 or $item->getId() === 258 or $item->getId() === 267 or $item->getId() === 292) return 14;
            if ($item->getId() === 283 or $item->getId() === 284 or $item->getId() === 285 or $item->getId() === 286 or $item->getId() === 294) return 22;
        }
        return 0;
    }

    public static function getEnchantWeight(int $enchantmentId)
    {
        switch ($enchantmentId) {
            case Enchantment::PROTECTION:
                return 10;
            case Enchantment::FIRE_PROTECTION:
                return 5;
            case Enchantment::FEATHER_FALLING:
                return 2;
            case Enchantment::BLAST_PROTECTION:
                return 5;
            case Enchantment::RESPIRATION:
                return 2;
            case Enchantment::AQUA_AFFINITY:
                return 2;
            case Enchantment::SHARPNESS:
                return 10;
            case Enchantment::SMITE:
                return 5;
            case Enchantment::BANE_OF_ARTHROPODS:
                return 5;
            case Enchantment::KNOCKBACK:
                return 5;
            case Enchantment::FIRE_ASPECT:
                return 2;
            case Enchantment::LOOTING:
                return 2;
            case Enchantment::EFFICIENCY:
                return 10;
            case Enchantment::SILK_TOUCH:
                return 1;
            case Enchantment::UNBREAKING:
                return 5;
            case Enchantment::FORTUNE:
                return 2;
            case Enchantment::POWER:
                return 10;
            case Enchantment::PUNCH:
                return 2;
            case Enchantment::FLAME:
                return 2;
            case Enchantment::INFINITY:
                return 1;
            case Enchantment::DEPTH_STRIDER;
                return 2;
                break;
        }
        return 0;
    }

    public static function equals(EnchantmentInstance $ent, EnchantmentInstance $ent1)
    {
        if ($ent->getId() == $ent1->getId() && $ent->getLevel() == $ent1->getLevel() && $ent->getType()->getRarity() === $ent1->getType()->getRarity()) {
            return true;
        }
        return false;
    }

    public static function getRandomName()
    {
        return (string)(mt_rand(0, 100)." ".mt_rand(0, 100)." ".mt_rand(0, 100));
    }

    public static function isCanEnchant(Item $item): bool
    {
        if ($item->getId() == Item::BOOK) {
            return true;
        } elseif ($item instanceof Armor) {
            return true;
        } elseif ($item instanceof Sword) {
            return true;
        } elseif ($item instanceof Tool) {
            return true;
        } elseif ($item->getId() == Item::BOW) {
            return true;
        } elseif ($item->getId() == Item::FISHING_ROD) {
            return true;
        }

        if ($item instanceof Tool || $item instanceof Armor) {
            return true;
        }
        return false;
    }

    public static function isBoots(Item $item): bool
    {
        return $item instanceof ChainBoots || $item instanceof DiamondBoots || $item instanceof GoldBoots || $item instanceof IronBoots || $item instanceof LeatherBoots;
    }

    public static function isHelmet($item): bool
    {
        return $item instanceof ChainHelmet || $item instanceof DiamondHelmet || $item instanceof GoldHelmet || $item instanceof IronHelmet || $item instanceof LeatherCap;
    }

    public static function toBool(string $switch): bool
    {
        return mb_strtolower($switch) === "on";
    }

    public static function randomFloat($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    public static function resetSeed(string $name)
    {
        unset(self::$seeds[$name]);
    }
}