<?php
/**
 * UENF Menu Walker
 * 
 * @package UENF_Geral
 */

if (!class_exists('UENF_Menu_Walker')) {
    /**
     * Classe para gerenciar o menu de navegação personalizado
     */
    class UENF_Menu_Walker extends Walker_Nav_Menu {
        
        /**
         * ID do item atual para associar com submenus
         */
        private $current_item_id = null;
        
        /**
         * Inicia a lista antes dos elementos serem adicionados.
         */
        public function start_lvl(&$output, $depth = 0, $args = null) {
            $indent = str_repeat("\t", $depth);
            // Adiciona ID para associar com aria-controls
            $submenu_id = isset($this->current_item_id) ? 'submenu-' . $this->current_item_id : '';
            $id_attr = $submenu_id ? ' id="' . esc_attr($submenu_id) . '"' : '';
            $output .= "\n$indent<ul class=\"sub-menu depth-{$depth}\"$id_attr>\n";
        }

        /**
         * Inicia o elemento.
         */
        public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
            $indent = ($depth) ? str_repeat("\t", $depth) : '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item';
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'depth-' . $depth;

            // Adiciona classes para itens com submenu
            if ($args->walker->has_children) {
                $classes[] = 'menu-item-has-children';
                $classes[] = 'page_item_has_children';
                
                // Armazena o ID do item atual para associar com submenu
                $this->current_item_id = $item->ID;
                
                // Adiciona classe para primeiro nível
                if ($depth === 0) {
                    $classes[] = 'menu-item-has-children--top';
                }
                
                // Adiciona classe para subníveis
                if ($depth > 0) {
                    $classes[] = 'menu-item-has-children--sub';
                }
            }

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

            $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            $output .= $indent . '<li' . $id . $class_names .'>';

            $atts = array();
            $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
            $atts['target'] = ! empty($item->target)     ? $item->target     : '';
            $atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
            $atts['href']   = ! empty($item->url)        ? $item->url        : '';
            $atts['class']  = 'menu-link';

            if ($args->walker->has_children) {
                $atts['aria-haspopup'] = 'true';
                $atts['aria-expanded'] = 'false';
                $atts['role'] = 'button';
                $atts['tabindex'] = '0';
            }

            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
            
            // Adiciona toggle de submenu para itens com submenu
            if ($args->walker->has_children) {
                $submenu_id = 'submenu-' . $item->ID;
                $item_output .= '<span class="submenu-toggle" role="button" tabindex="0" aria-expanded="false" aria-controls="' . $submenu_id . '" aria-label="Abrir submenu de ' . esc_attr($item->title) . '">';
                $item_output .= '<span class="screen-reader-text">Abrir submenu</span>';
                $item_output .= '</span>';
            }
            
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }
    }
}
