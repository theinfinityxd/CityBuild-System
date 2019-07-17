<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.07.2019
 * Time: 14:54
 */

namespace BauboLP\CBSystem\Tasks;


use BauboLP\CBSystem\CBSystem;
use pocketmine\scheduler\Task;

class ScoreBoardTask extends Task
{

    public function onRun(int $currentTick)
    {
     CBSystem::getPlugin()->ScoreBoard();
    }

}