<?php

class Application_Form_Visita extends Zend_Form
{

    public function init()
    {
        
        // Verifica se o campo select esta com o valor null
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
        
        $administrador = new Application_Model_Administradores();
        
        $admins = $administrador->select();
        
        $adminOption = array("-- Selecione --");
        
        foreach ($admins AS $key => $value){
            $adminOption[$value['id']] = $value['nome'];
        }
        
        $this->addElement('select', 'id_responsavel', array(
            'label' => 'Visitante',
            'required'  =>  true,
            'filters'   =>  array('StringTrim'),
            'class' => 'form-control',
            'multiOptions' => $adminOption,
            'validators' => array($required),
            'decorators' => $this->setColSize(6)
        ));

        // Caixa de pesquisa com ajax
        $this->addElement('text', 'usuario', array(
            'Label' => 'Visitado',
            'required'  =>  true,
            'filters'   =>  array('StringTrim'),
            'autocomplete' => 'off',
            'title' => 'Digite para pesquisar',            
            'description' => '<div class="input-group-addon" ><i class="glyphicon glyphicon-search" ></i></div> <ul style="display:none" id="searchResult" ></ul>',
            'class' => 'form-control',
            'decorators' => array(
                'Label',                
                'ViewHelper',
                'Errors',                
                array(
                    'Description',
                    array(
                        'escape' => false,
                        'tag' => ''
                    )
                ),
                array('HtmlTag', array('tag' => 'div', 'class' => 'form-group col-xs-6'))
            )
        ));
        
        $this->addElement('text','data',array(
            'label' => 'Data',
            'required' => true,
            'filters'   =>  array('StringTrim'),
            'class' => 'form-control',
            'decorators' => $this->setColSize(6)
        ));
        
        $this->addElement('textarea','observacoes', array(
           'label' => 'Observações',
           'required' => false,
           'class' =>'form-control',
           'decorators' => $this->setColSize(),
           'rows' => '5'
        ));        

        $this->addElement('hidden', 'id_usuario', array(
            'decorators' => $this->setColSize(2)
        ));
        
        $this->addElement('hidden', 'responsavel', array(
            'decorators' => $this->setColSize(2)
        ));

        $this->addElement('hidden', 'created_user_id', array(
            'value' => CURRENT_USER_ID,
            'decorators' => $this->setColSize(2)
        ));        
        
        $submit = $this->addElement('submit','Cadastrar', array(
            'class' => 'btn btn-primary btn-md'
        ));
        
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            'Description',
            array('HtmlTag', array('tag' => 'div', 'class' => 'form-group'))
        ), array('Cadastrar'), true);
        
        $this->setDecorators(array(
            'FormElements',
            'Form',
            array('HtmlTag', array('tag' => 'div', 'class' => 'panel panel-body panel-default'))
        ));   
        
        $this->setDecorators(array(
            'FormElements',
            'Form',
            array('HtmlTag', array('tag' => 'div', 'class' => 'panel panel-body panel-default'))
        ));        
        
    }
        

    private function setColSize($size = 12, $label = true, $addon = false)
    {
        $decorator = array(
            'Label',
            'ViewHelper',
            'Errors',
            'Description',
            array('HtmlTag', array('tag' => 'div', 'class' => 'form-group col-xs-'. $size .'')),
            array('Description', array('escape'=>false, 'tag'=>'span', 'class' => "input-group-addon"))
    
        );
    
        if(!$label){
            unset($decorator['0']);
        }
    
        if(!$addon){
            unset($decorator['5']);
        }
    
        return $decorator;
    }


}

