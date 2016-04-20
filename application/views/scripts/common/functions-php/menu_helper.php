<?php
/**
 * Get Menu Helper 
 * @param string $controllerName
 * @param string $actionName
 * @param array $arrayMenu
 * @param static $baseUrl
 * @return string
 */
function getMenu($controllerName, $actionName, $arrayMenu, $baseUrl){
        
    $html = '';    
    
    foreach ($arrayMenu as $key => $menu){
        if(is_array($menu)){
            
            $html .= '<li>';         
            
            $html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="#">' . $menu['name'];
                $html .= '<span class="caret"></span>';
            $html .= '</a>';

            // Removendo para não entrar no for do submenu            
            unset($menu['name']);
            
                $html .= '<ul class=" dropdown-menu" >';
                    $html .= getMenu($controllerName, $actionName, $menu, $baseUrl);
                    
                $html .= '</ul>';            
            
            $html .= '</li>';
            
        }else{
            
            if($key == $controllerName.'/'.$actionName){
                $html .= '<li class="active" >';
            }elseif($key == $controllerName && $actionName == 'index'){
                $html .= '<li class="active" >';
            }else{
                $html .= '<li>';
            }
            
            $html .= '<a href="'. $baseUrl .'/'. $key .'">' . $menu .'</a>';
            $html .= '</li>';
        }
    }
    
    return $html;
    
}