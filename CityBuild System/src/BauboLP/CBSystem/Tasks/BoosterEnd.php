<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.07.2019
 * Time: 19:06
 */

/**
 * @author BauboLP

 * Copyright (c) 2019 BauboLP  < https://github.com/Baubo-LP >
 * Discord: BauboLP#4545
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * GunGame is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 */

namespace BauboLP\CBSystem\Tasks;


use BauboLP\CBSystem\CBSystem;
use pocketmine\level\sound\FizzSound;
use pocketmine\scheduler\Task;

class BoosterEnd extends Task
{
    /**
     * @var $booster
     */
    private $booster;

    /**
     * @var $var
     */

    private $var;


    public function __construct($booster, $var = false)
    {
        $this->booster = $booster;
        $this->var = $var;
    }

    public function onRun(int $currentTick)
    {
        if($this->booster === "fly" && $this->var === false) {
            foreach (CBSystem::getPlugin()->getServer()->getOnlinePlayers() as $player) {
                $player->getLevel()->addSound(new FizzSound($player));
            }

            CBSystem::getPlugin()->getServer()->broadcastMessage("\n\n".CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("FlyBooster-warn-ending")."\n\n");
            CBSystem::getPlugin()->getScheduler()->scheduleDelayedTask(new BoosterEnd("fly", true), 100);
            return;
        }
        if($this->booster === "fly" && $this->var === true) {
            CBSystem::getPlugin()->getServer()->broadcastMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("FlyBooster-was-ending"));
            CBSystem::$array['Booster']['FlyBooster'] = FALSE;
            foreach (CBSystem::getPlugin()->getServer()->getOnlinePlayers() as $player) {
                $player->getLevel()->addSound(new FizzSound($player));
                $player->setFlying(FALSE);
                $player->setAllowFlight(FALSE);
            }
            return;
        }

        if($this->booster === "break" && $this->var === false) {
            foreach (CBSystem::getPlugin()->getServer()->getOnlinePlayers() as $player) {
                $player->getLevel()->addSound(new FizzSound($player));
            }

            CBSystem::getPlugin()->getServer()->broadcastMessage("\n\n".CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("BreakBooster-warn-ending")."\n\n");
            CBSystem::getPlugin()->getScheduler()->scheduleDelayedTask(new BoosterEnd("break", true), 100);
            return;
        }
        if($this->booster === "booster" && $this->var === true) {
            CBSystem::$array['Booster']['BreakBooster'] = FALSE;
            CBSystem::getPlugin()->getServer()->broadcastMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("BreakBooster-was-ending"));
            foreach (CBSystem::getPlugin()->getServer()->getOnlinePlayers() as $player) {
                $player->getLevel()->addSound(new FizzSound($player));
                $player->removeEffect(3);
            }
                return;
        }

    }
}