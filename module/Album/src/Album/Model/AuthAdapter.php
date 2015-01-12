<?php
namespace Album\Model;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;

class AuthAdapter implements AdapterInterface{
    protected $log;
    protected $pass;

    public function __construct($username, $password){
        $this->log = $username;
        $this->pass = $password;
    }

    public function authenticate(){
        
        if($this->log == 'truc@mail.fr' && $this->pass == 'Az123456'){
            $result = new Result(Result::SUCCESS, array(
                    'login'=>$this->log,
                    'role' =>'FANS'
            ));
        }
        else{
            $result = new Result(Result::FAILURE, $this->log);
        }
        
        return $result;
    }
}