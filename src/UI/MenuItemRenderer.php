<?php
namespace Ramphor\Core\UI;

use WP_Post;

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
