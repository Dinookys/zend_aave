<?php

class Application_Model_Index extends Application_Model_Model
{
    protected $tableOptions;
    
    public function __construct(){
        parent::__construct();
        $this->tableOptions = $this->prefix . '_options';
    }
    
    public function getOptions($action) {
        $select = $this->db->select();
        $select->from($this->tableOptions, array('nome'));
        $select->where('action = ?', $action);
        $select->order('nome ASC');
        
        $result = $this->db->fetchAll($select,null,Zend_DB::FETCH_ASSOC);
        
        return $result;
        
    }
    
    public function getOption($name,$action){
        
        $select = $this->db->select();
        $select->from($this->tableOptions, array('nome'));
        $select->where('nome = ?', $name);
        $select->where('action = ?', $action);        
        $select->order('nome ASC');
        
        $result = $this->db->fetchRow($select,null,Zend_DB::FETCH_ASSOC);
        
        return $result;
    }
    
    
    public function insertOption($data){
        
        $result = $this->db->insert($this->tableOptions, $data);
        return $result;
    }
    
    
    public function removeOption($data){       
        
        
        $where = array(
            'action = ?' => $data['action'],
            'nome = ?' => $data['nome']
        );
        
        $result = $this->db->delete($this->tableOptions,$where);
        
        return $result;
    }

}

