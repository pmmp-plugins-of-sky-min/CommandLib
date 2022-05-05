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

use pocketmine\Server;
use pocketmine\event\EventPriority;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

use function spl_object_id;

final class CmdManager{

	private static bool $registerBool= false;

	/** @var true[] */
	private static array $pks = [];

	public static function register(Plugin $plugin) : void{
		if (self::$registerBool) {
			return;
		}
		Server::getInstance()->getPluginManager()->registerEvent(DataPacketSendEvent::class, static function(DataPacketSendEvent $ev) : void{
			foreach ($ev->getPackets() as $packet) {
				if ($packet instanceof AvailableCommandsPacket) {
					$id = spl_object_id($packet);
					if (isset(self::$pks[$id])) {
						unset(self::$pks[$id]);
						return;
					}
					$target = $ev->getTargets()[0];
					$player = $target->getPlayer();
					foreach ($packet->commandData as $name => $commandData) {
						$cmd = Server::getInstance()->getCommandMap()->getCommand($name);
						if ($cmd instanceof BaseCommand && $cmd->hasOverloads()) {
							$commandData->overloads = $cmd->encode($player);
						}
					}
				}
			}
		}, EventPriority::MONITOR, $plugin);
		self::$registerBool = true;
	}

	public static function isRegister() : bool{
		return self::$registerBool;
	}

	public static function update(Player $player) : void{
		$player->getNetworkSession()->syncAvailableCommands();
	}

}
