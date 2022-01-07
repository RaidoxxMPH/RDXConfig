<?php

declare(strict_types=1);

namespace Raidoxx\RDXConfig;

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
       ##### Eventos Players #####
      $this->FoodConfig = (new Config($this->getDataFolder()."ConfigFood.yml", Config::YAML, array(
        "food-enable" => true,
        )));
        $this->DropConfig = (new Config($this->getDataFolder()."ConfigDrop.yml", Config::YAML, array(
          "drop-enable" => true,
          )));
          $this->SkinConfig = (new Config($this->getDataFolder()."ConfigSkin.yml", Config::YAML, array(
            "skin-enable" => true,
            )));
            ##### Entidades #####
            $this->EntityConfig = (new Config($this->getDataFolder()."ConfigEntity.yml", Config::YAML, array(
              "damage-enable" => true,
              "explode-enable" => true,
              "itempickup-enable" => true,
              )));
            ####### Blocks #######
            $this->BlockConfig = (new Config($this->getDataFolder()."ConfigBlock.yml", Config::YAML, array(
              "break-enable" => true,
              "place-enable" => true,
              )));
  }
  public function onHunger(PlayerExhaustEvent $e){
    if(!$this->FoodConfig->get('food-enable') === false){
        $e->cancel(true);
    }
  }
  public function onDrop(PlayerDropItemEvent $e){
    if(!$this->DropConfig->get('drop-enable') === false){
        $e->cancel(true);
    }
  }
  public function onChange(PlayerChangeSkinEvent $e){
    if(!$this->SkinConfig->get('skin-enable') === false){
        $e->cancel(true);
    }
  }
  public function onHit(EntityDamageEvent $e){
    $causa = $e->getCause();
    if(!$this->EntityConfig->get('damage-enable') === false){
      if ($causa === EntityDamageEvent::CAUSE_FALL) {
          $e->cancel(true);         
    } 
    elseif ($causa === EntityDamageEvent::CAUSE_ENTITY_ATTACK){
      $e->cancel(true);
    }
      elseif ($causa === EntityDamageEvent::CAUSE_VOID) {
        $entity = $e->getEntity();
        $this->AntiVoid($entity);
        $e->cancel(true);
      }
        elseif ($causa === EntityDamageEvent::CAUSE_BLOCK_EXPLOSION){
          $e->cancel(true);
        }
           elseif ($causa === EntityDamageEvent::CAUSE_SUFFOCATION) {
          $e->cancel(true);
        }
            elseif ($causa === EntityDamageEvent::CAUSE_DROWNING){
              $e->cancel(true);
            }
              elseif ($causa === EntityDamageEvent::CAUSE_FIRE or $causa === EntityDamageEvent::CAUSE_FIRE_TICK or $causa === EntityDamageEvent::CAUSE_LAVA){
                $e->cancel(true);
              } 
                elseif ($e instanceof EntityDamageByEntityEvent){
                  $damager = $e->getDamager();
                  if ($damager instanceof Player) {
                  $e->cancel(true);
                  }
                }
          }
  }
  public function AntiVoid(Player $player) : void{
			$position = $player->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation();
		  $player->teleport($position);
	}
  public function onExplode(EntityExplodeEvent $e){
    if(!$this->EntityConfig->get('explode-enable') === false){
        $e->cancel(true);
    }
  }
  public function onGet(EntityItemPickupEvent $e){
    if(!$this->EntityConfig->get('itempickup-enable') === false){
        $e->cancel(true);
    }
  }
  public function onBreak(BlockBreakEvent $e){
    if(!$this->BlockConfig->get('break-enable') === false){
      $e->cancel(true);
    }
  }
  public function onPlace(BlockPlaceEvent $e){
    if(!$this->BlockConfig->get('place-enable') === false){
      $e->cancel(true);
    }
  }
}