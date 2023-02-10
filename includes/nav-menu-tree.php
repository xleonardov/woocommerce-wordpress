<?php

function buildTree(array &$elements, $parentId = 0)
{
    $branch = array();
    foreach ($elements as &$element) {
        if ($element->menu_item_parent == $parentId) {
            $children = buildTree($elements, $element->ID);
            if ($children) {
                $element->children_arr = $children;
            }
            $branch[$element->ID] = $element;
            unset($element);
        }
    }
    return $branch;
}

function nav_menu_tree($menu_id)
{
    $items = wp_get_nav_menu_items($menu_id);
    return $items ? buildTree($items, 0) : null;
}

add_filter('mount_menu_tree', 'nav_menu_tree');

function check_parent_child_active($item, $current_id)
{
    $found = false;
    if (isset($item->children_arr) && count($item->children_arr) > 0) {
        foreach ($item->children_arr as $it) {
            if (isset($it->children_arr) && count($it->children_arr) > 0) {
                foreach ($it->children_arr as $i) {
                    if ($i->object_id == $current_id) {
                        $found = true;
                    }
                }
            } else if ($it->object_id == $current_id) {
                $found = true;
            }
        }
    } else {
        if ($item->object_id == $current_id) {
            $found = true;
        }
    }
    return $found;
}

add_filter('check_parent_child_active', 'check_parent_child_active', 2, 2);
