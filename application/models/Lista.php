<?php

class Application_Model_Lista extends Application_Model_Model
{
    protected $name;
    protected $usuario;
    protected $atividade;
    
    function __construct()
    {
        parent::__construct();
        $this->name = $this->prefix . '_lista';
        $this->usuario = $this->prefix . '_usuarios';
        $this->atividade = $this->prefix . '_atividades';
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
                array('u' => $this->usuario),
                array('u.nome')
                );
            $select->joinLeft(array('l' => $this->name), 'u.id = l.id_usuario');
            $select->joinLeft(
                array('a' => $this->atividade), 
                'a.id = l.id_atividade',
                array('a.nome AS atividade', 'a.descricao')
                );
    
            $select->where('l.state = ?', $filterState);
    
            if(!is_null($like)){
                $columns = array('u.nome','a.nome','l.data','a.descricao');
                $select->where($columns[0] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[1] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[2] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[3] . ' LIKE ?', '%'. $like .'%' );
            }
    
            $select->order('l.id DESC');
    
            return $select;
    
            //return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    public function getRel($data = array(), $like = NULL)
    {
        try {
    
            $select = new Zend_Db_Select($this->db);

            $select->from(
                array('u' => $this->usuario),
                array('u.nome')
                );
            $select->joinLeft(array('l' => $this->name), 'u.id = l.id_usuario');
            $select->joinLeft(
                array('a' => $this->atividade),
                'a.id = l.id_atividade',
                array('a.nome AS atividade', 'a.descricao')
                );
    
            $select->where('l.state = ?', isset($data['state']) ? $data['state'] : 1);
            
            if(isset($data['data-in']) && !empty($data['data-in'])){
                $data_in = explode('/', $data['data-in']);
                $data_in = implode('-', array_reverse($data_in));
    
                $select->where("STR_TO_DATE(l.data,'%d/%m/%Y') >= ?", $data_in);
            }
    
            if(isset($data['data-out']) && !empty($data['data-out'])){
                $data_out = explode('/', $data['data-out']);
                $data_out = implode('-', array_reverse($data_out));
    
                $select->where("STR_TO_DATE(l.data,'%d/%m/%Y') <= ?", $data_out);
            }
    
            if(!is_null($like) && !empty($like)){
                $columns = array('u.nome','a.nome','l.data','a.descricao');
                $select->where($columns[0] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[1] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[2] . ' LIKE ?', '%'. $like .'%' );
                $select->orWhere($columns[3] . ' LIKE ?', '%'. $like .'%' );
            }
    
    
            $select->order('l.id DESC');
    
            return $select;
    
            //return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
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
    
    public function selectByUserId($id){
         
        $select = $this->db->select();
        $select->from(array('u' => $this->usuario),array('u.nome'));
        $select->joinLeft(array('l' => $this->name ), 'u.id = l.id_usuario');
        $select->joinLeft(array('a' => $this->atividade), 'a.id = l.id_atividade', array('a.nome AS atividade', 'a.data'));
        $select->where('u.id = ?',$id);
    
        try {
    
            $result = $this->db->fetchAll($select, null, Zend_Db::FETCH_ASSOC);
    
            if($result){
                return $result;
            }
    
            return false;
    
        }catch(Zend_Db_Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    
    }
    
    /**
     *
     * @param string $id_usuario
     * @param string $id_atividade
     * @throws Exception
     * @return int|boolean
     */
    public function _exist($id_usuario, $id_atividade){
         
        $select = $this->db->select();
        $select->from($this->name,'id');
        $select->where('id_usuario = ?',$id_usuario);
        $select->where('id_atividade = ?',$id_atividade);
    
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

