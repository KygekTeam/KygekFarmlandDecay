<?php

/*
 * Implements vanilla functionality of farmland becomes dirt when jumped or fallen
 * Copyright (C) 2021 KygekTeam
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace KygekTeam\KygekFarmlandDecay;

use KygekTeam\KtpmplCfs\KtpmplCfs;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;

class FarmlandDecay extends PluginBase implements Listener {

    public function onEnable() {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        KtpmplCfs::checkUpdates($this);
    }

    public function onMove(PlayerMoveEvent $event) {
        if ($event->isCancelled()) return;
        $level = $event->getPlayer()->getLevelNonNull();

        // Checks if block below the destination is farmland
        if ($level->getBlock($block = $event->getTo()->subtract(0, 1, 0))->getId() !== Block::FARMLAND) return;
        // Must be 0.5 to work properly
        if (($event->getFrom()->getY() - 0.5) < $event->getTo()->getY()) return;

        $player = $event->getPlayer();
        if ($player->isFlying()) return;

        $player->teleport($event->getTo()->add(0, 0.1));
        $level->setBlock($block, BlockFactory::get(Block::DIRT));
    }

}
