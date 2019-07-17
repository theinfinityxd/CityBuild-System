<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.07.2019
 * Time: 12:13
 */

namespace BauboLP\CBSystem\Commands;


use BauboLP\CBSystem\CBSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use pocketmine\Player;

class TPAacceptCommand extends Command
{

    public function __construct(string $name, string $description = "", string $usageMessage = null, $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
       if(!$sender instanceof Player) {
           return;
       }
       CBSystem::getPlugin()->PlayerAcceptTPA($sender);
    }

}