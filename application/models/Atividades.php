<?php

class Application_Model_Atividades extends Application_Model_Model
{
    protected $name;
    
    function __construct()
    {
        parent::__construct();
        $this->name = $this->prefix . '_atividades';
    }
    

    public function insert($data = array()){    
    
        $result = $this->db->insert($this->name, $data);
    
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
                $columns = array('u.nome','u.data','u.descricao');
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
    

    public function select() {
        $select = $this->db->select();
        $select->from($this->name, array('id','nome','state'));
        return $this->db->fetchAll($select,null, Zend_DB::FETCH_ASSOC);
    }
    
    public function selectById($id){
     
        $select = $this->db->select();
        $select->from($this->name,'*');
        $select->where('id = ?',$id);
        
        try {
            
            $result = $this->db->fetchRow($select, null, Zend_Db::FETCH_ASSOC);
            
            if($result){
                return $result;
            }
            
            return false;
            
        }catch(Zend_Db_Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
        
    }    
    
    public function update($data = array()){
        
        if(isset($data['id'])){
            $id = $data['id'];            
            unset($data['id']);
        }
        
        $where = $this->db->quoteInto('id =?', $id);        
        $result = $this->db->update($this->name, $data, $where);
    
        if($result){
            return true;
        }
    
        return false;
    
    }    

}

