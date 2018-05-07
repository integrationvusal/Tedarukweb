<?php
namespace app\views\widgets;
use jewish\backend\CMS;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class HTML{

    public static function renderTree($data, $art_cats = [], $all=false)
    {
        $html = '';

        if($all) $html .= '<div class="menu-tree-item">
                            <p><input class="all" type="checkbox"/>
                                <label>'.CMS::t('filter_all').'</p>';

        $html .= '<div class="menu-tree-item">';
        foreach ($data as $item) {
            $is_pos_selected = false;

            if (empty($_POST['CSRF_token'])) {
                $is_pos_selected = in_array($item['id'], $art_cats);
            } 
            elseif(isset($_POST['cats'])) {
                $is_pos_selected = in_array($item['id'], $_POST['cats']);
            }

            $html .= '<p>
                <input type="checkbox" name="cats[]"
                value="'. $item['id'] .'" ' . (($is_pos_selected) ? "checked=checked'" : "") . '
                id="multiCheckboxCat_'. $item['id'] .'" />
                <label for="multiCheckboxCat_' . $item['id'] . '">' . $item['name'] . '</p>';

            if (!empty($item['children'])) {
                $html .= self::renderTree($item['children'], $art_cats);
            }

        }
        $html .= '</div>';
        
        if($all) $html .= '</div>';

        return $html;
    }
}

?>