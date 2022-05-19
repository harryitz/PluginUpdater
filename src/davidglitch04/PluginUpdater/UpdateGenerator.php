<?php

declare(strict_types=1);

namespace davidglitch04\PluginUpdater;

use davidglitch04\PluginUpdater\Utils\GetUpdateInfo;
use pocketmine\plugin\PluginBase;

class UpdateGenerator
{
    /** @var PluginBase $plugin */
    protected PluginBase $plugin;
    /** @var array $configData */
    protected array $configData;

    /**
     * @param PluginBase $plugin
     * @param array $configData
     */
    public function __construct(PluginBase $plugin, array $configData){
        $this->plugin = $plugin;
        $this->configData = $configData;
    }

    /**
     * @return string
     */
    public function getURL() : string
    {
        return $this->configData["url"];
    }

    /**
     * @return string
     */
    public function getVersion() : string
    {
        return $this->configData["version"];
    }

    /**
     * @return string
     */
    public function getFile() : string
    {
        return $this->plugin->getPathFile();
    }

    /**
     * @return PluginBase
     */
    public function getUpdatePlugin() : PluginBase
    {
        return  $this->plugin;
    }

    public function isEnable() : bool
    {
        return $this->configData["enable"];
    }

    /**
     * @return void
     */
    public function check() : void
    {
        new GetUpdateInfo($this);
    }
}