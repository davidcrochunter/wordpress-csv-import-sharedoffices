<?php
   /*
   Plugin Name: Import Shared Offices
   Author: Mr. Awesome
   License: GPL2
   */
?>

<?php
/*
function import_shared_offices_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
      <h1><?php esc_html( get_admin_page_title() ); ?></h1>
      <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "wporg_options"
        settings_fields( 'wporg_options' );
        // output setting sections and their fields
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections( 'wporg' );
        // output save settings button
        submit_button( 'Save Settings' );
        ?>
      </form>
    </div>
    <?php
}
*/

function wporg_options_page() {
    add_menu_page(
        'Import Shared Offices',
        'Import Shared Offices Options',
        'manage_options',

        // 'import-shared-offices-options',
        // 'import_shared_offices_options_page_html',
        // plugin_dir_url(__FILE__) . 'images/icon_wporg.png',

        plugin_dir_path(__FILE__) . 'admin/view.php',
        null,
        plugin_dir_url(__FILE__) . 'images/icon_wporg.png',

        20
    );
}
add_action( 'admin_menu', 'wporg_options_page' );