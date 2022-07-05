<?php
/**
 *      _                    _       
 *  ___| | ___   _ _ __ ___ (_)_ __  
 * / __| |/ / | | | '_ ` _ \| | '_ \ 
 * \__ \   <| |_| | | | | | | | | | |
 * |___/_|\_\\__, |_| |_| |_|_|_| |_|
 *           |___/ 
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 * 
 * @author skymin
 * @link   https://github.com/sky-min
 * @license https://opensource.org/licenses/MIT MIT License
 * 
 *   /\___/\
 * 　(∩`・ω・)
 * ＿/_ミつ/￣￣￣/
 * 　　＼/＿＿＿/
 *
 */

declare(strict_types = 1);
	
namespace skymin\CommandLib;

use skymin\CommandLib\enum\EnumManager;

use pocketmine\Server;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

use function count;
use function reset;

final class CmdManager{

	private static bool $registerBool = false;

	public static function register(Plugin $plugin) : void{
		if (self::$registerBool) {
			return;
		}
		$server = Server::getInstance();
		$commandMap = $server->getCommandMap();
		$server->getPluginManager()->registerEvent(DataPacketSendEvent::class, static function(DataPacketSendEvent $ev) use($commandMap): void{
			$packets = $ev->getPackets();
			if(count($packets) !== 1){
				return;
			}
			$packet = reset($packets);
			if(!$packet instanceof AvailableCommandsPacket){
				return;
			}
			$targets = $ev->getTargets();
			if(count($targets) !== 1){
				return;
			}
			foreach ($packet->commandData as $name => $commandData) {
				$cmd = $commandMap->getCommand($name);
				if($cmd instanceof BaseCommand && $cmd->hasOverloads()){
					$commandData->overloads = $cmd->encode($player);
				}
			}
			$packet->softEnums = EnumManager::getSoftEnums();
		}, EventPriority::MONITOR, $plugin);
		self::$registerBool = true;
	}

	public static function isRegister() : bool{
		return self::$registerBool;
	}

}