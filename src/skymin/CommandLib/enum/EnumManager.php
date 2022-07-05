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

use LogicException;
use InvalidArgumentException;

use pocketmine\Server;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;

final class EnumManager{

	/**
	 * @var Enum[]
	 * @phpstan-var array<string, Enum>
	 */
	private static array $enums = [];

		/**
	 * @var Enum[]
	 * @phpstan-var array<string, Enum>
	 */
	private static array $softEnums = [];

	public static function register(Enum $enum) : void{
		$name = $enum->getName();
		if(isset(self::$enums[$name])){
			throw new InvalidArgumentException("$name is an already registered Enum.");
		}
		self::$enums[$name] = $enum;
		if($enum->isSoft()){
			self::$softEnums[$name] = $enum->encode();
		}
	}

	public static function isRegister(string $enumName) : bool{
		return isset(self::$enums[$enumName]);
	}

	public function getEnum(string $name) : Enum{
		if(isset(self::$enums[$name])){
			return self::$enums[$name];
		}
		throw new LogicException('Tried creating menu before calling ' . self::class . '::register()');
	}

	public static function getSoftEnums() : array{
		return self::$softEnums;
	}

	public static function updateEnum(string $enumName) : void{
		if(!self::isRegister($enumName)){
			throw new LogicException($enumName . 'is unregistered enum');
		}
		$enum = self::$enums[$enumName];
		if(!$enum->isSoft()){
			throw new InvalidArgumentException($enumName . 'is not softEnum');
		}
		self::$softEnums[$name] = $enum->encode();
		$server = Server::getInstance();
		$server->broadcastPackets(
			$server->getOnlinePlayers(),
			UpdateSoftEnumPacket::create($enumName, $enum->getValues(), UpdateSoftEnumPacket::TYPE_SET);
		);
	}

}