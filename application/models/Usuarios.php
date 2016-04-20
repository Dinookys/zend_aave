<?php

class Application_Model_Usuarios extends Application_Model_Model
{
    protected $name;    

    function __construct()
    {
        parent::__construct();
        $this->name = $this->prefix . '_usuarios';
    }    
    
    public function insert($data = array()){
        $bind = array();
        
        if(isset($data['cpf'])){
            $bind['cpf'] = $data['cpf'];
            unset($data['cpf']);
        }
        
        if(isset($data['nome'])){
            $bind['nome'] = $data['nome'];
            unset($data['nome']);
        }
        
        $bind['params'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        $result = $this->db->insert($this->name, $bind);       
        
        if($result){
            return true;
        }
        
        return false;
        
    }

    public function selectAll($filterState = 1, $like = NULL)
    {
        try {           
            
            $select = new Zend_Db_Select($this->db);
            
            $select->from(
                array('u' => $this->name), 
                array('*')
            );
            
            $select->where('u.state = ?', $filterState);
            
            if(!is_null($like)){                
                $columns = array('u.params','u.nome','u.cpf');                
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
    /**
     * @tutorial Pesquisa na coluna NOME da tabela
     * @return array | bool
     * @param string $like 
     */
    public function getNameId($like){
        
        $select = $this->db->select();
        $select->from($this->name,array('id','nome'));
        $select->where('nome LIKE ?', '%'. $like .'%');
        $select->order('nome ASC');
        
        $result = $this->db->fetchAll($select, null, Zend_DB::FETCH_ASSOC);
        
        if($result){
            return $result;
        }        
        
        return false;
    }
    
    /**
     *
     * @param string $id
     * @param bool $bool se true retorna um valor verdadeiro ou falso para consulta
     */
    public function selectById($id){
        
        $sql = 'SELECT * FROM ' . $this->name . ' WHERE id = ?';
        $this->db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $result = $this->db->fetchRow($sql, $id);
        
        if($result){
            $result = array_merge($result, json_decode($result['params'], true));
            unset($result['params']);
            return $result;
        }
        
        return false;
        
    }
    
    /**
     * 
     * @param string $cpf
     * @param bool $bool se true retorna um valor verdadeiro ou falso para retornar a consulta
     */
    public function getByCPF($cpf, $bool = true){
        
        if($bool){    
            $sql = 'SELECT COUNT(id) FROM ' . $this->name . ' WHERE cpf = ?';
            $result = $this->db->fetchOne($sql, $cpf);
            
            if($result){
                return true;
            }
            
            return false;
            
        }else{
            $sql = 'SELECT * FROM ' . $this->name . ' WHERE cpf = ?';
            $result = $this->db->fetchRow($sql, $cpf);
        }
        
        return $result;
    }
    
    public function update($data = array()){
        $update = array();
        
        if(isset($data['id'])){
            $id = $data['id'];
            unset($data['id']);
        }
        
        if(isset($data['cpf'])){
            $update['cpf'] = $data['cpf'];
            unset($data['cpf']);
        }
        
        if(isset($data['nome'])){
            $update['nome'] = $data['nome'];
            unset($data['nome']);
        }
        
        $update['params'] = json_encode($data,JSON_UNESCAPED_UNICODE);
        $where = $this->db->quoteInto('id = ?', $id);        
        $result = $this->db->update($this->name, $update, $where);
        
        if($result){            
           return true;
        }
        
        return false;
        
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
    
}