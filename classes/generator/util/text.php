<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 * Description of table
 *
 * @author burningface
 */
class Generator_Util_Text {
    
    public static function upperFirst($string) {
        return ucfirst(strtolower($string));
    }
    
    public static function space($num=0){
        $space = "";
        for($i=1; $i<=$num; ++$i){
            $space .= " ";
        }
        return $space;
    }
}

?>