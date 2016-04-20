<?php

class Application_Form_Usuario extends Zend_Form
{

    public function init()
    {
        // Verifica se o campo select esta com o valor null   
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
        
        $this->addElement('radio','situacao',array(
            'label' => 'Situação:',
            'label_class' => 'radio-inline',
            'required' => true,
            'default' => '',
            'decorators' => $this->setColSize(12),
            'multiOptions' => array(
                'Vivendo' => 'Vivendo',
                'Convivendo' => 'Convivendo',
                'Participante' => 'Participante'
            ),
            'separator' => ''
        ));
                
        
        $this->addElement('text','nome',array(
            'label'  =>  'Nome',
            'required' => true,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(6)
        ));
        
        $this->addElement('text','data',array(
            'label'  =>  'Data',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(6)
        ));
        
        $this->addElement('text','endereco',array(
            'label'  =>  'Endereço',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(12)
        ));
        
        $this->addElement('text','endereco_referencia',array(
            'label'  =>  'Ponto de refêrencia',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(12)
        ));
        
        $this->addElement('text','telefone',array(
            'label'  =>  'Telefone de contato',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(12)
        ));
        
        $this->addElement('text','rg',array(
            'label'  =>  'RG',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4)
        ));
        
        $this->addElement('text','cpf',array(
            'label'  =>  'CPF',
            'required' => true,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4)
        ));
        
        $this->addElement('text','data_nascimento',array(
            'label'  =>  'Data de nascimento',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4)
        ));
        
        $this->addElement('select','sexo',array(
            'label'  =>  'Sexo',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4),
            'multiOptions' => array(
                '' => '--- Selecione uma opção ---',
                'Masculino' => 'Masculino',
                'Feminino' => 'Feminino'
            )
        ));
        
        $this->addElement('select','estado_civil',array(
            'label'  =>  'Estado Civil',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4),
            'multiOptions' => array(
                '' => '--- Selecione uma opção ---',
                'Solteiro' => 'Solteiro(a)',
                'Casado' => 'Casado(a)',
                'Divorciado' => 'Divorciado(a)',
                'Viuvo' => 'Viuvo(a)',
            )
        ));
        
        $this->addElement('select','escolaridade',array(
            'label'  =>  'Escolaridade',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4),
            'multiOptions' => array(
                '' => '--- Selecione uma opção ---',
                'Sem instrução formal' => 'Sem instrução formal',
                'Ensino Fundamental Incompleto' => 'Ensino Fundamental Incompleto',
                'Ensino Fundamental Completo' => 'Ensino Fundamental Completo',                
                'Ensino Médio Incompleto' => 'Ensino Médio Incompleto',
                'Ensino Médio Completo' => 'Ensino Médio Completo',
                'Ensino Superior Incompleto' => 'Ensino Superior Incompleto',
                'Ensino Superior Completo' => 'Ensino Superior Completo',
                'Pós-graduado' => 'Pós-graduado',
                'Mestrado' => 'Mestrado',
                'Doutorado' => 'Doutorado',
            )
        ));

        $this->addElement('hidden', 'header3', array(
            'description' => '<h3 class="page-header" >Filiação</h3>',
            'ignore' => true,
            'decorators' => array(
                array(
                    'Description',
                    array(
                        'escape' => false,
                        'tag' => 'div',
                        'class' => "col-xs-12"
                    )
                )
            )
        ));

        $this->addElement('text','filiacao_mae',array(
            'label'  =>  'Mãe',
            'required' => false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(6)
        ));
        
        $this->addElement('text','filiacao_pai',array(
            'label'  =>  'Pai',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(6)
        ));

        $this->addElement('hidden', 'header4', array(
            'description' => '<h3 class="page-header" >Conjugue</h3>',
            'ignore' => true,
            'decorators' => array(
                array(
                    'Description',
                    array(
                        'escape' => false,
                        'tag' => 'div',
                        'class' => "col-xs-12"
                    )
                )
            )
        ));

        $this->addElement('text','nome_conjugue',array(
            'label'  =>  'Nome do conjugue',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(6)
        ));


        $this->addElement('hidden', 'line2', array(
            'description' => '<hr/>',
            'ignore' => true,
            'decorators' => array(
                array(
                    'Description',
                    array(
                        'escape' => false,
                        'tag' => 'div',
                        'class' => "col-xs-12"
                    )
                )
            )
        ));

        $this->addElement('text','renda_mensal',array(
            'label'  =>  'Renda mensal',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(6)
        ));

        $this->addElement('text','beneficio',array(
            'label'  =>  'Benefício',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(6)
        ));


        $this->addElement('select','moradia',array(
            'label'  =>  'Tipo de moradia',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'multiOptions' => array(
                '' => '--- Selecione uma opção ---',
                'Propria' => 'Propria',
                'Alugada' => 'Alugada'
            ),
            'decorators' => $this->setColSize(4)
        ));

        $this->addElement('select','prontuario',array(
            'label'  =>  'Prontuário HDT',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4),
            'multiOptions' => array(
                '' => '--- Selecione uma opção ---',
                'HDT' => 'HDT',
                'HC' => 'HC',
                'CTA' => 'CTA',
                'Tratamento privado' => 'Tratamento privado'
            )
        ));

        $this->addElement('text','medico',array(
            'label'  =>  'Médico',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(4)
        ));
        
        $this->addElement('textarea','dependentes',array(
            'label'  =>  'Dependentes',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'rows' => array('rows' => 5),
            'class'     => 'form-control',
            'decorators' => $this->setColSize(12)
        ));
        
        $this->addElement('hidden', 'line3', array(
            'description' => '<h3>Informações aos Visitadores<h3/>',
            'ignore' => true,
            'decorators' => array(
                array(
                    'Description',
                    array(
                        'escape' => false,
                        'tag' => 'div',
                        'class' => "col-xs-12"
                    )
                )
            )
        ));
        
        $this->addElement('radio','visitas',array(
            'label' => 'Receber Visitas?:',
            'label_class' => 'radio-inline',
            'required' => true,            
            'decorators' => $this->setColSize(12),
            'multiOptions' => array(
                'Sim' => 'Sim',
                'Não' => 'Não'           
            ),
            'separator' => ''
        ));
           
        
        $this->addElement('textarea','observacoes',array(
            'label'  =>  'Observações',
            'required'  =>  false,
            'filters'   =>  array('StringTrim'),
            'rows' => array('rows' => 5),
            'class'     => 'form-control',
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
        
        $this->setAttrib('id', 'usuario');
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
