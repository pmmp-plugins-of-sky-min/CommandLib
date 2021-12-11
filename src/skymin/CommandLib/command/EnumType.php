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

namespace skymin\CommandLib\command;

use pocketmine\utils\EnumTrait;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket as Type;

/**
 * @method static self INT()
 * @method static self FLOAT()
 * @method static self STRING()
 * @method static self TARGET()
 * @method static self POSITION()
 * @method static self MESSAGE()
 * @method static self JSON()
 */
final class EnumType{
	use EnumTrait{
		__construct as Enum__construct;
	}
	
	protected static function setup() : void{
		self::registerAll(
			new self('int', Type::ARG_TYPE_INT),
			new self('float', Type::ARG_TYPE_FLOAT),
			new self('string', Type::ARG_TYPE_STRING),
			new self('target', Type::ARG_TYPE_POSITION),
			new self('position', Type::ARG_TYPE_TARGET),
			new self('message',  Type::ARG_TYPE_MESSAGE),
			new self('json', Type::ARG_TYPE_JSON)
		);
	}
	
	private function __construct(string $name, private int $paramType){
		$this->Enum__construct($name);
	}
	
	public function getParamType() : int{
		return $this->paramType;
	}
	
}