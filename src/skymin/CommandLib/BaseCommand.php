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

use pocketmine\command\Command;
use pocketmine\lang\Translatable;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;

use function explode;
use function array_values;

abstract class BaseCommand extends Command{

	/** @var string[][] */
	private array $overPermission = [];

	/** @var Parameter[][] */
	private array $overloads = [];

	public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
		if(!CmdManager::isRegister()){
			throw new \LogicException('Tried creating menu before calling ' . CmdManager::class . ' register');
		}
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	final public function addParameter(Parameter $parameter, int $overloadIndex) : void{
		$this->overloads[$overloadIndex][] = $parameter;
	}

	final public function setParameter(Parameter $parameter, int $parameterIndex, int $overloadIndex) : void{
		$this->overloads[$overloadIndex][$parameterIndex] = $parameter;
	}

	final public function getParameter(int $parameterIndex, int $overloadIndex) : ?Parameter{
		return $this->overloads[$overloadIndex][$parameterIndex] ?? null;
	}

	/** @param Parameter[] $parameters */
	final public function addOverload(array $parameters) : void{
		$this->overloads[] = array_values($parameters);
	}

	/** @param Parameter[] $parameters */
	final public function setOverload(array $parameters, int $overloadIndex) : void{
		$this->overloads[$overloadIndex] = array_values($parameters);
	}

	/** @return Parameter[] */
	final public function getOverload(int $overloadIndex) : ?array{
		return $this->overloads[$overloadIndex] ?? null;
	}

	/** @param Parameter[][] $parameters */
	final public function setOverloads(array $overloads) : void{
		$this->overloads = $overloads;
	}

	final public function setOverloadPermission(int $overloadIndex, string $permission) : void{
		foreach(explode(';', $permission) as $perm){
			if(PermissionManager::getInstance()->getPermission($perm) === null){
				throw new \InvalidArgumentException("Cannot use non-existing permission \"$perm\"");
			}
		}
		$this->overPermission[$overloadIndex] = $permission;
	}

	final public function getOverloadPermission(int $overloadIndex) : ?string{
		return $this->overPermission[$overloadIndex] ?? null;
	}

	final public function hasOverloads() : bool{
		return $this->overloads !== [];
	}

	final public function encode(Player $player) : array{
		$encode = [];
		$overPermission = $this->overPermission;
		foreach ($this->overloads as $overKey => $overload) {
			if (isset($overPermission[$overKey]) && !$player->hasPermission($overPermission[$overKey])) {
				continue;
			}
			foreach ($overload as $paramKey => $parameter){
				$encode[$overKey][$paramKey] = $parameter->encode($player);
			}
		}
		return $encode;
	}

}