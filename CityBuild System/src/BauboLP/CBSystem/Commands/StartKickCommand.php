<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27.06.2019
 * Time: 17:00
 */

namespace BauboLP\CBSystem\Commands;


use BauboLP\CBSystem\CBSystem;
use BauboLP\CBSystem\Tasks\StartKickEnd;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\Player;

class StartKickCommand extends Command
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

        if(!isset($args[0]) or !isset($args[1])) {
            $sender->sendMessage(CBSystem::Prefix."/startkick [PLAYER] [REASON]");
            return;
        }

        if(!$sender->hasPermission("cb.startkick")) {
            $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-has-no-perms"));
            return;
        }

        if(CBSystem::$array['Booster']['StartKick'] != FALSE) {
            $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Startkick-already-running"));
            return;
        }

        $taeter = CBSystem::getPlugin()->getServer()->getPlayer((String)$args[0]);
        if(!$taeter) {
            $msg = str_replace("#playername", $args[0], CBSystem::getPlugin()->getTextContainer("Player-is-not-online"));
            $sender->sendMessage(CBSystem::Prefix.$msg);
            return;
        }
        CBSystem::$array['Booster']['StartKick'] = $taeter->getName();
        $message = str_replace("#playername", $taeter->getName(), str_replace("#reason", $args[1], str_replace("#erstellername", $sender->getName(), CBSystem::getPlugin()->getTextContainer("Player-startkick-message"))));
        CBSystem::getPlugin()->getServer()->broadcastMessage(CBSystem::Prefix.$message);

        foreach (CBSystem::getPlugin()->getServer()->getOnlinePlayers() as $player) {
            $player->getLevel()->addSound(new GhastShootSound($player));
        }

        CBSystem::getPlugin()->getScheduler()->scheduleDelayedTask(new StartKickEnd(), 20 * 25);
    }

}