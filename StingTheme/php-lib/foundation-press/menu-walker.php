<?php
/**
 * copied from the MIT Licensed foundation press theme (can be found here: https://github.com/olefredrik/FoundationPress)
 * Customize the output of menus for Foundation top bar
 * Edited by Teddy
 */
class top_bar_walker extends Walker_Nav_Menu {

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $element->has_children = !empty( $children_elements[$element->ID] );
        $element->classes[] = ( $element->current || $element->current_item_ancestor ) ? 'active' : '';
        $element->classes[] = ( $element->has_children && $max_depth !== 1 ) ? 'has-dropdown' : '';
		$element->classes[1] = '';//remove the menu-item class from the list
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
    
    function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $item_html = '';
        parent::start_el( $item_html, $object, $depth, $args ); 
        //$output .= ( $depth == 0 ) ? '<li class="divider"></li>' : '';
        
        $classes = empty( $object->classes ) ? array() : (array) $object->classes;  
        if( in_array('label', $classes) ) {
            //$output .= '<li class="divider"></li>';
            $item_html = preg_replace( '/<a[^>]*>(.*)<\/a>/iU', '<label>$1</label>', $item_html );
        }
        
    if ( in_array('divider', $classes) ) {
        $item_html = preg_replace( '/<a[^>]*>( .* )<\/a>/iU', '', $item_html );
    }
        $output .= $item_html;
    }
    
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= "\n<ul class=\"sub-menu dropdown\">\n";
    }
    
}
?>