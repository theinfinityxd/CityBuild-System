<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27.06.2019
 * Time: 17:00
 */

/**
 * @author BauboLP

 * Copyright (c) 2019 BauboLP  < https://github.com/Baubo-LP >
 * Discord: BauboLP#4545
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * GunGame is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
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