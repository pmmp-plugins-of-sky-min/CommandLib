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
use pocketmine\network\mcpe\protocol\types\command\{CommandData, CommandEnum};
use pocketmine\player\Player;
use pocketmine\lang\Translatable;
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
					} else {
						self::generateOverloads($ev->getTargets(), $packet);
					}
					break;
				}
			}
		}, EventPriority::MONITOR, $plugin);
		self::$registerBool = true;
	}

	private static function generateOverloads(array $targets, AvailableCommandsPacket &$packet) : void{
		if (count($targets) === 1) {
			[$target] = $targets;
			$player = $target->getPlayer();
			foreach ($packet->commandData as $name => $commandData) {
				$cmd = Server::getInstance()->getCommandMap()->getCommand($name);
				if ($cmd instanceof BaseCommand && $cmd->hasOverloads()) {
					$commandData->overloads = $cmd->getOverloads($player);
				}
			}
		} else {
			foreach ($targets as $target) {
				$player = $target->getPlayer();
				$pk = clone $packet;
				foreach ($pk->commandData as $name => $commandData) {
					$cmd = Server::getInstance()->getCommandMap()->getCommand($name);
					if ($cmd instanceof BaseCommand && $cmd->hasOverloads()) {
						$commandData->overloads = $cmd->getOverloads($player);
					} else {
						unset($pk->commandData[$name]);
					}
				}
				self::$pks[spl_object_id($pk)] = true;
				$target->sendDataPacket($pk);
			}
		}
	}

	public static function isRegister() : bool{
		return self::$registerBool;
	}

	public static function update(BaseCommand $command) : void{
		$name = $command->getName();
		$lname = strtolower($name);
		$aliases = $command->getAliases();
		$aliasObj = null;
		if (count($aliases) > 0) {
			if (!in_array($lname, $aliases, true)) {
				$aliases[] = $lname;
			}
			$aliasObj = new CommandEnum(ucfirst($name) . 'Aliases', array_values($aliases));
		}
		$description = $command->getDescription();
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			$pk = new AvailableCommandsPacket();
			$pk->commandData = [new CommandData(
				$lname,
				$description instanceof Translatable ? $player->getLanguage()->translate($description) : $description,
				0,
				0,
				$aliasObj,
				$command->getOverloads($player)
			)];
			self::$pks[spl_object_id($pk)] = true;
			$player->getNetworkSession()->sendDataPacket($pk);
		}
	}

}