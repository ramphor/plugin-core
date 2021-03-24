<?php
namespace Ramphor\Core\UI;

class Menu
{
    protected static $ramphorNavItems;
    protected static $ramphorNavItemsArgs;

    public function registeMetabox()
    {
        if (is_null(static::$ramphorNavItems)) {
            static::$ramphorNavItems = $this->get_nav_items();
        }
        if (empty(static::$ramphorNavItems)) {
            // Don't show ramphor menu items when static::$ramphorNavItems value is empty
            return;
        }

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
            array(
                'quick_edit' => __('Quick Edit', 'ramphor'),
            )
        );
        return $ramphorNavItems;
    }

    protected function create_menu_nav_item($key)
    {
        if (is_null(static::$ramphorNavItemsArgs)) {
            static::$ramphorNavItemsArgs = apply_filters('ramphor_nav_menu_item_args', array());
        }

        $itemsArgs = &static::$ramphorNavItemsArgs;
        if (isset($itemsArgs[$key])) {
            return $itemsArgs[$key];
        }

        return array(
            'type' => $key,
            'url' => "#ramphor-{$key}",
            'title' => ucfirst(preg_replace('/[_|\-]/', ' ', $key)),
        );
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
        $items = static::$ramphorNavItems;
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

    public function setup_nav_menu_item($menu_item)
    {
        if (is_null(static::$ramphorNavItems)) {
            static::$ramphorNavItems = $this->get_nav_items();
        }

        $items = static::$ramphorNavItems;
        if (isset($items[$menu_item->type])) {
            $menu_item->type_label = $items[$menu_item->type];
        }

        return $menu_item;
    }
}
