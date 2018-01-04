<?php

namespace Angel\PCFly;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\entity\EntityDamageEvent;

class Main extends PluginBase implements Listener{

    private $fly = [];
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("PC Fly made by Angel(@VortexZMcPe)");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        if(strtolower($command->getName()) == "vmfly"){
            if($sender->hasPermission("fly.command") || $sender->isOp()){
                if(isset($this->fly[strtolower($sender->getName())])){
                    unset($this->fly[strtolower($sender->getName())]);
                    $sender->setAllowFlight(false);
                    $sender->setFlying(false);
                    $sender->sendMessage(TF::RED."Fly disabled!");
                } else {
                    $this->fly[strtolower($sender->getName())] = strtolower($sender->getName());
                    $sender->setAllowFlight(true);
                    $sender->setFlying(true);
                    $sender->sendMessage(TF::GREEN."Fly enabled!");
                }
            } else {
                $sender->sendMessage(TF::RED."You dont have permission to use this command");
            }
        }
        return true;
    }

    public function onHit(EntityDamageEvent $ev){
        
        if(($p = $ev->getEntity()) instanceof Player){
            
            if($ev->getCause() !== 4 && isset($this->fly[$p->getLowerCaseName()])){
                $p->sendPopup(TF::RED . 'Fly disabled');
                $p->setFlying(true);
                $p->setAllowFlight(false);
                unset($this->fly[$p->getLowerCaseName()]);
            }
        }
    }
}
