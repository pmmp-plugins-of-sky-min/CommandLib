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

namespace skymin\CommandLib\enum;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;

use function array_values;

final class Enum{

	public const TYPE_SOFT = 0;
	public const TYPE_HARDCODE = 1;

	/** @var string[] */
	private array $values;

	private bool $registerCheck = false;

	/** @param string[] $values */
	public function __construct(
		private string $name,
		array $values,
		private int $type = self::TYPE_SOFT
	){
		$this->values = array_values($values);
	}

	public function getType() : int{
		return $this->type;
	}

	public function getName() : void{
		return $this->name;
	}

	public function getValues() : array{
		return $this->values
	}

	public function setValues(array $values) : void{
		$this->values = $values;
	}

	public function encode() : CommandEnum{
		return new CommandEnum($this->name, $this->values);
	}


}