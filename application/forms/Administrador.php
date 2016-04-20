<?php

class Application_Form_Administrador extends Zend_Form
{

    public function init()
    {
        // Verifica se o campo select esta com o valor null   
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
        
        // Pega a lista de perfis no banco de dados na tabela #__perfis
        $perfis = new Application_Model_Administradores();        
        $lista_options = $perfis->getPerfis();
        
        $options_perfil = array("-- Selecione --");
        
        foreach ($lista_options as $option){
            $options_perfil[$option->id] = $option->role;
        }
        
        $this->addElement('text','nome',array(
            'label'  =>  'Nome',
            'required'  =>  true,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control'
        ));
        
        $this->addElement('text','login',array(
            'label'  =>  'Login',
            'required'  =>  true,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control'
        ));

        $this->addElement('text','email',array(
            'label'  =>  'Email',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control'
        ));
        
        $this->addElement('select','id_role',array(
            'label'  =>  'Perfil',
            'required'  =>  true,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'multiOptions' => $options_perfil,
            'validators' => array($required)
        )); 
        
        $this->addElement('select','state',array(
            'label'  =>  'Habilitado',
            'required'  =>  true,     
            'class'     => 'form-control',
            'multiOptions' => array('0'=>'não', '1'=>'sim'),            
        ))
        ->setDefault('acesso', '0');        
        
        
        $this->addElement('password','password',array(
            'label'  =>  'Senha',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'autocomplete' => 'off',
            'validators' => array(
                array('StringLength', false, array('min' => 4, 'max' => 10))
            )
        ));

        $this->addElement('password','confirm',array(
            'label'  =>  'Confirma Senha',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'validators' => array(
                array('identical', false, array('token' => 'password'))
            )
        ));
        
        $submit = $this->addElement('submit','Cadastrar', array(
            'class' => 'btn btn-primary btn-md'            
        ));
        
        $this->setElementDecorators(array(
            'ViewHelper',            
            'Errors',
            'Label',
            'Description',
            array('HtmlTag', array('tag' => 'div', 'class' => 'form-group'))            
        ), array('Cadastrar'), false);   
        
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
        
        $this->setAttrib('id', 'add_usuario');
        $this->setAttrib('class', 'form');
        $this->setMethod('post');
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

