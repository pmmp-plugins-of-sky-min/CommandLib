<?php
declare(strict_types = 1);

namespace skymin\CommandLib;

use pocketmine\Server;
use pocketmine\plugin\Plugin;

final class CmdManager{
	
	private bool $registerBool= false;
	
	public static function register(Plugin $plugin) : void{
		if(!$this->registerBool){
			Server::getInstance()->getPluginManager()->registerEvents(PacketListener, $plugin);
		}
	}
	
	public static function isRegister() : bool{
		return $this->registerBool;
	}
	
}