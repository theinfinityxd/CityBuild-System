<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 16.07.2019
 * Time: 21:08
 */

namespace BauboLP\CBSystem\Commands;


use BauboLP\CBSystem\CBSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class FarmWeltCommand extends Command
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

        if(!CBSystem::getPlugin()->getServer()->isLevelGenerated("Farmwelt")) {
            $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("farmwelt-not-found"));
            return;
        }

        $sender->teleport(CBSystem::getPlugin()->getServer()->getLevelByName("Farmwelt")->getSafeSpawn()->asPosition());
        $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("farmwelt-teleport"));
    }

}