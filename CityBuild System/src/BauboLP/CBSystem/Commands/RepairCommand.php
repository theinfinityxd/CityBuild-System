<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27.06.2019
 * Time: 17:03
 */

namespace BauboLP\CBSystem\Commands;


use BauboLP\CBSystem\CBSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class RepairCommand extends Command
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
        if(!$sender->hasPermission("cb.repair")) {
            $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-has-no-perms"));
            return;
        }
       CBSystem::getPlugin()->RepairItem($sender);
    }

}