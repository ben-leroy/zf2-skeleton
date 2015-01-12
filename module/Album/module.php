<?php
namespace Album;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Album\Model\Album;
use Album\Model\Album\Utility;
use Album\Model\AlbumTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Album\Utility\AclService;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function init(ModuleManager $mm){
        $eventManager = $mm->getEventManager();
        $eventManager->attach('loadModules.post',array($this,'postInit'),-1000);
    }
    
    public function postInit(ModuleEvent $e){
       // var_dump($e);
        //exit;
    }
    
    public function getAutoloaderConfig()
    {
        return array(
                'Zend\Loader\ClassMapAutoloader' => array(
                        __DIR__ . '/autoload_classmap.php',
                ),
                'Zend\Loader\StandardAutoloader' => array(
                        'namespaces' => array(
                                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                        ),
                ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
                'factories' => array(
                        'Album\Model\AlbumTable' =>  function($sm) {
                            $tableGateway = $sm->get('AlbumTableGateway');
                            $table = new AlbumTable($tableGateway);
                            return $table;
                        },
                        'AlbumTableGateway' => function ($sm) {
                            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                            $resultSetPrototype = new ResultSet();
                            $resultSetPrototype->setArrayObjectPrototype(new Album());
                            return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                        },
                        'AlbumAuth' => function($sm){
                            $auth = new AuthenticationService();
                            $auth->setStorage(new Session());
                            return $auth;
                        },
                        'AclService'=>function($m){
                            $acl = new AclService();
                            return $acl;
                        }
                ),
        );
    }
}