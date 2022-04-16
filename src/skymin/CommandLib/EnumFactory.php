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

use pocketmine\network\mcpe\protocol\types\command\{CommandEnum, CommandParameter};

final class EnumFactory{
	public const FLAG_FORCE_COLLAPSE_ENUM = 0x0; //Somehow in pmmp source code, this ís 0x1 which is 1 while the default flag is 0
	public const FLAG_HAS_ENUM_CONSTRAINT = 0x1; //This is 0x2 in pmmp, I tried to make a CommandParameter with this but resulted in a broken command
	
	public static function create(string $name, string|EnumType $enumType, ?array $enumValues = null, int $flag = self::FLAG_FORCE_COLLAPSE_ENUM, bool $optional = false) : CommandParameter{
		if($enumType instanceof EnumType){
			if($enumValues === null){
				return CommandParameter::standard($name, $enumType->getParamType(), $flag, $optional);
			}
			$enumType = $enumType->name();
		}
		return CommandParameter::enum($name, new CommandEnum($enumType, $enumValues), $flag, $optional);
	}
	
}