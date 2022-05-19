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
 * @author libs\skymin
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

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket as Type;
use pocketmine\utils\EnumTrait;

/**
 * @method static self INT()
 * @method static self FLOAT()
 * @method static self WILDCARD_INT()
 * @method static self OPERATOR()
 * @method static self TARGET()
 * @method static self WILDCARD_TARGE()
 * @method static self FILE()
 * @method static self SLOT()
 * @method static self STRING()
 * @method static self INT_POSITION()
 * @method static self FLOAT_POSITION()
 * @method static self MESSAGE()
 * @method static self TEXT()
 * @method static self JSON()
 * @method static self COMMAND()
 */

final class EnumType{
	use EnumTrait{
		__construct as Enum__construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self('int', Type::ARG_TYPE_INT),
			new self('float', Type::ARG_TYPE_FLOAT),
			new self('value', Type::ARG_TYPE_VALUE),
			new self('wildcard_int', Type::ARG_TYPE_WILDCARD_INT),
			new self('operator', Type::ARG_TYPE_OPERATOR),
			new self('target', Type::ARG_TYPE_TARGET),
			new self('wildcard_target', Type::ARG_TYPE_WILDCARD_TARGET),
			new self('file', Type::ARG_TYPE_FILEPATH),
			new self('slot', Type::ARG_TYPE_EQUIPMENT_SLOT),
			new self('string', Type::ARG_TYPE_STRING),
			new self('int_position', Type::ARG_TYPE_INT_POSITION),
			new self('float_position', Type::ARG_TYPE_POSITION),
			new self('message', Type::ARG_TYPE_MESSAGE),
			new self('text', Type::ARG_TYPE_RAWTEXT),
			new self('json', Type::ARG_TYPE_JSON),
			new self('command', Type::ARG_TYPE_COMMAND)
		);
	}

	private function __construct(string $name, private int $paramType){
		$this->Enum__construct($name);
	}

	public function getParamType() : int{
		return $this->paramType;
	}
}
