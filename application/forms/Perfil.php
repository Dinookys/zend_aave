<?php

class Application_Form_Perfil extends Zend_Form
{

    public function init()
    {

        $this->addElement('text','role',array(
            'label' => 'Nome',
            'title' => 'Nome do perfil',
            'required' => true,
            'class' => 'form-control',
            'decorators' => $this->setColSize(12)
        ));
        
        $this->addElement('textarea','menus',array(
           'label' =>'Menu',
           'description' =>'Ex:  <br>  administradores=Administradores; <br> usuarios=Usuários;  <br>Colocando dentro de submenu: <br> nomedosubmenu.name=nome; <br> nomedosubmenu.subitem=nome do subitem;',
           'class' => 'form-control',
            'value' => 'usuarios="Usuários"',
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
            ),
            'rows' => 5
        ));
        
        $this->addElement('textarea','acl',array(
            'label' =>'Pemissões',
            'description' => 'Para acesso total usar: *<br/>Para acesso unitario usar: administrador,usuarios,eventos (separado por virgula) <br> ação especifica ex: administradores:user',
            'title' =>'',
            'value' => 'administradores.user,usuarios',
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
            ),
            'rows' => 5
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

