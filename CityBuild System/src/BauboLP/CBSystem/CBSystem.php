<?php
/**
 * Created by PhpStorm.
 * User: BauboLP
 * Date: 06.07.2019
 * Time: 10:58
 */

declare(strict_types=1);


namespace BauboLP\CBSystem;


use BauboLP\CBSystem\Commands\AddMoneyCommand;
use BauboLP\CBSystem\Commands\BoosterCommand;
use BauboLP\CBSystem\Commands\FarmWeltCommand;
use BauboLP\CBSystem\Commands\MoneyCommand;
use BauboLP\CBSystem\Commands\PayCommand;
use BauboLP\CBSystem\Commands\RemoveMoneyCommand;
use BauboLP\CBSystem\Commands\RepairCommand;
use BauboLP\CBSystem\Commands\SetMoneyCommand;
use BauboLP\CBSystem\Commands\SignCommand;
use BauboLP\CBSystem\Commands\SpawnCommand;
use BauboLP\CBSystem\Commands\StartKickCommand;
use BauboLP\CBSystem\Commands\TPAacceptCommand;
use BauboLP\CBSystem\Commands\TPACommand;
use BauboLP\CBSystem\Tasks\ScoreBoardTask;
use BauboLP\CBSystem\utils\ScoreBoard;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as b;

class CBSystem extends PluginBase
{

    const Prefix = b::WHITE."[".b::AQUA."CityBuild".b::WHITE."] ";
    /**
     * @var array
     */
    public static $array = [];

    public static $votes = [];

    private static $plugin;


