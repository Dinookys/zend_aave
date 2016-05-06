<?php

class LoginController extends Zend_Controller_Action
{

    protected $_custom;

       
    public function init()
    {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $this->_custom = $config->getOption('custom');
        $this->_helper->layout->setLayout('layout-login');
        $this->view->headTitle($this->getRequest()->getControllerName() . ' | ' . $this->_custom['company_name'] );
    }

    public function indexAction()
    {
        
        $form = new Application_Form_Login;
        $request = $this->_request;
        $auth = Zend_Auth::getInstance();
        
        if($request->isPost())
        {
            $data = $request->getPost();
             
            if($form->isValid($data))
            {
                $data = $form->getValues();                
                $login = Application_Model_Login::login($data['user'], $data['senha']);
                
                if($login === true)
                {
                    $this->redirect('/index');
                }else{
                    $this->view->messages = array($login);                    
                    $form->populate($data);
                }
            }            
        }
        
        if($auth->hasIdentity()){
            $this->redirect('/index');
        }
        
        $this->view->form = $form;
        $this->view->logourl = $this->_custom['logourl'];
        $this->view->company_name = $this->_custom['company_name'];       
        
    }
    
    public function logoutAction()
    {
         $auth = Zend_Auth::getInstance();
         $auth->clearIdentity();
         Zend_Session::destroy();
         $this->redirect('/index');
    }

}

