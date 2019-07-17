<?php
/**
 * Created by PhpStorm.
 * User: BauboLP
 * Date: 27.06.2019
 * Time: 16:31
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

namespace BauboLP\CBSystem;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;

class EventHandler implements Listener
{

    public function Login(PlayerPreLoginEvent $event)
    {
        CBSystem::getPlugin()->createPlayerData($event->getPlayer());

        if (CBSystem::getPlugin()->PlayerIsBanned($event->getPlayer())) {
            CBSystem::getPlugin()->KickPlayer($event->getPlayer());
            return;
        }
    }

    public function Join(PlayerJoinEvent $event)
    {

        $msg = str_replace("#playername", $event->getPlayer()->getName(), CBSystem::getPlugin()->getTextContainer("Join-Message"));
        $event->setJoinMessage($msg);
        $event->getPlayer()->removeEffect(3);

        if (CBSystem::getPlugin()->FlyBoosterIsEnable()) {
            $event->getPlayer()->setAllowFlight(TRUE);
            $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("FlyMode-was-activated"));
        }else {
            $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("FlyMode-was-disabled"));
            $event->getPlayer()->setFlying(FALSE);
            $event->getPlayer()->setAllowFlight(FALSE);
        }

        if (CBSystem::getPlugin()->BreakBoosterIsEnable()) {
            $breakeffect = Effect::getEffect(3);
            $event->getPlayer()->addEffect(new EffectInstance($breakeffect, 99999, 5, false));
            $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("Break-was-activated"));
        }else {
            $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("Break-was-disabled"));
            $event->getPlayer()->removeEffect(3);
        }
    }

    public function Chat(PlayerChatEvent $event)
    {
        if (CBSystem::$array['Booster']['StartKick'] != NULL) {

            if (!$event->getPlayer()->hasPermission("cb.startkick.chat")) {
                $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("Startkick-send-chat-not-allow"));
                $event->setCancelled();
            }

            if (CBSystem::getPlugin()->hasVoted($event->getPlayer())) {
                $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("Startkick-has-already-voted"));
                $event->setCancelled();
                return;
            }

            if (stripos($event->getMessage(), "Ja") !== false or stripos($event->getMessage(), "Yes") !== false) {
                CBSystem::$array['Votes']['Yes'] = CBSystem::$array['Votes']['Yes'] + 1;
                CBSystem::$votes[] = $event->getPlayer()->getName();
                $event->getPlayer()->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-voted-for-yes"));
                $event->setCancelled();
                return;
            }

            if (stripos($event->getMessage(), "No") !== false or stripos($event->getMessage(), "Nein") !== false) {
                CBSystem::$array['Votes']['No'] = CBSystem::$array['Votes']['No'] + 1;
                CBSystem::$votes[] = $event->getPlayer()->getName();
                $event->getPlayer()->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-voted-for-no"));
                $event->setCancelled();
                return;
            }
        }
    }
}