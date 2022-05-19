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

namespace skymin\CommandLib\utils;

use pocketmine\player\Player;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\{CommandEnum, CommandParameter};

use function array_values;

final class Parameter{
	public const FLAG_FORCE_COLLAPSE_ENUM = 0x0; //Somehow in pmmp source code, this ís 0x1 which is 1 while the default flag is 0
	public const FLAG_HAS_ENUM_CONSTRAINT = 0x1; //This is 0x2 in pmmp, I tried to make a CommandParameter with this but resulted in a broken command

	/** @param string[]|Translatable[] $enumValues */
	public static function create(
		string|Translatable $name,
		string|EnumType $enumType,
		?array $enumValues = null,
		int $flag = self::FLAG_FORCE_COLLAPSE_ENUM,
		bool $optional = false
	) : self{
		return new self($name, $enumType, $enumValues, $flag, $optional);
	}

	/** @var string[]|Translatable[] */
	public ?array $enumValues = null;

	/** @param string[]|Translatable[] $enumValues */
	public function __construct(
		public string|Translatable $name,
		public string|EnumType $enumType,
		?array $enumValues = null,
		public int $flag = self::FLAG_FORCE_COLLAPSE_ENUM,
		public bool $optional = false
	){
		if($enumValues !== null){
			$this->enumValues = array_values($enumValues);
		}
	}

	public function encode(Player $player) : CommandParameter{
		$enumValues = $this->enumValues;
		$enumType = $this->enumType;
		$name = $this->name;
		if($name instanceof Translatable){
			$name = $player->getLanguage()->translate($name);
		}
		if ($enumType instanceof EnumType) {
			if ($enumValues === null) {
				return CommandParameter::standard($this->name, $enumType->getParamType(), $this->flag, $this->optional);
			}
			$enumType = $enumType->name();
		}
		foreach ($enumValues as $key => $value) {
			if ($value instanceof Translatable) {
				$enumValues[$key] = $player->getLanguage()->translate($value);
			}
		}
		return CommandParameter::enum($this->name, new CommandEnum($enumType, $enumValues), $this->flag, $this->optional);
	}
}
