<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

use function key;

final class EasyParameter{
	
	public static function create($parameters, bool $optional = false) : array{
		$result = [];
		foreach($parameters as $name => $enum){
			$parameter = CommandParameter::baseline($name, AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID, 0, $optional);
			$enumkey = key($enum);
			$parameter->enum = new CommandEnum($enumkey, $enum($enumkey));
			$result[] = $parameter;
		}
		return $result;
	}
	
}