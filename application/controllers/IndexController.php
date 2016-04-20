<?php

class IndexController extends Zend_Controller_Action
{

    protected $db = null;

    protected $data_user = null;
    
    protected $_model = null;

    protected $_custom = null;

    protected $_layout = null;

    protected $_actionName = null;

    protected $_controllerName = null;

    protected $_FlashMessenger = null;

    public function init()
    {
        
        $auth = Zend_Auth::getInstance();
        $this->data_user = $auth->getIdentity();
        
        if(!$auth->hasIdentity()){
            $this->redirect('/login');
        }
        
        
        $this->_model = new Application_Model_Index();
        
        $this->view->user = $this->data_user;
        
        $this->_modelUsers = new Application_Model_Usuarios();
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $this->_custom = $config->getOption('custom');
        
        // Pegando array de configurações para a criação do menu
        $this->view->menu = $config->getOption('menu');
        
        $this->_FlashMessenger = $this->_helper->getHelper('FlashMessenger');        
        $this->view->headTitle(strtoupper($this->getRequest()
            ->getControllerName()) . ' | ' . $this->_custom['company_name']);
        
        $this->view->controllerName = $this->_controllerName = $this->getRequest()->getControllerName();
        $this->view->actionName = $this->_actionName = $this->getRequest()->getActionName();
        $this->view->messages = $this->_FlashMessenger->getMessages($this->_controllerName);
        $this->view->user = $this->data_user; 
    }

    public function indexAction()
    {
        $this->redirect('usuarios');
    }

    public function addOptionAction()
    {
        $this->_helper->layout->setLayout('ajax');
        $request = $this->_request;
        
        if($request->isPost()){
            
            $data = $request->getPost();          
            
            $exist = $this->_model->getOption($data['nome'],$data['action']);
            
            if($exist){
                echo json_encode(array('info' => 'Opção já existe'),JSON_UNESCAPED_UNICODE);
                return;
            }            
            
            $insert = $this->_model->insertOption($data);            
            echo json_encode(array('success' => $insert));
            
            return;
        }
        
        $this->redirect('/');
        
    }

    public function editOptionAction()
    {
        // action body
    }

    public function listOptionsAction()
    {
        // action body
    }

    public function removeOptionAction()
    {
        $this->_helper->layout->setLayout('ajax');
        
        $request = $this->_request;
        
        if($request->isPost()){
            $data = $request->getPost();
            
            $result = $this->_model->removeOption($data);
            
            if($result){
                echo json_encode(array('success' => 'Opção ' . $data['nome'] . ' removida com sucesso!'),JSON_UNESCAPED_UNICODE);
            }else{
                echo json_encode(array('info' => 'Não foi possível remover ' . $data['nome'] . '!'),JSON_UNESCAPED_UNICODE);
            }
            
            return;
        }
        
        $this->redirect('/');
        
    }


}









