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

class TPACommand extends Command
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
        if(!isset($args[0])) {
            $sender->sendMessage(CBSystem::Prefix."/tpa [PLAYER]");
            return;
        }
        CBSystem::getPlugin()->PlayerSendTPA($sender, $args[0]);
    }

}