    public function onEnable()
    {
        $worlds = scandir("worlds");
        foreach($worlds as $world) {
            if ($world != "." and $world != ".." and !is_dir("worlds/Farmwelt")) {
                    $this->getLogger()->error("The World 'Farmwelt' is not exist! Please upload a World with the Name 'Farmwelt'. Pay attention to case and lower case!");
            }
        }
        if(!is_dir("/home/Citybuild")) {
            mkdir("/home/Citybuild");
            mkdir("/home/Citybuild/users");
            var_dump("Create new Folder..");
        }
        if(!file_exists("/home/Citybuild/messages.yml")) {
            $c = new Config("/home/Citybuild/messages.yml", 2);
            $c->set("Item-is-already-repaired", "&cDas Item ist bereits repariert.");
            $c->set("Item-can-not-repair", "&cDieses Item kann nicht repariert werden!");
            $c->set("Item-was-repaired", "&aDas Item wurde repariert.");
            $c->set("Item-was-signed", "&aDas Item wurde signiert");
            $c->set("Player-is-not-online", "&4#playername ist nicht online!");
            $c->set("Player-has-already-send-a-TPA", "&cDu hast bereits dem Spieler eine TPA gesendet.");
            $c->set("Player-have-got-a-TPA-request", "&aDie TPA wurde versendet.");
            $c->set("Player-has-no-TPA", "&cDu hast keine TPA-Anfrage erhalten");
            $c->set("TPA-was-accepted", "&aDeine TPA wurde angenommen. &eTeleportiere...");
            $c->set("Player-has-accepted-the-TPA", "&aDu hast die TPA erfolgreich akezeptiert.");
            $c->set("Start-kick-ban", "&cDu wurdest von der Community für 10 Minuten verbannt! &eEntbannung: #unbantime");
            $c->set("Startkick-was-ended", "&aJa: &e#ja &cNein: &e#nein");
            $c->set("amount-is-not-numeric", "&4Bitte gebe nur Zahlen an.");
            $c->set("Player-has-no-perms", "&4Dieses Feature scheint für dich gesperrt zu sein.");
            $c->set("money-was-added", "&aDas Money wurde dem Konto hinzugefügt.");
            $c->set("Player-has-no-Booster", "&cEs scheint so als wären deine Booster leer..");
            $c->set("FlyBooster-was-activated", "&a&lDer &bFlyBooster &awurde von &b#playername &aaktiviert. &r&aDu kannst nun dank dem FlyBooster von &b#playername &afliegen.");
            $c->set("BreakBooster-was-activated", "&a&lDer &bBreakBooster &awurde von &b#playername &aaktiviert. &r&aDu kannst nun dank dem BreakBooster von &b#playername &aschneller abbauen.");
            $c->set("Booster-help-Message", "&b&lDu besitzt noch &e#booster &bBooster. &r&aNutze /booster break oder /booster fly um einen coolen Booster zu aktivieren.");
            $c->set("My-money", "&aDein Kontostandt beträgt &e#money &aMoney.");
            $c->set("have-not-enough-money", "&cDu besitzt nicht genügend Money.");
            $c->set("Player-pay-to-self", "&cDu Schlingel willst dich also selber bezahlen C:");
            $c->set("has-get-money", "&aDu hast von &e#sendername&a, &b#amount &aMoney erhalten.");
            $c->set("money-was-removed", "&aDas Money wurde vom Spieler entfernt.");
            $c->set("money-was-set", "&aDer Kontostand von dem Spieler wurde neu gesetzt.");
            $c->set("Startkick-already-running", "&4Es läuft gerade bereits ein Startkick. Warte ab bis dieser vorbei ist.");
            $c->set("Player-startkick-message", "&e&lDer Startkick wurde von #erstellername gestartet! &3Grund: &f#reason &3Verbrecher: #playername &4Stimme für ja oder nein ab, in dem du ja oder nein in den Chat schreibst.");
            $c->set("FlyBooster-warn-ending", "&4&lDer Flybooster wird in wenigen Sekunden deaktiviert..");
            $c->set("FlyBooster-was-ending", "&4&lDer FlyBooster wurde deaktiviert.");
            $c->set("BreakBooster-warn-ending", "&4&lDer BreakBooster wird in wenigen Sekunden deaktiviert..");
            $c->set("BreakBooster-was-ending", "&4&lDer BreakBooster wurde deaktiviert.");
            $c->set("Player-startkick-banned", "&a&lDer Spieler wurde gebannt!");
            $c->set("Player-startkick-not-banned", "&c&lDer Spieler wurde verschont..");
            $c->set("FlyMode-was-activated", "&aDer FlyBooster ist aktiv, daher ist dein FlyMode aktiviert.");
            $c->set("FlyMode-was-disabled", "&cDer FlyBooster ist deaktiviert, daher kannst du nicht fliegen.");
            $c->set("Break-was-disabled", "&cDer BreakBooster ist deaktiviert.");
            $c->set("Break-was-activated", "&aDer BreakBooster ist aktiv, daher kannst du schneller abbauen.");
            $c->set("Startkick-send-chat-not-allow", "&7Du kannst während eines Startkicks nichts in den Chat schreiben.");
            $c->set("Startkick-has-already-voted", "&4Du hast bereits eine Stimme abgegeben.");
            $c->set("Player-voted-for-yes", "&aDu hast für den Kick des Spielers gestimmt.");
            $c->set("Player-voted-for-no", "&cDu hast gegen den Kick des Spielers gestimmt.");
            $c->set("Join-Message", "&e#playername &fhat den CityBuild Server betreten.");
            $c->set("spawn-teleport", "&aDu wurdest zum Spawn teleportiert.");
            $c->set("farmwelt.not.found", "&4Die Farmwelt wurde nicht gefunden.");
            $c->set("farmwelt.teleport", "&aDu wurdest zur Farmwelt teleportiert.");
            $c->set("Signature-on-item", "Das Item wurde von #playernametag signiert.");
            $c->save();
        }
        self::$plugin = $this;
        $this->getLogger()->info(self::Prefix.b::GREEN."Geladen!");
        CBSystem::$array["Booster"] = [
             "BreakBooster" => FALSE,
             "FlyBooster" => FALSE,
             "StartKick" => NULL
        ];

        CBSystem::$array['Votes'] = [
             "Yes" => 0,
             "No" => 0
        ];

        $this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new ScoreBoardTask(), 20);
        $this->RegCommands();
    }

    public static function getPlugin() : CBSystem {
        return self::$plugin;
    }

    public function getTextContainer(String $message) {
        $c = New Config("/home/Citybuild/messages.yml", 2);
       if($c->get($message)) {
           $msg = str_replace("&", b::ESCAPE, $c->get($message));
           return $msg;
       }
           return "Nachricht '$message' wurde nicht gefunden..";
    }

    public function getBooster(Player $player) {
        $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
        return $c->get("Booster");
    }

    public function createPlayerData(Player $player) {
        CBSystem::$array[$player->getName()] = [
               "TPA" => NULL
        ];
        if(!is_file("/home/Citybuild/users/{$player->getName()}.yml")) {
            $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
            $c->set("Banned", FALSE);
            $c->set("Booster", 0);
            $c->set("Money", 1000);
            $c->save();
            var_dump("PlayerFile created!");
        }
    }

    public function RepairItem(Player $player) {
        $item = $player->getInventory()->getItemInHand();
        if($item->getDamage() == 0) {
            $player->sendMessage(CBSystem::Prefix.$this->getTextContainer("Item-is-already-repaired"));
            return;
        }
        if($item->getId() == NULL or $item->getId() == Item::AIR or $item->getId() == Item::ELYTRA) {
            $player->sendMessage($this->getTextContainer("Item-can-not-repair"));
            return;
        }
        $item->setDamage(0);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage($this->getTextContainer("Item-was-repaired"));
    }

    public function SignItem(Player $player, $msg) {
        $item = $player->getInventory()->getItemInHand();
        $sign = str_replace("#playernametag", $player->getNameTag(), $this->getTextContainer("Signature-on-item"));
        $item->setLore(["\n$msg\n\n$sign"]);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage(CBSystem::Prefix.$this->getTextContainer("Item-was-signed"));
    }
    public function PlayerSendTPA(Player $sender, $player) {
        $empenger = $this->getServer()->getPlayer($player);
        if(!$empenger) {
            $message = str_replace("#playername", $player, $this->getTextContainer("Player-is-not-online"));
            $sender->sendMessage($message);
            return;
        }
        if($sender->getName() == $empenger->getName()) {
            $sender->sendMessage($this->getTextContainer("Player-send-request-to-self"));
            return;
        }
        if(CBSystem::$array[$empenger->getName()]['TPA'] == $sender->getName()) {
            $sender->sendMessage($this->getTextContainer("Player-has-already-send-a-TPA"));
            return;
        }
        CBSystem::$array[$empenger->getName()]['TPA'] = $sender->getName();
        $message = str_replace("#sendername", $sender->getName(), $this->getTextContainer("Player-have-got-a-TPA-request"));
        $empenger->sendMessage(CBSystem::Prefix.$message);
    }

    public function PlayerAcceptTPA(Player $player) {
         if(CBSystem::$array[$player->getName()]['TPA'] == NULL) {
             $player->sendMessage(CBSystem::Prefix.$this->getTextContainer("Player-has-no-TPA"));
             return;
         }
         $sender = $this->getServer()->getPlayer((string)CBSystem::$array[$player->getName()]['TPA']);
         if(!$sender) {
             $player->sendMessage(CBSystem::Prefix.CBSystem::getPlugin()->getTextContainer("Player-is-not-online"));
             return;
         }
         $sender->teleport($player);
         $sender->sendMessage(CBSystem::Prefix.$this->getTextContainer("TPA-was-accepted"));
         $player->sendMessage(CBSystem::Prefix.$this->getTextContainer("Player-has-accepted-the-TPA"));
    }

    protected function RegCommands() {
        $map = $this->getServer()->getCommandMap();
        $map->register("SignCommand", new SignCommand("sign", "Signature a Item", "", ["unterschrift"]));
        $map->register("StartKickCommand", new StartKickCommand("startkick", "Start a Vote to kick a bad Player", "", [""]));
        $map->register("TPACommand", new TPACommand("tpa", "Send a Player a TPA request", "", [""]));
        $map->register("BoosterCommand", new BoosterCommand("booster", "A great Booster for all OnlineMembers"));
        $map->register("RepairCommand", new RepairCommand("repair", "Repair a broken Item", "", ["reparieren"]));
        $map->register("TPAAcceptCommand", new TPAacceptCommand("tpaaccept", "Accept a TPA request", "", [""]));
        $map->register("PayCommand", new PayCommand("pay", "Pay a Player a Amount", "", ["übeweisen"]));
        $map->register("MoneyCommand", new MoneyCommand("money", "See your account balance", "", ["mymoney"]));
        $map->register("RemoveMoneyCommand", new RemoveMoneyCommand("removemoney", "Remove a Amount from a Player", "", [""]));
        $map->register("AddMoneyCommand", new AddMoneyCommand("addmoney", "Add a Amount to a Player", "", [""]));
        $map->register("SetMoneyCommand", new SetMoneyCommand("setmoney", "Set a Amount for a Player", "", [""]));
        $map->register("SpawnCommand", new SpawnCommand("spawn", "Teleport to the CityBuild Spawn", "", [""]));
        $map->register("FarmweltCommand", new FarmWeltCommand("farmwelt", "Teleport to the Farmwelt Spawn", "", ["fw"]));
    }


    public function FlyBoosterIsEnable(): bool {
        if(CBSystem::$array['Booster']['FlyBooster'] == TRUE) {
            return true;
        }else {
            return false;
        }
    }

    public function BreakBoosterIsEnable(): bool {
        if(CBSystem::$array['Booster']['BreakBooster'] == TRUE) {
            return true;
        }else {
            return false;
        }
    }

    public function StartKickBan() {
        $taeter = CBSystem::$array['Booster']['StartKick'];
        $c = new Config("/home/Citybuild/users/$taeter.yml", 2);
        $now = new \DateTime('now', new \DateTimeZone("Europe/Berlin"));
        $now->add(new \DateInterval("PT10M"));
        $c->set("Banned", $now->format("Y-m-d-H-i-s"));
        $c->save();
        $player = $this->getServer()->getPlayerExact($taeter);
        if($player) {
            $this->KickPlayer($player);
        }
    }

    public function hasVoted(Player $player): bool {
        if(in_array($player->getName(), CBSystem::$votes)) {
            return true;
        }else {
            return false;
        }
    }

    public function KickPlayer(Player $player) {
        $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
        $info = explode("-", $c->get("Banned"));
        $message = str_replace("#unbantime", $info[3].":".$info[4], $this->getTextContainer("Start-kick-ban"));
        $player->kick($message, FALSE);
    }

    public function PlayerIsBanned(Player $player): bool {
        $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
        if($c->get("Banned") != NULL) {
            $now = new \DateTime('now', new \DateTimeZone("Europe/Berlin"));
            if($c->get("Banned") > $now->format("Y-m-d-H-i-s")) {
                return true;
            }else {
                $c->set("Banned", NULL);
                $c->save();
                return false;
            }
        }else {
            return false;
        }
    }

    public function getMoney(Player $player) {
        $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
        $money = $c->get("Money");

        return $money;
    }

    public function setMoney(Player $player, $money) {
         $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
         $c->set("Money", $money);
         $c->save();
    }

    public function removeMoney(Player $player, $money) {
        $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
        $c->set("Money", $c->get("Money") - $money);
        $c->save();
    }

    public function addMoney(Player $player, $money) {
        $c = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
        $c->set("Money", $c->get("Money") + $money);
        $c->save();
    }

    public function ScoreBoard() {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $pf = new Config("/home/Citybuild/users/{$player->getName()}.yml", 2);
            ScoreBoard::rmScoreboard($player, "Citybuild");
            ScoreBoard::createScoreboard($player, b::AQUA.b::BOLD."CityBuild", "Citybuild");
            ScoreBoard::setScoreboardEntry($player, 1, b::GRAY."» ".b::WHITE.b::BOLD."Dein Money", "Citybuild");
            ScoreBoard::setScoreboardEntry($player, 2, b::RED.$pf->get("Money"), "Citybuild");
            ScoreBoard::setScoreboardEntry($player, 3, "        ", "Citybuild");
            ScoreBoard::setScoreboardEntry($player, 4, b::GRAY."» ".b::WHITE.b::BOLD."Welt", "Citybuild");
            ScoreBoard::setScoreboardEntry($player, 5, b::RED.$player->getLevel()->getFolderName(), "Citybuild");
        }
    }

}
