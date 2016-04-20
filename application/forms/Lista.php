<?php

class Application_Form_Lista extends Zend_Form
{

    public function init()
    {
        
        // Verifica se o campo select esta com o valor null
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
        
        $atividades = new Application_Model_Atividades();
        
        $atividades = $atividades->select();
        
        $atividadesOption = array("-- Selecione --");
        
        // Recuperando a lista de atividades cadastradas
        foreach ($atividades AS $key => $value){
            $atividadesOption[$value['id']] = $value['nome'];
            
            // informa qual o estado atual da atividade
            if($value['state'] == 0){
                $atividadesOption[$value['id']] .= ' -- na lixeira';
            }
            
            if($value['state'] == 3){
                $atividadesOption[$value['id']] .= ' -- arquivado';
            }
            
        }
        
        $this->addElement('select', 'id_atividade', array(
            'label' => 'Atividade',
            'required'  =>  true,
            'filters'   =>  array('StringTrim'),
            'class' => 'form-control',
            'multiOptions' => $atividadesOption,
            'validators' => array($required),
            'decorators' => $this->setColSize(6)            
        ));

        // Caixa de pesquisa com ajax
        $this->addElement('text', 'usuario', array(
            'Label' => 'Usuário AAVE',
            'autocomplete' => 'off',
            'required'  =>  true,
            'filters'   =>  array('StringTrim'),
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
            'title' => 'Data do comparecimento',
            'required' => true,
            'class' => 'form-control',
            'decorators' => $this->setColSize(6)
        ));

        $this->addElement('hidden', 'id_usuario', array(
            'decorators' => $this->setColSize(12)
        ));
        
        
        $this->addElement('hidden', 'created_user_id', array(
            'value' => CURRENT_USER_ID,
            'decorators' => $this->setColSize(12)
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

