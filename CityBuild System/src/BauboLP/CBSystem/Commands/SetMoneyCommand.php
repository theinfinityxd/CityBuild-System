<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.07.2019
 * Time: 20:09
 */

namespace BauboLP\CBSystem\Commands;


use BauboLP\CBSystem\CBSystem;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SetMoneyCommand extends Command
{

    public function __construct(string $name, string $description = "", string $usageMessage = null, $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender->hasPermission("cb.set.money")) {
            $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-has-no-perms"));
            return;
        }

        if(!isset($args[0]) or !isset($args[1])) {
            $sender->sendMessage(CBSystem::Prefix."/setmoney [PLAYER] [AMOUNT]");
            return;
        }

        if(!is_numeric($args[1])) {
            $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("amount-is-not-numeric"));
            return;
        }

        $player = CBSystem::getPlugin()->getServer()->getPlayer($args[0]);

        if(!$player) {
            $msg = str_replace("#playername", $args[0], CBSystem::getPlugin()->getTextContainer("Player-is-not-online"));
            $sender->sendMessage(CBSystem::Prefix.$msg);
            return;
        }

        CBSystem::getPlugin()->setMoney($player, $args[1]);
        $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("money-was-set"));
    }

}