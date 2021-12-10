<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\Server;
use pocketmine\utils\EnumTrait;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket as Type;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;

use function array_map;

/**
 * @method static self INT()
 * @method static self FLOAT()
 * @method static self STRING()
 * @method static self TARGET()
 * @method static self POSITION()
 */
final class EnumType{
	use EnumTrait;
	
	protected static function setup() : void{
		self::registerAll(
			new self('int'),
			new self('float'),
			new self('string'),
			new self('target'),
			new self('position')
		);
	}
	
	public function getParamType(){
		return match($this->id()){
			self::INT()->id() => Type::ARG_TYPE_INT,
			self::FLOAT()->id() => Type::ARG_TYPE_FLOAT,
			self::STRING()->id() => Type::ARG_TYPE_STRING,
			self::POSITION()->id() => Type::ARG_TYPE_POSITION,
			self::TARGET()->id() => Type::ARG_TYPE_TARGET
		};
	}
	
}