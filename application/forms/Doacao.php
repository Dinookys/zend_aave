<?php

class Application_Form_Doacao extends Zend_Form
{

    public function init()
    {
        
        $modelIndex = new Application_Model_Index();
        
        
        $optionsTipo = array('Dinheiro' => 'Dinheiro', 'Medicamento' => 'Medicamento', 'Outros' => 'Outros');        
        
        
        $getOptions = $modelIndex->getOptions('doacao_tipo_option');
        
        
        if(is_array($getOptions) && !empty($getOptions)){            
            $optionsTipo = array();            
            foreach ($getOptions AS $key => $option){   
                $optionsTipo['' . $option['nome'] . ''] = $option['nome'];
            }
        }
        

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
        
        $this->addElement('select', 'tipo', array(
            'label' => 'Tipo',
            'required'  =>  true,
            'title' => 'Tipo de doação',
            'filters'   =>  array('StringTrim'),
            'class' => 'form-control',
            'multiOptions' => $optionsTipo,
            'description' => $this->manageOptions('doacao_tipo_option','tipo'), 
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
        
        $this->addElement('text','valor', array(
           'label' => 'Valor',
           'required' => false,
           'class' =>'form-control',
           'decorators' => $this->setColSize(6)
        ));        

        $this->addElement('text','descricao',array(
            'label' => 'Descrição',
            'title' => 'Uma breve descrição da doação ( será apresentado na listagem )',
            'required' => true,
            'class' => 'form-control',
            'decorators' => $this->setColSize(12)
        ));

        $this->addElement('hidden', 'id_usuario', array(
            'decorators' => $this->setColSize(3)
        ));

        $this->addElement('hidden', 'created_user_id', array(
            'value' => CURRENT_USER_ID,
            'decorators' => $this->setColSize(3)
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
    
    /**
     * manageOptions add input e buttons abaixo do campo select use na Key description do array do elemento
     * @param string $inputName
     * @param string $selectName
     * @return string
     */
    private function manageOptions($inputName = null,$selectName = null){
        
        if($inputName && $selectName){        
            $html = '<input disabled="disabled" title="Digite o nome da opção e clique em adicionar" name="'. $inputName .'" class="form-control hide change-option" />';
            $html .= '<a data-input="'. $inputName .'" data-select="'. $selectName .'" class="btn btn-default add-option" href="#">Adicionar opção</a>';
            $html .= '<a data-input="'. $inputName .'" data-select="'. $selectName .'" class="btn btn-default remove-option" href="#">Remover opção</a>';
            
            return $html;
        }        
        
        return null;
        
    }


}

