<?php
namespace Album\Utility;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
class AclService extends Acl{
    public function __construct(){
        $this->addRole(new GenericRole('FANS'));
        $this->addResource(new GenericResource('Album\Controller\AlbumController::addAction'));
        $this->allow('FANS','Album\Controller\AlbumController::addAction');
        
    }
}