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
use pocketmine\utils\Utils;

use Closure;

use function array_values;

final class Enum{

	/** @var string[] */
	private array $values;

	private ?Closure $updater = null;

	/** @param string[] $values */
	public function __construct(
		private string $name,
		array|Closure $values
	){
		if($values instanceof Closure){
			Utils::validateCallableSignature(function() : array{}, $values);
			$this->updater = $values;
			$values = $values();
		}
		$this->values = array_values($values);
	}

	public function isSoft() : bool{
		return $this->updater !== null;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getValues() : array{
		return $this->values;
	}

	public function update() : void{
		$name = $this->name;
		if(!EnumManager::isRegister($this)){
			throw new \LogicException($name . 'is unregistered enum');
		}
		if(!$this->isSoft()){
			throw new \InvalidArgumentException($name . 'is not softEnum');
		}
		$this->values = array_values(($this->updater)());
		EnumManager::updateSoftEnum($this);
	}

	/** @internal */
	public function encode() : CommandEnum{
		return new CommandEnum($this->name, $this->values);
	}

}