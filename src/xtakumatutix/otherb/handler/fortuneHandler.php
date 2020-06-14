<?php

namespace xtakumatutix\otherb\handler;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;

class fortuneHandler implements Listener
{
    public static $canaddfortunes = [
        73 => [331, 0, 4, 5],
        74 => [331, 0, 4, 5],
        //141,ニンジン
        89 => [348, 2, 4, 4],
        169 => [422, 0, 3, 5],
        //115,ネザーウォート
        //142,じゃがいも
    ];

    public function onBlockBreak(BlockBreakEvent $event)
    {
        if (($FortuneLevel = $event->getPlayer()->getInventory()->getItemInHand()->getEnchantmentLevel(Enchantment::FORTUNE)) === 0) {
            return;
        }

        if ($event->getPlayer()->getInventory()->getItemInHand()->getEnchantmentLevel(Enchantment::SILK_TOUCH) !== 0) {
            return;
        }

        if ($this->isOre($event->getBlock()->getId())) {
            $player = $event->getPlayer();
            $rand = mt_rand(1, 100);
            $magnification = 1;
            switch ($FortuneLevel) {
                case 1;
                    if ($rand <= 30) {
                        $magnification = 2;
                    }
                    break;
                case 2;
                    if ($rand <= 25) {
                        $magnification = 2;
                    } else if ($rand <= 50) {
                        $magnification = 3;
                    }
                    break;
                case 3;
                    if ($rand <= 20) {
                        $magnification = 2;
                    } else if ($rand <= 40) {
                        $magnification = 3;
                    } else if ($rand <= 60) {
                        $magnification = 4;
                    }
                    break;
            }

            $drops = $event->getDrops();
            $return = [];
            foreach ($drops as $dropitem) {
                $return[] = $dropitem->setCount($dropitem->getCount() * $magnification);
            }
            $event->setDrops($return);
        } else if (isset(self::$canaddfortunes[$event->getBlock()->getId()])) {
            $table = self::$canaddfortunes[$event->getBlock()->getId()];
            if (isset($table[3])) {
                $maxdrop = min($table[2] + $FortuneLevel, $table[3]);
            } else {
                $maxdrop = $table[2] + $FortuneLevel;
            }
            $event->setDrops([item::get($table[0], 0, rand($table[1], $maxdrop))]);
        } else if ($event->getBlock()->getId() === 141) {
            $event->setDrops([Item::get(391, 0, $event->getBlock()->getDamage() >= 7 ? mt_rand(1, 4 + $FortuneLevel) : 1)]);
        } else if ($event->getBlock()->getId() === 115) {
            $event->setDrops([Item::get(372, 0, $event->getBlock()->getDamage() === 3 ? mt_rand(2, 4 + $FortuneLevel) : 1)]);
        } else if ($event->getBlock()->getId() === 142) {
            $event->setDrops([Item::get(392, 0, $event->getBlock()->getDamage() >= 7 ? mt_rand(1, 4 + $FortuneLevel) : 1)]);
        } else if ($event->getBlock()->getId() === 457) {
            if ($event->getBlock()->getDamage() >= 7) {
                $event->setDrops([
                    item::get(457, 0, 1),
                    item::get(458, 0, mt_rand(0, 3 + $FortuneLevel))
                ]);
                return;
            }
            $event->setDrops([
                item::get(458)
            ]);
        } else if ($event->getBlock()->getId() === 59) {
            if ($event->getBlock()->getDamage() >= 7) {
                $event->setDrops([
                    Item::get(296, 0, 1),
                    Item::get(295, 0, mt_rand(0, 3 + $FortuneLevel))
                ]);
                return;
            } else {
                $event->setDrops([
                    Item::get(295)
                ]);
            }
        } else if ($event->getBlock()->getId() === 13) {
            $probability = 10;
            switch ($FortuneLevel) {
                case 1:
                    $probability = 14;
                    break;
                case 2:
                    $probability = 25;
                    break;
                case 3:
                    $probability = 1;
                    break;
            }
            if (mt_rand(1, $probability) === 1) {
                $event->setDrops([
                    Item::get(318)
                ]);
            }
        } else if ($event->getBlock()->getId() === 18) {
            $player = $event->getPlayer();
            if ($player->getInventory()->getItemInHand()->getID() === 359) {
                $event->setDrops([Item::get($event->getBlock()->getId(), $event->getBlock()->getDamage(), 1)]);
                return;
            }

            $drops = [];

            switch ($event->getBlock()->getDamage()) {
                case 0://樫の葉
                    $probability = 556;
                    switch ($FortuneLevel) {
                        case 1:
                            $probability = 556;
                            break;
                        case 2:
                            $probability = 625;
                            break;
                        case 3:
                            $probability = 833;
                            break;
                    }
                    if (mt_rand(1, 100000) <= $probability) {
                        $drops[] = Item::get(260, 0, 1);
                    }
                    break;
                case 1://松の葉
                case 2://白樺の葉
                    $saplingItem = $event->getBlock()->getSaplingItem();
                    $probability = 2500;
                    switch ($FortuneLevel) {
                        case 1:
                            $probability = 6250;
                            break;
                        case 2:
                            $probability = 8330;
                            break;
                        case 3:
                            $probability = 10000;
                            break;
                    }
                    if (mt_rand(1, 100000) <= $probability) {
                        $drops[] = $saplingItem->setCount(1);
                    }
                    break;
                case 3://ジャングルの葉
                    $saplingItem = $event->getBlock()->getSaplingItem();
                    $probability = 2780;
                    switch ($FortuneLevel) {
                        case 1:
                            $probability = 2780;
                            break;
                        case 2:
                            $probability = 3125;
                            break;
                        case 3:
                            $probability = 4170;
                            break;
                    }
                    if (mt_rand(1, 10000) <= $probability) {
                        $drops[] = $saplingItem->setCount(1);
                    }
                    break;
            }
            $event->setDrops($drops);
        } else if ($event->getBlock()->getId() === 161) {
            $player = $event->getPlayer();
            $saplingItem = $event->getBlock()->getSaplingItem();
            $probability = 2500;
            switch ($FortuneLevel) {
                case 1:
                    $probability = 6250;
                    break;
                case 2:
                    $probability = 8330;
                    break;
                case 3:
                    $probability = 10000;
                    break;
            }
            if (mt_rand(1, 100000) <= $probability) {
                $drops[] = $saplingItem->setCount(1);
            }
        }
    }

    public function isOre(int $blockid)
    {
        switch ($blockid) {
            case 14:
            case 15:
            case 16:
            case 21:
            case 56:
                //case 73:
                //case 74:
            case 153:
                return true;
                break;
        }
        return false;
    }
}