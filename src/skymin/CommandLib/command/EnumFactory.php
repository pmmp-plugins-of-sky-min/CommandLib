<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

final class EnumFactory{
	
	public static function create(string $name, string $enumName, array $enumValues, bool $optional = false) : CommandParameter{
		$result = CommandParameter::enum($name, new CommandEnum($enumName, $enumValues), 0, $optional);
		return $result;
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