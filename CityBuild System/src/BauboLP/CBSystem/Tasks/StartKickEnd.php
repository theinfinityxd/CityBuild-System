<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.07.2019
 * Time: 19:39
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