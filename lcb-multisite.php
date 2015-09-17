<?php

/**
 * Plugin Name: LCB Multisite
 * Description: Wordpress multisite addon for generating hereditary multisite structure
 * Version: 0.1
 * Author: Silpion Tomasz Gregorczyk 
 * Author URI: http://leftcurlybracket.com/
 * Requires at least: 3.8
 */
function lcb_multisite_parent_form()
{
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $is_main_site = is_main_site($id);
    if (!$is_main_site):
        $parent_id = 0;
        global $wpdb;
        $blog_prefix = $wpdb->get_blog_prefix($id);
        $sql = "SELECT * FROM {$blog_prefix}options
                    WHERE option_name LIKE 'multisite_parent'";
        $query = $wpdb->prepare($sql, $wpdb->esc_like('_') . '%', '%' . $wpdb->esc_like('user_roles')
        );
        $options = $wpdb->get_results($query);
        foreach ($options as $option) {
            if ($option->option_name == "multisite_parent") {
                isset($option->option_value) ? $parent_id = $option->option_value : false;
            }
        }
        ?>
        <tr class="form-field">
            <th scope="row"><label for="multisite_parent"><?php _e('Multisite parent'); ?></label></th>
            <td>
                <select class="<?php echo $class; ?>" name="option[multisite_parent]" type="text" id="multisite_parent"<?php disabled($disabled) ?>>
                    <?php foreach (wp_get_sites() as $site): if($site['blog_id']!=$id): ?>
                        <option value="<?php echo $site['blog_id']; ?>" <?php if ($site['blog_id'] == $parent_id) { echo "selected"; } ?>>
                            <?php echo $site["domain"] . $site['path']; ?>
                        </option>
                    <?php endif; endforeach; ?>
                </select>
            </td>
        </tr>
        <?php
    endif;
}

add_action('wpmueditblogaction', 'lcb_multisite_parent_form', 10, 2);