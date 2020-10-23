<?php
namespace Ramphor\Core\UI;

class Menu {
    protected static $ramphorNavItems = array();

    public function registeMetabox() {
        static::$ramphorNavItems = $this->get_nav_items();

        add_meta_box(
            'ramphor_nav_links',
            __('Ramphor Premium', 'ramphor'),
            array( $this, 'nav_menu_links' ),
            'nav-menus',
            'side'
        );
    }

    public function get_nav_items()
    {
        $ramphorNavItems = apply_filters(
            'ramphor_nav_menu_items',
            array()
        );
        return $ramphorNavItems;
    }

    protected function create_menu_nav_item($key)
    {
        $title = '';
        if ($key === 'ramphor-logo') {
            $title = get_bloginfo('name');
        } elseif ($key === 'ramphor-search-form') {
            $title = static::$ramphorNavItems[$key];
        }

        $item = wp_parse_args(array(), array(
            'type' => $key,
            'title' => $title,
            'url' => "#ramphor-{$key}",
            'classes' => null
        ));

        return apply_filters("ramphor_nav_{$key}_menu_item", $item, $key);
    }

    protected function render_menu_item_hidden_input($index, $item)
    {
        foreach ($item as $type => $value) : ?>
            <?php if (is_null($value)) : ?>
                <input type="hidden"
                    class="menu-item-<?php echo $type; ?>"
                    name="menu-item[<?php echo esc_attr($index); ?>][menu-item-<?php echo $type; ?>]"
                />
            <?php else : ?>
                <input
                    type="hidden"
                    class="menu-item-type"
                    name="menu-item[<?php echo esc_attr($index); ?>][menu-item-<?php echo $type; ?>]<?php  ?>"
                    value="<?php echo $value; ?>"
                />
            <?php endif; ?>
            <?php
        endforeach;
    }

    public function nav_menu_links()
    {
        $items = $this->get_nav_items();
        ?>
        <div id="posttype-ramphor-nav-items" class="posttypediv">
            <div id="tabs-panel-ramphor-nav-items" class="tabs-panel tabs-panel-active">
                <ul id="ramphor-nav-items-checklist" class="categorychecklist form-no-clear">
                    <?php
                    $i = -1;
                    foreach ($items as $key => $value) :
                        $item = $this->create_menu_nav_item($key);
                        ?>
                        <li>
                            <label class="menu-item-title">
                                <input
                                    type="checkbox"
                                    class="menu-item-checkbox"
                                    name="menu-item[<?php echo esc_attr($i); ?>][menu-item-object-id]"
                                    value="<?php echo esc_attr($i); ?>"
                                />
                                <?php echo esc_html($value); ?>
                            </label>
                            <?php $this->render_menu_item_hidden_input($i, $item); ?>
                        </li>
                        <?php
                        $i--;
                    endforeach;
                    ?>
                </ul>
            </div>
            <p class="button-controls">
                <span class="add-to-menu">
                    <button
                        type="submit"
                        class="button-secondary submit-add-to-menu right"
                        value="<?php esc_attr_e('Add to menu', 'ramphor'); ?>"
                        name="add-post-type-menu-item"
                        id="submit-posttype-ramphor-nav-items"
                    >
                        <?php esc_html_e('Add to menu', 'ramphor'); ?>
                    </button>
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
        <?php
    }
}
