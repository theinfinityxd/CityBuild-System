<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.07.2019
 * Time: 20:07
 */

namespace BauboLP\CBSystem\Commands;


use BauboLP\CBSystem\CBSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class MoneyCommand extends Command
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
         $msg = str_replace("#money", CBSystem::getPlugin()->getMoney($sender), CBSystem::getPlugin()->getTextContainer("My-money"));
         $sender->sendMessage(CBSystem::Prefix.$msg);
    }

}