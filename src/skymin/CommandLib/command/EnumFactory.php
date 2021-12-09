<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

final class EnumFactory{
	
	public static function enum(string $name, string $enumName, array $enumValues, bool $optional = false) : CommandParameter{
		$parameter = CommandParameter::baseline($name, AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID, 0, $optional);
		$parameter->enum = new CommandEnum($enumName, $enumValues);
		return $parameter;
	}
	
}