<?php

declare(strict_types=1);

namespace davidglitch04\PluginUpdater;

use pocketmine\plugin\PluginBase;

final class PluginUpdater
{
    /**
     * @param PluginBase $plugin
     * @param array $configData
     * @param bool $enable
     * @return void
     */
    public static function checkUpdate(PluginBase $plugin, array $configData) : UpdateGenerator
    {
        return new UpdateGenerator($plugin, $configData);
    }
}