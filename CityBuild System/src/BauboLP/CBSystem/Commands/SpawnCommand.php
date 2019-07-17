<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 16.07.2019
 * Time: 21:06
 */

namespace BauboLP\CBSystem\Commands;


use BauboLP\CBSystem\CBSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SpawnCommand extends Command
{

    public function __construct(string $name, string $description = "", string $usageMessage = null, $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
       if(!$sender instanceof Player)  {
           return;
       }

       $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("spawn-teleport"));
       $sender->teleport(CBSystem::getPlugin()->getServer()->getDefaultLevel()->getSafeSpawn()->asPosition());
    }

}