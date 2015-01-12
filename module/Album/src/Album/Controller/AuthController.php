<?php
namespace Album\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Album\Model\PasswordValidator;
use Album\Model\AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;

class AuthController extends AbstractActionController{
    public function indexAction(){
        $form = $this->createForm();
        
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            
            if($form->isValid()){
                //code authentification
                $serviceManager = $this->getServiceLocator();
                $auth = $serviceManager->get('AlbumAuth');
                //$auth = new AuthenticationService();
                $auth->setAdapter(new AuthAdapter($form->get('login')->getValue(), $form->get('password')->getValue()));
                //$auth->setStorage(new Session());
                if($auth->hasIdentity()){
                    return $this->redirect()->toRoute('album', array('action' => 'add'));    
                }else{
                    $auth->setAdapter(new AuthAdapter($form->get('login')->getValue(), $form->get('password')->getValue()));
                    $r = $auth->authenticate();
                    if($r->isValid()){
                        //return $this->redirect()->toRoute('album');
                        return $this->redirect()->toRoute('album', array('action' => 'add'));
                    }
                    else{
                        //mauvais log/pass
                        $this->flashMessenger()->addErrorMessage('Erreur authentification');
                        return array('formAuth'=>$form);
                    }
                }
                    
            }else{//mauvaise saisie
                return array('formAuth'=>$form);
            }
            
        }else{//affichage du formulaire
            return array('formAuth'=>$form);
        } 
    }
    
    protected function createForm(){
        $login = new Element('login');
        $login->setLabel('Votre Identifiant :');
        $login->setAttributes(array(
                'type'=> 'text',
                'placeholder'=> 'votre login'));
        
        $form = new Form('identification');
        $form->add($login);
        $form->add(array( 
            'name' => 'password', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'placeholder' => 'votre mot de passe', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Votre Mot de Passe', 
            ), 
        ));
        
//         $inputLogin = new Input('login');
//         $inputLogin->setRequired(true);
//         $inputLogin->getValidatorChain()->attachByName('emailaddress');
//         $inputLogin->getFilterChain()->attachByName('alpha');
        $inputFilter = new InputFilter();
//        $inputFilter->add($inputLogin);
        $inputFilter->add(array(
                'name' => 'login',
                'required' => true,
                'validators' => array(
                    array('name' => 'emailaddress')
                ),
            )
        );
        $passValidator = new PasswordValidator();
        $inputFilter->add(array(
                'name' => 'password',
                'validators' => array(
                        $passValidator
                ),
        ));
        
        $form->setInputFilter($inputFilter);
        return $form;
    }
}

