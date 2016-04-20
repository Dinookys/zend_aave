<?php

class Application_Model_Login extends Application_Model_Model
{
    protected $name;
    protected $perfilName;
    
    public  function __construct(){
        
        parent::__construct();
        
        $this->name = $this->prefix . '_administradores';
        $this->perfilName = $this->prefix . '_perfil';        
    }
    
    public static function login($login,$senha)
    {
        $model = new self;
        
        // Estancia a conexão com o banco de dados
        $db = Zend_Db_Table::getDefaultAdapter();
        
        // Estancia o Zend_Auth para indica em qual tabela e quais campos fazer a verificação
        $adapter = new Zend_Auth_Adapter_DbTable($db);
                
        $adapter->setTableName($model->name)
                ->setIdentityColumn('login')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('SHA1(CONCAT(?,salt))');
        
        // Atribuindo campo extra para a verificação
        $select = $adapter->getDbSelect();
        $select->where('state = 1');
        
        $adapter->setIdentity($login);
        $adapter->setCredential($senha);
        
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);        
        
        
        if($result->isValid()){
            // Gravando dados na sessão
            $contents = $adapter->getResultRowObject(null,'password');
            
            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $result = $db->fetchRow('SELECT role,menus,acl FROM '. $model->perfilName .' WHERE id = ?', $contents->id_role);            
            $contents->role = $result->role;
            $contents->acl = $result->acl;
            $contents->menus = self::createMenuArray($result->menus);
            
            $auth->getStorage()->write($contents);           
            
            return true;
        }else{
            return $model->getMessages($result);
        }
    }
    
    private function getMessages(Zend_Auth_Result $result){
        switch ($result->getCode())
        {
            case $result::FAILURE_IDENTITY_NOT_FOUND:
                $msg = "Login não encontrado";
                break;
                
            case $result::FAILURE_IDENTITY_AMBIGUOUS:
                $msg = "Login em duplicidade";
                break;
                
            case $result::FAILURE_CREDENTIAL_INVALID:
                $msg = "Senha inválida";
                break;
                
            default:
                $msg = "Login/senha inválidos";                
        }
        
        return $msg;
    }
    
    /**
     * Converte um string em um array, para gerar o menu do perfil
     * @param string $menus
     * @return array
     */
    private function createMenuArray($menus){
        
        $menu_out = array();
        
        if($menus){
            
            $menus = explode(';', trim($menus));            
            
            foreach ($menus as $key => $item){
            
                if(!empty($item)){
            
                    $newItem = explode('=', trim($item));
                    $subitem = explode('.', $newItem[0]);
            
                    if(count($subitem) == 2){
                        $menu_out[$subitem[0]][$subitem[1]] = $newItem[1];
                    }else{
                        $menu_out[$newItem[0]] = $newItem[1];
                    }
                }
            
            }
            
        }

        return $menu_out;        
    }

}

