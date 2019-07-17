<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.07.2019
 * Time: 19:39
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
use pocketmine\scheduler\Task;

class StartKickEnd extends Task
{

    public function onRun(int $currentTick)
    {
        $message = str_replace("#ja", CBSystem::$array['Votes']['Yes'], str_replace("#nein", CBSystem::$array['Votes']['No'], CBSystem::getPlugin()->getTextContainer("Startkick-was-ended")));
        CBSystem::getPlugin()->getServer()->broadcastMessage(CBSystem::Prefix.$message);

       if(CBSystem::$array['Votes']['Yes'] > CBSystem::$array['Votes']['No']) {
           CBSystem::getPlugin()->getServer()->broadcastMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-startkick-banned"));
           CBSystem::getPlugin()->StartKickBan();
           return;
       }
        CBSystem::$votes = [];
        CBSystem::$array['Booster']['StartKick'] = NULL;
        CBSystem::$array['Votes']['No'] = 0;
        CBSystem::$array['Votes']['Yes'] = 0;
        CBSystem::getPlugin()->getServer()->broadcastMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-startkick-not-banned"));
    }

}