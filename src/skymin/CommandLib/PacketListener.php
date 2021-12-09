<?php
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
					$commandData->overloads = $cmd->getOverloads();
				}
			}
		}
	}
	
}