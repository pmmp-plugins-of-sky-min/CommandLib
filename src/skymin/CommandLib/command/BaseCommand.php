<?php
declare(strict_types = 1);

namespace skymin\CommandLib\command;

use pocketmine\command\Command;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

use skymin\CommandLib\CmdManager;

use function array_values;

abstract class BaseCommand extends Command{
	
	public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [], private array $overloads = [[new CommandParameter()]]){
		if(CmdManager::isRegister()){
			throw new \LogicException('Tried creating menu before calling ' . CmdManager::class . 'register');
		}
		parent::__construct($name, $description, $usageMessage, $aliases);
	}
	
	final public function addParameter(CommandParameter $parameter, int $overloadIndex = 0) : void{
		$this->overloads[$overloadIndex][] = $parameter;
	}
	
	final public function setParameter(CommandParameter $parameter, int $parameterIndex, int $overloadIndex = 0) : void{
		$this->overloads[$overloadIndex][$parameterIndex] = $parameter;
	}
	
	final public function setParameters(array $parameters, int $overloadIndex = 0) : void{
		$this->overloads[$overloadIndex] = array_values($parameters);
	}
	
	final function getOverloads() : array{
		return $this->overloads;
	}
	
}