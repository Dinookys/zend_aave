<?php
abstract class Application_Model_Model{
    
    protected $prefix;
    protected $params;
    protected $db;
    protected $name;
    
    function __construct(){
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->params = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $this->prefix = $this->params->getOption('prefix');
    }
    
    /**
     * Method trash Application_Model_Model
     *
     * @param int $id
     * @param int $state
     * @throws Zend_Exception
     */
    public function trash($id, $state = 0)
    {
        try {
            $where = $this->db->quoteInto('id = ?', $id);
            $bind = array(
                'state' => $state
            );
            $this->db->update($this->name, $bind, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    

    /**
     * Method remove Application_Model_Model
     *
     * @param
     *            int
     * @return boolean
     */
    public function delete($id)
    {
        try {
            $where = $this->db->quoteInto('id = ?', $id);
            $this->db->delete($this->name, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * Method lastInsertId Application_Model_Model
     *     
     * @return string
     */
    public function lastInsertId() {
        return $this->db->lastInsertId($this->name);
    }
    
}