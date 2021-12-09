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

use skymin\CommandLib\command\BaseCommand ;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

final class PacketListener implements Listener{
	
	public function onDataPcaketSend(DataPacketSendEvent $ev) : void{
		foreach($ev->getPackets() as $packet){
			if(!$packet instanceof AvailableCommandsPacket) continue;
			foreach($packet->commandData as $name => $commandData){
				$cmd = Server::getInstance()->getCommandMap()->getCommand($name);
				if($cmd instanceof BaseCommand){
					if($cmd->hasOverloads()){
						$commandData->overloads = $cmd->getOverloads();
					}
				}
			}
		}
	}
	
}