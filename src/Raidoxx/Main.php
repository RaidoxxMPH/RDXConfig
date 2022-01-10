<?php

declare(strict_types=1);

namespace Raidoxx\RDXConfig\Raidoxx;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerChangeSkinEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\player\Player;
use pocketmine\world\World;
use pocketmine\event\Cancellable;

class Main extends PluginBase implements Listener{

  
  public function onEnable():void{
      @mkdir($this->getDataFolder());
      $this->getServer()->getLogger()->info("§aRDXConfig | §bBy: Raidoxx ©2022-".date('Y'));
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
       ##### Config #####
      $this->RdxConfig = (new Config($this->getDataFolder()."RDXConfig.yml", Config::YAML, array(
        "NoFoodWorlds" => [ "world", "world2"],
        "NoDropsWorlds" => [ "world", "world2"],
        "NoDamageWorlds" => [ "world", "world2"],
        "NoExplodeWorlds" => [ "world", "world2"],
        "NoItemsPickupWorlds" => [ "world", "world2"],
        "NoBreakWorlds" => [ "world", "world2"],
        "NoPlaceWorlds" =>  [ "world", "world2"],
        "skin-enable" => true,
        )));
  }
  
  #Quando o player ficar com fome desabilitar o evento
  
  public function onHunger(PlayerExhaustEvent $e){
    $player = $e->getPlayer();
    if(in_array($player->getWorld()->getProvider()->getWorldData()->getName(), $this->RdxConfig->get('NoFoodWorlds'))){
        $e->cancel(true);
    }
  }
  
  #Quando o player dropar algum item desabilitar o evento
  
  
  public function onDrop(PlayerDropItemEvent $e){
    $player = $e->getPlayer();
    if(in_array($player->getWorld()->getProvider()->getWorldData()->getName(), $this->RdxConfig->get('NoDropsWorlds'))){
        $e->cancel(true);
    }
  }
  
  #Quando player mudar de skin desabilitar o evento
  
  public function onChange(PlayerChangeSkinEvent $e){
    if(!$this->RdxConfig->get('skin-enable') === false){
        $e->cancel(true);
    }
  }
  
  
  #Quando o player tomar/dar um hit desativar o evento
  
  public function onHit(EntityDamageEvent $e){
    $entity = $e->getEntity();
    $causa = $e->getCause();
  
    if(in_array($entity->getWorld()->getProvider()->getWorldData()->getName(), $this->RdxConfig->get('NoDamageWorlds'))){
      
      #Se a causa for por queda cancelar o evento
      if ($causa === EntityDamageEvent::CAUSE_FALL) {
          $e->cancel(true);         
    } 
    
    
    #Mas se a causa for por ataque de entidade cancelar o evento
    elseif ($causa === EntityDamageEvent::CAUSE_ENTITY_ATTACK){
      $e->cancel(true);
    }
    
    
    #Mas se a causa for por dano do void cancelar o evento e teletransportar o player para o spawn seguro do mundo
      elseif ($causa === EntityDamageEvent::CAUSE_VOID) {
        $entity = $e->getEntity();
        $this->AntiVoid($entity);
        $e->cancel(true);
      }
      
      
      #Mas se a causa for por bloco de explosão cancelar o evento
        elseif ($causa === EntityDamageEvent::CAUSE_BLOCK_EXPLOSION){
          $e->cancel(true);
        }
           #Mas se a causa for por entidade de explosão cancelar o evento
        elseif ($causa === EntityDamageEvent::CAUSE_ENTITY_EXPLOSION){
          $e->cancel(true);
        }
        
         #Mas se a causa for por sufocamento cancelar o evento
           elseif ($causa === EntityDamageEvent::CAUSE_SUFFOCATION) {
          $e->cancel(true);
        }
        
        
         #Mas se a causa for por afogamento cancelar o evento
            elseif ($causa === EntityDamageEvent::CAUSE_DROWNING){
              $e->cancel(true);
            }
            
             #Mas se a causa for por Fogo,Tick de Fogo ou Lava cancelar o evento
              elseif ($causa === EntityDamageEvent::CAUSE_FIRE or $causa === EntityDamageEvent::CAUSE_FIRE_TICK or $causa === EntityDamageEvent::CAUSE_LAVA){
                $e->cancel(true);
              } 
              
              #Mas se a causa for por Dar dano em uma entidade, cancelar o evento.
                elseif ($e instanceof EntityDamageByEntityEvent){
                  $damager = $e->getDamager();
                  if ($damager instanceof Player) {
                  $e->cancel(true);
                  }
                }
          }
  }
  
  #Quando player tomar dano do void voltar para o spawn do mundo
  
  public function AntiVoid(Player $player) : void{
			$position = $player->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation();
		  $player->teleport($position);
	}
	
	
	#Quando uma entidade explodir cancelar o evento
	
  public function onExplode(EntityExplodeEvent $e){
    $player = $e->getEntity();
    if(in_array($player->getWorld()->getProvider()->getWorldData()->getName(), $this->RdxConfig->get('NoExplodeWorlds'))){
        $e->cancel(true);
    }
  }
  
  #Quando o player pegar o item do chão cancelar o evento
  
  public function onGet(EntityItemPickupEvent $e){
    $player = $e->getOrigin();
    if(in_array($player->getWorld()->getProvider()->getWorldData()->getName(), $this->RdxConfig->get("NoItemsPickupWorlds"))){
        $e->cancel(true);
    }
  }
  
  #Quando o jogador quebrar algun bloco cancelar o evento
  
  public function onBreak(BlockBreakEvent $e){
    $player = $e->getPlayer();
    if(in_array($player->getWorld()->getProvider()->getWorldData()->getName(), $this->RdxConfig->get('NoBreakWorlds'))){
      $e->cancel(true);
    }
  }
  
  #Quando um player colocar algum bloco cancelar o evento
  
  public function onPlace(BlockPlaceEvent $e){
  $player = $e->getPlayer();
  if(in_array($player->getWorld()->getProvider()->getWorldData()->getName(), $this->RdxConfig->get('NoPlaceWorlds'))){
      $e->cancel(true);
    }
  }
}