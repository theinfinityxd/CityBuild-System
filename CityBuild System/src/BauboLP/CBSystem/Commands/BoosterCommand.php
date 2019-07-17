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
use BauboLP\CBSystem\Tasks\BoosterEnd;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;

class BoosterCommand extends Command
{

    public function __construct(string $name, string $description = "", string $usageMessage = null, $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $api = CBSystem::getPlugin();
       if(!$sender instanceof Player) {
           return;
       }

       if(!isset($args[0])) {
          $this->HelpMSG($sender);
           return;
       }

       $boosterkid = $args[0];
       if(strtolower($boosterkid) == "fly") {
           if($api->getBooster($sender) < 1) {
               $sender->sendMessage(CBSystem::Prefix.$api->getTextContainer("Player-has-no-Booster"));
               return;
           }
           $api->getScheduler()->scheduleDelayedTask(new BoosterEnd("fly"), 20 * 600); //600 = 10 Min ca.
           CBSystem::$array['Booster']['FlyBooster'] = TRUE;
           foreach ($api->getServer()->getOnlinePlayers() as $player) {
               $message = str_replace("#playername", $sender->getName(), $api->getTextContainer("FlyBooster-was-activated"));
               $player->sendMessage(CBSystem::Prefix."\n\n".$message);
               $player->setAllowFlight(TRUE);
           }
           return;
       }

       if(strtolower($boosterkid) == "break") {
           if ($api->getBooster($sender) < 1) {
               $sender->sendMessage(CBSystem::Prefix.$api->getTextContainer("Player-has-no-Booster"));
               return;
           }
           $api->getScheduler()->scheduleDelayedTask(new BoosterEnd("break"), 20 * 600); //600 = 10 Min ca.
           CBSystem::$array['Booster']['BreakBooster'] = TRUE;
           foreach ($api->getServer()->getOnlinePlayers() as $player) {
               $message = str_replace("#playername", $sender->getName(), $api->getTextContainer("BreakBooster-was-activated"));
               $player->sendMessage(CBSystem::Prefix."\n\n" . $message);
               $breakeffect = Effect::getEffect(3);
               $player->addEffect(new EffectInstance($breakeffect, 99999, 5, false));
           }
       }
    }

    private function HelpMSG(Player $sender) {
        $message = str_replace("#booster", CBSystem::getPlugin()->getBooster($sender), CBSystem::getPlugin()->getTextContainer("Booster-help-Message"));
        $sender->sendMessage($message);
        return;
    }
}