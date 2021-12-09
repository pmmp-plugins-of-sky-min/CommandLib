<?php
declare(strict_types = 1);

namespace skymin\CommandLib;

use pocketmine\Server;
use pocketmine\plugin\Plugin;

final class CmdManager{
	
	private static bool $registerBool= false;
	
	public static function register(Plugin $plugin) : void{
		if(!self::$registerBool){
			Server::getInstance()->getPluginManager()->registerEvents(new PacketListener(), $plugin);
			self::$registerBool = true;
		}
	}
	
	public static function isRegister() : bool{
		return self::$registerBool;
	}
	
}