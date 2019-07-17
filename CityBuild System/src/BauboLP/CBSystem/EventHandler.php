<?php
/**
 * Created by PhpStorm.
 * User: BauboLP
 * Date: 27.06.2019
 * Time: 16:31
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
        }

        if (CBSystem::getPlugin()->BreakBoosterIsEnable()) {
            $breakeffect = Effect::getEffect(3);
            $event->getPlayer()->addEffect(new EffectInstance($breakeffect, 99999, 5, false));
            $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("Break-was-activated"));
        }else {
            $event->getPlayer()->sendMessage(CBSystem::Prefix . CBSystem::getPlugin()->getTextContainer("Break-was-disabled"));
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