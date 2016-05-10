<?php

class Application_Form_Psicologico extends Zend_Form
{

    public function init()
    {

        // Caixa de pesquisa com ajax
        $this->addElement('text', 'usuario', array(
            'Label' => 'Usuário AAVE',
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
            'class' => 'form-control',
            'decorators' => $this->setColSize(6)
        ));
        
        $this->addElement('text','descricao',array(
            'label' => 'Descrição ( relato )',
            'title' => 'Uma breve descrição da consulta ( será apresentado na listagem )',
            'required' => true,
            'class' => 'form-control',
            'decorators' => $this->setColSize(12)
        ));

        $this->addElement('textarea','observacoes', array(
           'label' => 'Conduta',
           'required' => false,
           'class' =>'form-control',
           'decorators' => $this->setColSize(),
           'rows' => '5'
        ));        

        $this->addElement('hidden', 'id_usuario', array(
            'decorators' => $this->setColSize(12)
        ));
        
        $this->addElement('hidden', 'state', array(
            'value' => 1,
            'decorators' => $this->setColSize()
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

