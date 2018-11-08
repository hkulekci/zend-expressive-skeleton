<?php
namespace CoreComponent;

/**
 * Core Modülü
 *
 * @package CoreComponent
 */
class ModuleConfig
{
    public function __invoke()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
