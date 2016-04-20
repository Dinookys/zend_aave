<?php

class Application_Form_Atividade extends Zend_Form
{

    public function init()
    {

        $this->addElement('text','nome',array(
            'label' => 'Nome',
            'title' => 'Nome da atividade',
            'required' => true,
            'class' => 'form-control',
            'decorators' => $this->setColSize(6)
        ));
        
        $this->addElement('text','data',array(
            'label' => 'Data',
            'title' => 'Data de realização',
            'required' => true,
            'class' => 'form-control',
            'decorators' => $this->setColSize(6)
        ));        

        $this->addElement('textarea','descricao', array(
           'label' => 'Descrição',
           'required' => false,
           'class' =>'form-control',
           'decorators' => $this->setColSize(),
           'rows' => '5'
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

