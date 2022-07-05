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

namespace skymin\CommandLib\parameter;

use skymin\CommandLib\enum\{
	Enum,
	EnumManager
};

use pocketmine\player\Player;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

final class Parameter{

	public const FLAG_FORCE_COLLAPSE_ENUM = 0x0; //Somehow in pmmp source code, this ís 0x1 which is 1 while the default flag is 0
	public const FLAG_HAS_ENUM_CONSTRAINT = 0x1; //This is 0x2 in pmmp, I tried to make a CommandParameter with this but resulted in a broken command

	public function __construct(
		private string|Translatable $name,
		private ParamType|Enum $type,
		private int $flag = self::FLAG_FORCE_COLLAPSE_ENUM,
		private bool $optional = false
	){}

	public function encode() : CommandParameter{
		$type = $this->type;
		$name = $this->name;
		if($type instanceof Enum){
			if(!EnumManager::isRegister($type->getName())){
				new \LogicException('Tried creating menu before calling ' . EnumManager::class . ' register');
			}
			return CommandParameter::enum($this->name, $type->encode(), $this->flag, $this->optional);
		}
		return CommandParameter::standard($name, $type->getParamType(), $this->flag, $this->optional);
	}

}