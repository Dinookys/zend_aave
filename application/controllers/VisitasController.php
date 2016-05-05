<?php

class VisitasController extends Zend_Controller_Action
{

    protected $_modelAdmin = null;

    protected $_model = null;

    protected $_custom = null;

    protected $data_user = null;

    protected $_actionName = null;

    protected $_FlashMessenger = null;

    protected $_controllerName = null;

    public function preDispatch()
    {
        $auth = Zend_Auth::getInstance();
        $this->view->user = $this->data_user = $auth->getIdentity();
    
        if (! $auth->hasIdentity()) {
            $this->redirect('/login');
        } else {
            $acl = new Application_Model_Acl_Acl();
            if (! $acl->isAllowed()) {
                $this->redirect('/error/forbidden');
            }
        }
        
        parent::preDispatch();
    }

    public function init()
    {
        $this->_modelAdmin = new Application_Model_Administradores();
        $this->_model = new Application_Model_Visitas();
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        
        $this->_custom = $config->getOption('custom');
        
        $this->_FlashMessenger = $this->_helper->getHelper('FlashMessenger');
        
        $this->view->headTitle(strtoupper($this->getRequest()
            ->getControllerName()) . ' | ' . $this->_custom['company_name']);
        
        $this->view->modelAdmin = new Application_Model_Administradores();
        $this->view->controllerName = $this->_controllerName = $this->getRequest()->getControllerName();
        $this->view->actionName = $this->_actionName = $this->getRequest()->getActionName();
        $this->view->messages = $this->_FlashMessenger->getMessages($this->_controllerName);
        $this->view->user = $this->data_user;  
        
    }

    public function indexAction()
    {
        $request = $this->_request;  
        $session = new Zend_Session_Namespace();
        
        $like = NULL;       
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $session->__set($this->_controllerName, $data);            
        }        
        
        $filter = $request->getParam('filter');        
        if($filter == ""){
            $filter = 1;
        }
        
        if($session->getNamespace($this->_controllerName)){
            $data = $session->__get($this->_controllerName);
            $this->view->data = $data;
            $like = $data['search'];
        }
        
        $select = $this->_model->selectAll($filter, $like);
        
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage($this->_custom['itemCountPerPage'])
                  ->setCurrentPageNumber($this->_getParam('page',1));
        
        $this->view->paginator = $paginator;
        $this->view->barTitle = 'Visitas';       
    }

    public function addAction()
    {
        $form = new Application_Form_Visita();
        $request = $this->_request;
        
        if($request->isPost()){
           $data = $request->getPost();
           
           if($form->isValid($data)){
               
               $insert = $this->_model->insert($data);
               
               if($insert){
                   $this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage('Item adicionada com sucesso!');
                   $this->redirect('/' . $this->_controllerName);
               }else{
                   $this->view->messages = array('Problemas ao tentar adicionar item. Tente novamente');
                   $this->view->message_type = 'alert-warning';
               }
           }
           
           $form->populate($data);
        }

        $this->view->form = $form;
        $this->view->barTitle = 'Nova visita';
    }

    public function editAction()
    {
        $form = new Application_Form_Visita();
        $request = $this->_request;
        $id = $request->getParam('id');
        
        if($request->isPost()){
            $data = $request->getPost();
             
            if($form->isValid($data)){                
                $update = $this->_model->update($data);
                 
                if($update){
                    $this->view->messages = array('Atualizado com sucesso!');
                    $this->view->message_type = 'alert-success';
                }else{
                   $this->view->messages = array('Sem alterações');
                   $this->view->message_type = 'alert-info';
                }
            }
        }else{
                $data = $this->_model->selectById($id);
            }
            
        $form->addElement('hidden', 'id', array(
            'value' => $id
        ));
         
        $form->populate($data);
        $this->view->form = $form;
        $this->view->barTitle = 'Editando visita';
    }

    public function deleteAction()
    {
        $request = $this->_request;        

        if ($request->isPost()) {
            $data = array_keys($request->getPost());            
            
            foreach ($data as $k => $id) {
                if($id != 'search'){
                    $this->_model->delete($id);
                }else{
                    unset($data[$k]);
                }
            }
            
            $totalData = count($data);
            $textoRemovido = 'item removido';
            if ($totalData > 1) {
                $textoRemovido = 'itens removidos';
            }

            $this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage(sprintf('%s %s com sucesso!', $totalData, $textoRemovido));
        }

        $this->redirect('/'.$this->_controllerName.'/index/filter/0');
    }

    public function trashAction()
    {
		$request = $this->_request;
		
		if ($request->isPost()) {
			$data = array_keys($request->getPost());
			
			foreach ($data as $k => $id) {
			    if($id != 'search'){
			        $this->_model->trash($id, 0);
			    }else{
			        unset($data[$k]);
			    }
			}			
			
			$totalData = count($data);

			$textoRemovido = 'item movido para lixeira';
			if ($totalData > 1) {
				$textoRemovido = 'itens movidos para lixeira';
			}

			$this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage(sprintf('%s %s com sucesso!', $totalData, $textoRemovido));
		}

		$this->redirect('/'.$this->_controllerName);
    }

    public function unlockAction()
    {
        $this->redirect('/' . $this->_controllerName);
    }

    public function archiveAction()
    {
        $request = $this->_request;
    
        if ($request->isPost()) {
    
            $data = array_keys($request->getPost());
    
            foreach ($data as $k => $id) {
                if($id != 'search'){
                    $this->_model->trash($id, 3);
                }else{
                    unset($data[$k]);
                }
            }
    
            $totalData = count($data);
            $textoRemovido = 'item movido para arquivados';
            if ($totalData > 1) {
                $textoRemovido = 'itens movidos para arquivados';
            }
    
            $this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage(sprintf('%s %s com sucesso!', $totalData, $textoRemovido));
    
        }
    
        $this->redirect('/' . $this->_controllerName);
    }

    public function restoreAction()
    {
		$request = $this->_request;		

		if ($request->isPost()) {
		    
            $data = array_keys($request->getPost());
        
            foreach ($data as $k => $id) {                
                if($id != 'search'){
                    $this->_model->trash($id, 1);
                }else{
                    unset($data[$k]);
                }                
            }
            
            $totalData = count($data);            
            $textoRemovido = 'item restaurado';
            if ($totalData > 1) {
                $textoRemovido = 'itens restaurados';
            }

			$this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage(sprintf('%s %s com sucesso!', $totalData, $textoRemovido));
		}

		$this->redirect('/'.$this->_controllerName);
    }


    public function relatoriosAction()
    {
        $request = $this->_request;  
        $session = new Zend_Session_Namespace();
        
        $like = NULL;
        $data = NULL;
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $session->__set($this->_controllerName, $data);            
        }        
        
        $filter = $request->getParam('filter');        
        if($filter == ""){
            $filter = 1;
        }
        
        if($session->getNamespace($this->_controllerName)){
            $data = $session->__get($this->_controllerName);
            $this->view->data = $data;
            $like = $data['search'];
        }
        
        $select = $this->_model->getRel($data, $like);
        
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage($this->_custom['itemCountPerPage'])
        ->setCurrentPageNumber($this->_getParam('page',1));
        
        $this->view->paginator = $paginator;
        $this->view->barTitle = 'Relatório de Visitas';
    }


}

















