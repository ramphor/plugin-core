<?php
namespace Ramphor\Core\UI;

use WP_Post;
use WP_User;
use WP_Term;

class MenuItemRenderer
{
    protected function check_user_can_edit()
    {
        return true;
    }

    protected function detect_current_object()
    {
        $queried_object = get_queried_object();
        if (is_a($queried_object, WP_Post::class)) {
            $post_type = get_post_type_object($queried_object->post_type);
            return array(
                'edit_label' => $post_type->labels->edit_item,
                'url' => get_edit_post_link($queried_object),
            );
        } elseif (is_a($queried_object, WP_User::class)) {
            return array(
                'edit_label' => __('Edit User'),
                'url' => get_edit_user_link($queried_object->ID),
            );
        } elseif (is_a($queried_object, WP_Term::class)) {
            $taxonomy = get_taxonomy($queried_object->taxonomy);
            return array(
                'edit_label' => $taxonomy->labels->edit_item,
                'url' => get_edit_term_link($queried_object->term_id, $queried_object->taxonomy),
            );
        }
    }

    public function render($item_output, $item, $depth)
    {
        switch ($item->type) {
            case 'quick_edit':
                if (!is_user_logged_in() || !$this->check_user_can_edit() || empty($current_object = $this->detect_current_object())) {
                    return;
                }
                return sprintf(
                    '<a href="%s">%s</a>',
                    $current_object['url'],
                    $current_object['edit_label']
                );
        }
        return $item_output;
    }
}
