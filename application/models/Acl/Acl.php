<?php
class Application_Model_Acl_Acl extends Zend_Acl{
    
    protected $_role;
    protected $_current_resource;
    protected $_acl;
    
    function __construct()
    {        
        $auth = Zend_Auth::getInstance();        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');        
        $user = $auth->getIdentity();
        $this->_current_resource = $request->getControllerName() . ':' . $request->getActionName();
        
        $resources = $user->acl;
        
        $this->_role = $user->role;
        $this->addRole($this->_role);
        
        if(!in_array($this->_current_resource, $this->getResources())){
            $this->addResource($this->_current_resource);
        }        

        if(isset($resources) && $resources != '*'){
            
            $resources = explode(',', $resources);
            foreach ($resources as $value){
                if(!in_array($value, $this->getResources())){
                    $this->addResource($value);
                }
            }
            
            if(in_array($this->_current_resource, $resources) OR in_array($request->getControllerName(), $resources) ){
                $this->allow($this->_role, $this->_current_resource);
            }else{
                $this->deny($this->_role, $this->_current_resource);
            }
            
        }elseif($resources == '*'){                       
            $this->allow($this->_role, $this->_current_resource);
        }else{                       
            $this->deny($this->_role, $this->_current_resource);
        }

    }
    
    public function isAllowed($role = NULL, $resource = NULL, $privilege = NULL) {
        return parent::isAllowed($this->_role, $this->_current_resource);
    }    
    
    /**
     * Verifica se tem permissão para acessar o conteudo baseado no id ou em ACL
     * @param string $id
     * @param array $ids
     * @return boolean
     */
    public function autorized($id, $ids = array()){
        if(in_array($id, $ids) OR in_array(CURRENT_USER_ROLE, $this->_acl['fullControl'])){
            return true;
        }else{
            return false;
        }
    }
}