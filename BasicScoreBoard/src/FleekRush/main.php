<?php

namespace FleekRush;

//Basis

use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\inventory;

//Events
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

//scoreboard
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;

class main extends PluginBase implements Listener{
    

    public function onEnable(){
       $this->getLogger()->info($this->prefix . "Enabled.");
       $this->getServer()->getPluginManager()->registerEvents($this, $this);
       $this->getScheduler()->scheduleRepeatingTask(new SCTask($this), 20);



    }




    public function setScoreboardEntry(Player $player, int $score, string $msg, string $objName){


    $entry = new ScorePacketEntry();
    $entry->objectiveName = $objName;
    $entry->type = 3;
    $entry->customName = " $msg   ";
    $entry->score = $score;
    $entry->scoreboardId = $score;
    $pk = new SetScorePacket();
    $pk->type = 0;
    $pk->entries[$score] = $entry;
    $player->sendDataPacket($pk);
}

    public function rmScoreboardEntry(Player $player, int $score){

        $pk = new SetScorePacket();
        if (isset($pk->entries[$score])) {
            unset($pk->entries[$score]);
            $player->sendDataPacket($pk);
        }
    }

    public function createScoreboard(Player $player, string $title, string $objName, string $slot = "sidebar", $order = 0){


        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = $slot;
        $pk->objectiveName = $objName;
        $pk->displayName = $title;
        $pk->criteriaName = "dummy";
        $pk->sortOrder = $order;
        $player->sendDataPacket($pk);
    }

    public function rmScoreboard(Player $player, string $objName){


        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = $objName;
        $player->sendDataPacket($pk);
    }






    public function onScore(){


        $pl = $this->getServer()->getOnlinePlayers();
        foreach ($pl as $player) {
            $name = $player->getName();
            $this->rmScoreboard($player, "objektName");
            $this->setScoreboardEntry($player, 0, "", "objektName");
            $this->createScoreboard($player, C::BOLD . "Title", "objektName");


            $this->setScoreboardEntry($player, 1, C::YELLOW . "FirstLine", "objektName");
            $this->setScoreboardEntry($player, 2, C::GRAY . "SecondLine", "objektName");



        }
    }
