<?php

namespace LabCoding\MergeModuleConfig;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Listener\ConfigListener;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;

class Module implements AutoloaderProviderInterface
{

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => dirname(__DIR__) . '/src',
                ]
            ]
        ];
    }

    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        $events->attach(ModuleEvent::EVENT_LOAD_MODULE, array($this, 'onLoadModule'));
    }


    public function onLoadModule(ModuleEvent $e)
    {
        /** @var ConfigListener $configListener */
        $configListener = $e->getParam('configListener');
        $configListener->onLoadModule($e);

        foreach (new \DirectoryIterator(getcwd() . '/module/' . $e->getModuleName() . '/config') as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->getFilename() == 'module.config.php') {
                continue;
            }
            $file = 'module/' . $e->getModuleName() . '/config/' . $fileInfo->getFilename();
            $configListener->addConfigStaticPath($file);
        }
    }
}