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

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\{CommandParameter, CommandEnum};

final class EnumFactory{
	
	public static function create(string $name, string|EnumType $enumType, ?array $enumValues = null, bool $optional = false) : CommandParameter{
		$result = new CommandParameter();
		$result->paramName = $name;
		$result->flags = 0;
		$result->isOptional = $optional;
		$result->paramType = AvailableCommandsPacket::ARG_FLAG_VALID;
		if($enumType instanceof EnumType){
			if($enumValues === null){
				$result->paramType |= $enumType->getParamType();
				return $result;
			}
			$enumType = $enumType->name();
		}
		$result->paramType |= AvailableCommandsPacket::ARG_FLAG_ENUM;
		$result->enum = new CommandEnum($enumType, $enumValues) ;
		return $result;
	}
	
}