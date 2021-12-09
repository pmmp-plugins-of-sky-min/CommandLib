<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

final class EnumFactory{
	
	public static function create(string $name, string $enumName, array $enumValues, bool $optional = false) : CommandParameter{
		$result = new CommandParameter();
		$result->paramName = $name;
		$result->paramType = AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID;
		$result->flags = 0;
		$result->isOptional = $optional;
		$result->enum = new CommandEnum($enumName, $enumValues) ;
		return $result;
	}
	
}