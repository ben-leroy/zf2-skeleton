<?php
namespace Album\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class AuthController extends AbstractActionController{
    public function indexAction(){
        $form = $this->createForm();
        
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            
            var_dump($form->isValid());
            var_dump($form->getInputFilter()->getValue('login'));
            var_dump($form->get('login'));
            
            exit();
        }else{
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
                    array('name' => 'alnum')
                ),
                'filters' => array(
                    array('name' => 'digits')
                )
            )
        );
        
        $form->setInputFilter($inputFilter);
        return $form;
    }
}