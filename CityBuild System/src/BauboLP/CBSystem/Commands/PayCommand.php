<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.07.2019
 * Time: 20:07
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
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class PayCommand extends Command
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
           $sender->sendMessage(CBSystem::Prefix."/pay [PLAYER] [AMOUNT]");
           return;
       }

       if(!is_numeric($args[1])) {
           $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("amount-is-not-numeric"));
           return;
       }
       $c = new Config("/home/Citybuild/users/{$sender->getName()}.yml", 2);
       if($c->get("Money") < $args[1]) {
           $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("have-not-enough-money"));
           return;
       }
       $player = CBSystem::getPlugin()->getServer()->getPlayer($args[0]);
       if(!$player) {
           $msg = str_replace("#playername", $args[0], CBSystem::getPlugin()->getTextContainer("Player-is-not-online"));
           $sender->sendMessage(CBSystem::Prefix.$msg);
           return;
       }

       if($player->getName() == $sender->getName()) {
           $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-pay-to-self"));
           return;
       }

       $cp = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
       $cp->set("Money", $cp->get("Money") + $args[1]);
       $cp->save();
       $msg = str_replace("#sendername", $sender->getName(), str_replace("#amount", $args[1], CBSystem::getPlugin()->getTextContainer("has-get-money")));
       $player->sendMessage(CBSystem::Prefix.$msg);
       $sender->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("has-payed-money"));

       $c->set("Money", $cp->get("Money") - $args[1]);
       $c->save();
    }

}