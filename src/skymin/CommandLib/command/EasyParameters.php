<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

use function key;

final class EasyParameters{
	
	public static function create(array $parameters, bool $optional = false) : array{
		$result = [];
		foreach($parameters as $name => $enum){
			if(is_array($enum)){
				$enumkey = key($enum);
				$enum = new CommandEnum($enumkey, $enum($enumkey));
			}
			if($enum instanceof CommandEnum){
				$parameter = CommandParameter::baseline($name, AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID, 0, $optional);
				$parameter->enum = $enum;
				$result[] = $parameter;
			}
		}
		return $result;
	}
	
}