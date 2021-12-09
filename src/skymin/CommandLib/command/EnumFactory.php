<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

final class EnumFactory{
	
	public static function create(string $name, string $enumName, array $enumValues, bool $optional = false) : CommandParameter{
		$parameter = CommandParameter::baseline($name, AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID, 0, $optional);
		$parameter->enum = new CommandEnum($enumName, $enumValues);
		return $parameter;
	}
	
	public static function arrayCreate(array $parameters, bool $optional = false) : array{
		$result = [];
		foreach($parameters as $name => $enum){
			if(is_array($enum)){
				$enumName = key($enum);
				$result[] = self::create($name, $enumName,  $enum($enumName));
			}
		}
		return $result;
	}
	
}