<?php

class UsuariosController extends Zend_Controller_Action
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
        $this->data_user = $auth->getIdentity();
        
        if (! $auth->hasIdentity()) {
            $this->redirect('/login');
        } else {
            $acl = new Application_Model_Acl_Acl();
            if (! $acl->isAllowed()) {
                $this->redirect('/error/forbidden');
            }
        }
        $this->view->user = $this->data_user;
        parent::preDispatch();
    }

    public function init()
    {
        $this->_model = new Application_Model_Usuarios();
        $this->_modelAdmin = new Application_Model_Administradores();
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
        $this->view->barTitle = 'Usuários';       
    }

    public function addAction()
    {
        $form = new Application_Form_Usuario();
        
        $request = $this->_request;
        if ($request->isPost()) {
            $data = $request->getPost();            
            if($form->isValid($data)){
                
                $data['cpf'] = preg_replace('/([^\d]*)/i', "", $data['cpf']);
                $exist = $this->_model->getByCPF($data['cpf'], false);
                 
                if(!$exist){                    
                    $insert = $this->_model->insert($data);                
                    if($insert){
                        $this->view->messages = $this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage('Cadastro realizado com sucesso!');
                        $this->view->message_type = 'alert-success';                    
                        $this->redirect('/' . $this->_controllerName . '/edit/' . $this->_model->lastInsertId());                    
                    }else{
                        $this->view->messages = array('Falha ao efetuar cadastrado');
                        $this->view->message_type = 'alert-warning';
                    }                
                }else{
                    $this->view->messages = array('CPF já cadastrado para: <a href="'. $this->view->baseUrl() .'/' . $this->_controllerName . '/edit/id/' . $exist['id'].'" >'. $exist['nome'] .'</a>');
                    $this->view->message_type = 'alert-warning';
                }                
            }else{
                $this->view->messages = array('Alguns campos não foram preenchidos, por favor verifique!');
                $this->view->message_type = 'alert-warning';
            }
            
            $form->populate($data);
        }
        
        $this->view->barTitle = 'Cadastro de usuário';
        $this->view->cadastroForm = $form;
    }

    public function editAction()
    {
        $form = new Application_Form_Usuario();
        $request = $this->_request;
        $id = $request->getParam('id');
        
        if ($request->isPost()) {
            $data = $request->getPost();
            
            if($form->isValid($data)){
                
                $data['cpf'] = preg_replace('/([^\d]*)/i', "", $data['cpf']);
                $exist = $this->_model->getByCPF($data['cpf'], false);  
                
                if($exist && $exist['id'] == $data['id'] OR empty($exist)){                    
                
                    $update =  $this->_model->update($data);                    
                    if($update){
                       $this->view->messages = array('Atualizado com sucesso!');
                       $this->view->message_type = 'alert-success';
                    }
                    
                }else{
                    $this->view->messages = array('CPF já cadastrado para: <a href="'. $this->view->baseUrl() .'/' . $this->_controllerName . '/edit/id/' . $exist['id'].'" >'. $exist['nome'] .'</a>');
                    $this->view->message_type = 'alert-warning';
                }
                
            }else{
                $this->view->messages = array('Alguns campos não foram preenchidos, por favor verifique!');
                $this->view->message_type = 'alert-warning';
            }
                
        } else {
            $data = $this->_model->selectById($id);            
        }
        
        $form->addElement('hidden','id',array(
            'value' => $id
        ));
        
        // se for vazio redireciona para a index
        if ($data) {            
            $form->populate($data);
            $this->view->barTitle = 'Editando usuário';
            $this->view->editForm = $form;
            return false;
        }
        
        $this->redirect('/' . $this->_controllerName);        
        
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
		$model = new Application_Model_Usuarios();

		if ($request->isPost()) {
			$data = array_keys($request->getPost());
			
			foreach ($data as $k => $id) {
			    if($id != 'search'){
			        $model->trash($id, 0);
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
        $this->redirect('/usuarios/index');
    }

    public function archiveAction()
    {
        $request = $this->_request;
        $model = new Application_Model_Usuarios();
    
        if ($request->isPost()) {
    
            $data = array_keys($request->getPost());
    
            foreach ($data as $k => $id) {
                if($id != 'search'){
                    $model->trash($id, 3);
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
		$model = new Application_Model_Usuarios();

		if ($request->isPost()) {
		    
            $data = array_keys($request->getPost());
        
            foreach ($data as $k => $id) {                
                if($id != 'search'){
                    $model->trash($id, 1);
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

    public function getUsuariosAction()
    {
        $this->_helper->layout->setLayout('ajax');
        $request = $this->_request;
        
        if($request->isPost()){
            $data = $request->getPost();    
            $result = $this->_model->getNameId($data['like']);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);            
            return false;
        }
        
        $this->redirect('/');
        
    }

    public function resumoAction()
    {
        $request = $this->_request;
        
        $id = $request->getParam('id');
        
        if($id){
            // Iniciando Model            
            $modelSocial = new Application_Model_Social();
            $modelPsicologico = new Application_Model_Psicologico();
            $modelLista = new Application_Model_Lista();
            $modelJuridico = new Application_Model_Juridico();
            $modelVisita = new Application_Model_Visitas();
            $modelDoacoes = new Application_Model_Doacoes();
            
            // Get Arrays por id
            $this->view->usuarioAave = $this->_model->selectById($id);
            $this->view->social = $modelSocial->selectByUserId($id);
            $this->view->psicologico = $modelPsicologico->selectByUserId($id);
            $this->view->lista = $modelLista->selectByUserId($id);
            $this->view->visitas = $modelVisita->selectByUserId($id);
            $this->view->juridico = $modelJuridico->selectByUserId($id);        
            $this->view->doacoes = $modelDoacoes->selectByUserId($id);
            
            
            $this->view->barTitle = 'Histórico';
            
        }else{
            $this->redirect('/' . $this->_controllerName);
        }
        
    }


}



