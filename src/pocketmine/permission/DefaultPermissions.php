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

namespace pocketmine\permission;

use pocketmine\Server;

abstract class DefaultPermissions{
	const ROOT = "pocketmine";

	/**
	 * @param Permission $perm
	 * @param Permission $parent
	 *
	 * @return Permission
	 */
	public static function registerPermission(Permission $perm, Permission $parent = null) : Permission{
		if($parent instanceof Permission){
			$parent->getChildren()[$perm->getName()] = true;

			return self::registerPermission($perm);
		}
		Server::getInstance()->getPluginManager()->addPermission($perm);

		return Server::getInstance()->getPluginManager()->getPermission($perm->getName());
	}

	public static function registerCorePermissions(){
		$parent = self::registerPermission(new Permission(self::ROOT, "Allows using all PocketMine commands and utilities"));

		$broadcasts = self::registerPermission(new Permission(self::ROOT . ".broadcast", "Allows the user to receive all broadcast messages"), $parent);
		self::registerPermission(new Permission(self::ROOT . ".broadcast.admin", "Allows the user to receive administrative broadcasts", Permission::DEFAULT_OP), $broadcasts);
		self::registerPermission(new Permission(self::ROOT . ".broadcast.user", "Allows the user to receive user broadcasts", Permission::DEFAULT_TRUE), $broadcasts);
		$broadcasts->recalculatePermissibles();

		$spawnprotect = self::registerPermission(new Permission(self::ROOT . ".spawnprotect.bypass", "Allows the user to edit blocks within the protected spawn radius", Permission::DEFAULT_OP), $parent);
		$spawnprotect->recalculatePermissibles();

		$commands = self::registerPermission(new Permission(self::ROOT . ".command", "Allows using all PocketMine commands"), $parent);

		$time = self::registerPermission(new Permission(self::ROOT . ".command.time", "Allows the user to alter the time", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.time.add", "Allows the user to fast-forward time"), $time);
		self::registerPermission(new Permission(self::ROOT . ".command.time.set", "Allows the user to change the time"), $time);
		self::registerPermission(new Permission(self::ROOT . ".command.time.start", "Allows the user to restart the time"), $time);
		self::registerPermission(new Permission(self::ROOT . ".command.time.stop", "Allows the user to stop the time"), $time);
		self::registerPermission(new Permission(self::ROOT . ".command.time.query", "Allows the user query the time"), $time);
		$time->recalculatePermissibles();

		self::registerPermission(new Permission(self::ROOT . ".command.tell", "Allows the user to privately message another player", Permission::DEFAULT_TRUE), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.teleport", "Allows the user to teleport players", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.stop", "Allows the user to stop the server", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.list", "Allows the user to list all online players", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.reload", "Allows the user to reload the server settings", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.gamemode", "Allows the user to change the gamemode of players", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.status", "Allows the user to view the server performance", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.gc", "Allows the user to fire garbage collection tasks", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.dumpmemory", "Allows the user to dump memory contents", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.timings", "Allows the user to records timings for all plugin events", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.spawnpoint", "Allows the user to change player's spawnpoint", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.setworldspawn", "Allows the user to change the world spawn", Permission::DEFAULT_OP), $commands);
		self::registerPermission(new Permission(self::ROOT . ".command.transferserver", "Allows the user to transfer self to another server", Permission::DEFAULT_OP), $commands);

		$commands->recalculatePermissibles();

		$parent->recalculatePermissibles();
	}
}
