<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

final class EnumFactory{
	
	public static function create(string $name, string|EnumType $enumType, array $enumValues = [], bool $optional = false) : CommandParameter{
		$result = new CommandParameter();
		$result->paramName = $name;
		$result->flags = 0;
		$result->isOptional = $optional;
		$result->paramType = AvailableCommandsPacket::ARG_FLAG_VALID;
		if($enumType instanceof EnumType){
			$result->paramType |= $enumType->getParamType();
			return $result;
		}
		$result->paramType |= AvailableCommandsPacket::ARG_FLAG_ENUM;
		$result->enum = new CommandEnum($enumType, $enumValues) ;
		return $result;
	}
	
}