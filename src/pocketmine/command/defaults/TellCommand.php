<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TellCommand extends VanillaCommand{

	public function __construct(string $name){
		parent::__construct(
			$name,
			"%pocketmine.command.tell.description",
			"%commands.message.usage",
			["w", "msg"]
		);
		$this->setPermission("pocketmine.command.tell");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 2){
			throw new InvalidCommandSyntaxException();
		}

		$name = strtolower(array_shift($args));

		$player = $sender->getServer()->getPlayer($name);

		if($player === $sender){
			$sender->sendMessage("§l§o§3G§bC§r§7: §cYou can't send a message to yourself!");
			return true;
		}

		if($player instanceof Player){
			$sender->sendMessage("§l§o§3G§bC§r§7: §bYou whispered to §6" . $player->getDisplayName() . "§b: §c" . implode(" ", $args));
                        $player->sendMessage("§l§o§3G§bC§r§7: §6" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . "§b whispered to you: §c" . implode(" ", $args));
		}else{
			$sender->sendMessage("§l§o§3G§bC§r§7: §cThat player isn't online or doesn't exist.");
		}

		return true;
	}
}
