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

        $events->attach(ModuleEvent::EVENT_LOAD_MODULE, array($this, 'onLoadModule'), 1);
    }

    /**
     * @param ModuleEvent $e
     * @return ConfigListener
     */
    public function onLoadModule(ModuleEvent $e)
    {
        /** @var ConfigListener $configListener */
        $configListener = $e->getParam('configListener');

        $modulePath = str_replace('\\', '/', $e->getModuleName());

        $path = 'module/' . $modulePath . '/config/autoload';

        if(!is_dir($path)) return $configListener;

        foreach (new \DirectoryIterator(getcwd() . '/' . $path) as $fileInfo) {
            if ($fileInfo->isFile() == false || $fileInfo->getExtension() != 'php') {
                continue;
            }
            
            $file = $path . '/' . $fileInfo->getFilename();
            $configListener->addConfigStaticPath($file);
        }

        return $configListener;
    }
}
