<?php

class Application_Model_Administradores extends Application_Model_Model
{
    protected $name;
    protected $namePerfis;

    function __construct()
    {
        parent::__construct();
        $this->name = $this->prefix . '_administradores';
        $this->namePerfis = $this->prefix . '_perfil';
        
    }    

    public function selectAll($filterState = 1, $like = NULL)
    {
        try {
            
            $select = new Zend_Db_Select($this->db);
            
            $select->from(
                array('u' => $this->name), 
                array('id', 'nome','login','email','id_role','state')
            );
            
            $select->joinLeft(
                array('p' => $this->namePerfis),
                'u.id_role = p.id',
                'role'
            );
            
            $select->where('u.state = ?', $filterState);
            
            if(!is_null($like)){                
                $columns = array('u.nome', 'u.login', 'u.email', 'p.role');                
                $select->where($columns[0] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[1] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[2] . ' LIKE ?', '%'. $like .'%' );
            }
            
            $select->order('u.id DESC');
            
            return $select;
            
            //return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    public function selectById($id)
    {        
        $sql = 'SELECT * FROM ' . $this->name . ' WHERE id = ?';
        $result = $this->db->fetchRow($sql,array($id),Zend_Db::FETCH_ASSOC);
        return $result;
    }
    

    /**
     *
     * @param string $id
     * @throws Zend_Exception
     */
    public function selectNameById($id){
        try {
            if ($id) {
                $result = $this->db->fetchOne('SELECT nome FROM '. $this->name .' WHERE id = ?', array(
                    $id
                ), Zend_Db::FETCH_ASSOC);
                return $result;
            }
    
            return false;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    
    public function getPerfis()
    {
        $sql = "SELECT * FROM " . $this->namePerfis;        
        $result = $this->db->fetchAll($sql,NULL,Zend_Db::FETCH_OBJ);        
        return $result;        
    }
    
    /**
     * Method clearData Application_Model_Usuarios
     *
     * @param
     *            array
     * @return array
     */
    private function clearData($data)
    {
        $result = $this->db->describeTable($this->name);
        $cols = array_keys($result);
        $cleardata = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $cols)) {
                $cleardata[$key] = $value;
            }
        }
    
        return $cleardata;
    }
    
    /**
     * Method insert Application_Model_Usuarios
     *
     * @param
     *            array
     * @return boolean
     */
    public function insert($data = array())
    {
        $bind = $this->clearData($data);
        $bind['password'] = sha1($bind['password']);
    
        try {
            if ($this->db->insert($this->name, $bind)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    public function lastInsertId()
    {
        return $this->db->lastInsertId($this->name);
    }
    
    /**
     * Method update Application_Model_Usuarios
     *
     * @param
     *            array
     * @return boolean
     */
    public function update($data = array())
    {
        $bind = $this->clearData($data);
        if (isset($bind['password'])) {
            $bind['password'] = sha1($bind['password']);
        };
    
        unset($bind['id']);
    
        try {
            $where = $this->db->quoteInto('id = ?', $data['id']);
            if ($this->db->update($this->name, $bind, $where)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * Method select Application_Model_Usuarios
     *
     * @param
     *            string
     * @return array
     * @tutorial param $email = null return all rows
     */
    public function select($email = null)
    {
        try {
            if ($email) {
                $result = $this->db->fetchRow('SELECT u.id, u.nome, u.email, u.login, u.id_role, u.state, p.role FROM ' . $this->name . ' AS u LEFT JOIN ' . $this->namePerfis . ' AS p ON u.id_role = p.id WHERE email = ?', array(
                    $email
                ), Zend_Db::FETCH_OBJ);
            } else {
                $this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
                $result = $this->db->fetchAll('SELECT u.id, u.nome, u.email, u.id_role, u.login, u.state, p.role FROM ' . $this->name . ' AS u LEFT JOIN ' . $this->namePerfis . ' AS p ON u.id_role = p.id WHERE 1 ORDER BY id ASC');
            }
    
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * selectByRole
     *
     * @param string $roleName
     * @throws Zend_Exception
     */
    public function selectByRole($roleName = null)
    {
        try {
            if ($roleName) {
                $this->db->setFetchMode(Zend_Db::FETCH_OBJ);
                $result = $this->db->fetchAll('SELECT u.id, u.nome, u.email, u.id_perfil, u.state p.role FROM ' . $this->name . ' AS u LEFT JOIN ' . $this->namePerfis . ' AS p ON u.id_perfil = p.id WHERE p.role = ? ORDER BY u.nome ASC', array(
                    $roleName
                ));
                return $result;
            }
    
            return false;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
};