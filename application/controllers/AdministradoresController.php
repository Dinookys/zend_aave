<?php

class AdministradoresController extends Zend_Controller_Action
{

    protected $_modelUsers = null;

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
        $this->_modelUsers = new Application_Model_Administradores();
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        
        $this->_custom = $config->getOption('custom');
        
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
        $request = $this->_request;        
        
        $like = NULL;
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $like = isset($data['search']) ? $data['search'] : NULL;
            $this->view->data = $data;
        }
        
        $filter = $request->getParam('filter');
        if($filter == ""){
            $filter = 1;
        }
        
        $select = $this->_modelUsers->selectAll($filter, $like);
        
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
        $paginator->setItemCountPerPage($this->_custom['itemCountPerPage'])
                  ->setCurrentPageNumber($this->_getParam('page',1));
        
        $this->view->paginator = $paginator;
        $this->view->barTitle = 'Administradores';       
    }

    public function addAction()
    {
        $form = new Application_Form_Administrador();
        $request = $this->_request;
        if ($request->isPost()) {
            $data = $request->getPost();
            
            if ($form->isValid($data)) {
                if (! $this->_modelUsers->select($data['email'])) {
                    $this->_modelUsers->insert($data);                                        
                    $this->_FlashMessenger->setNamespace('index')->addMessage('Cadastrado realizado com sucesso.');
                    $this->view->message_type = 'alert-success';
                    $this->redirect($this->_controllerName . '/edit/id/'.$this->_modelUsers->lastInsertId());
                } else {
                    $this->view->messages = array('O email <b>' . $data['email'] . '</b> já está cadastrado');                    
                }
            } else {
                $form->populate($data);
            }
        }
        
        $this->view->barTitle = 'Adicionando Administrador';
        $this->view->cadastroForm = $form;
    }

    public function editAction()
    {
        $form = new Application_Form_Administrador();
        $request = $this->_request;
        
        $id = $request->getParam('id');

        $form->addElement('hidden','id',array(
            'value' => $id
        ));        
        
        if ($request->isPost()) {            
            $data = $request->getPost();
            
            if(empty($data['password'])){
                unset($data['password']);
            }
            
            if ($form->isValid($data)) {
                $checkData = $this->_modelUsers->select($data['email']);
            
                if (!empty($checkData) && $checkData->id != $data['id'] && $checkData->email == $data['email']) {
                    $this->view->messages = array('O email <b>' . $data['email'] . '</b> já está cadastrado');
                }else {
                    $this->_modelUsers->update($data);
                    $this->view->message_type = 'alert-success';
                    $this->view->messages = array('Atualizado com sucesso!');
                }
            } else {
                $form->populate($data);
            }
        } else {
            $data = $this->_modelUsers->selectById($id);
            // se for vazio redireciona para a index
            if ($data) {                
                $form->populate($data);
            } else {
                $this->redirect('/'. $this->_controllerName);
            }
        }
        
        $this->view->barTitle = 'Editando usuário';
        $this->view->editForm = $form;
    }

    public function deleteAction()
    {
        $request = $this->_request;
        
        if ($request->isPost()) {
            $data = array_keys($request->getPost());
            $totalData = count($data);
            
            $textoRemovido = 'item removido';
            if ($totalData > 1) {
                $textoRemovido = 'itens removidos';
            }
            
            foreach ($data as $id) {
                if ($id != $this->data_user->id) {
                    $this->_modelUsers->delete($id);
                } else {
                    $totalData = $totalData - 1;
                    $message = sprintf('O id <b> %1s </b> não pode ser excluido', $id);
                    $this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage($message);
                }
            }
            
            $this->_FlashMessenger->setNamespace($this->_controllerName)->addMessage(sprintf('%s %s com sucesso!', $totalData, $textoRemovido));
        }
        
        $this->redirect($this->_controllerName . '/index/filter/0');
    }

    public function trashAction()
    {
		$request = $this->_request;
		$model = new Application_Model_Administradores();

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
        $this->redirect('/'.$this->_controllerName);
    }
    
    public function archiveAction()
    {
        $request = $this->_request;
        $model = new Application_Model_Administradores();
    
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
    
        $this->redirect('/'.$this->_controllerName);
    }

    public function restoreAction()
    {
		$request = $this->_request;
		$model = new Application_Model_Administradores();

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
    
    public function userAction()
    {
        $form = new Application_Form_PerfilUsuario();
        $request = $this->_request;
         
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($form->isValid($data)) {
                $checkData = $this->_modelUsers->select($data['email']);
    
                if ($checkData->id != $data['id'] && $checkData->email == $data['email']) {
                    $this->_FlashMessenger->setNamespace($this->_actionName)->addMessage('O email <b>' . $data['email'] . '</b> já está cadastrado');
                } else {
                    $this->_modelUsers->update($data);
                    $this->view->message_type = 'alert-success';
                    $this->_FlashMessenger->setNamespace($this->_actionName)->addMessage('Atualizado com sucesso!');
                }
            } else {
                $form->populate($data);
            }
        } else {
            $data = $this->_modelUsers->selectById($this->data_user->id);
    
            if ($data) {
                $form->populate($data);
            } else {
                $this->redirect('/index');
            }
        }
         
        $this->view->barTitle = 'Editando administrador';
        $this->view->formUser = $form;
    }
}