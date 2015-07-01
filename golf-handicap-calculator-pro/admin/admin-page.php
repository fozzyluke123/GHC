<?php // Creates the ghc admin settings page.

include __DIR__ . "/user_profile_edit.php";
include __DIR__ . "/template_page.php";
include __DIR__ . "/competitions_page.php";
add_action('admin_menu', 'ghc_create_menu');
add_action('admin_init', 'register_ghc_settings' );

function ghc_create_menu() {

	//create new top-level admin menu
	add_menu_page('GHC Settings', 'GHC Settings', 'activate_plugins', __FILE__, 'ghc_settings_page', 'dashicons-flag' );
	add_submenu_page( __FILE__, 'Add/Edit Template','Add/Edit Template', 'activate_plugins', 'ghc_templates_page', 'ghc_template_page');
	add_submenu_page( __FILE__, 'Competitions' , 'Competitions', 'activate_plugins', 'ghc_competitions_page', 'ghc_competitions_page');
}
function register_ghc_settings() {
	//register our settings
	register_setting( 'ghc-settings-group', 'ghc_option_css' );
	register_setting('ghc-settings-group', 'ghc_license_key' );
}
function ghc_settings_page() { //admin settings page
	$license 	= get_option( 'ghc_license_key' );
	$status 	= get_option( 'ghc_license_status' );?>
    <div class="ghc_admin_page">
        <h2>Golf Handicap Calculator Settings</h2> <!-- admin settings form -->
        <form method="post" action="options.php">
        <div class="wrap">
            <h2><?php _e('Plugin License Options'); ?></h2> <!-- plugin license input -->
            <?php if (get_option( 'ghc_license_status' ) !== "valid"){?>
            <h3>Enter your license key to use the GHC plugin</h3>
            <?php }
                settings_fields('ghc_license'); ?>
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php _e('License Key'); ?>
                            </th>
                            <td>
                                <input id="ghc_license_key" name="ghc_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" <?php if( $status !== false && $status == 'valid' ) { ?> readonly <?php } ?>/>
                                <label class="description" for="ghc_license_key"><?php _e('Enter your license key'); ?></label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php _e('Activate License'); ?>
                            </th>
                            <td>
                                <?php if( $status !== false && $status == 'valid' ) { ?>
                                    <span style="color:green; vertical-align: -webkit-baseline-middle; vertical-align: -moz-middle-with-baseline;"><?php _e('active'); ?></span>
                                    <?php wp_nonce_field( 'ghc_nonce', 'ghc_nonce' ); ?>
                                    <input type="submit" class="button-secondary" name="ghc_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
                                <?php } else { ?>
                                    <span style="color:red; vertical-align: -webkit-baseline-middle; vertical-align: -moz-middle-with-baseline;"><?php _e('License Invalid'); ?></span>
                                    <?php wp_nonce_field( 'ghc_nonce', 'ghc_nonce' ); ?>
                                    <input type="submit" class="button-secondary" name="ghc_license_activate" value="<?php _e('Activate License'); ?>"/>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php if( $status !== false && $status == 'valid' ) {
                ?><p> Thanks for entering your code </p><?php
                }
        settings_fields( 'ghc-settings-group' );
        do_settings_sections( 'ghc-settings-group' );
        if (get_option( 'ghc_license_status' ) == "valid"){// if plugin is licensed then display the rest of the settings
            ?>
            <h3>GHC Shortcodes</h3>
            <p>To add these items to your website, simple copy and paste these codes to the post/page you want to display them on.</p>
            <li>[ghc_form] = The main input form</li>
            <li>[ghc_user_details] = Echo current users handicap</li>
            <li>[ghc_display_users] = Display all users scores in a table</li>
            <table>
                <tr>
                    <td><h3 for = "ghc_option_css"> Custom CSS</h3>
                    <textarea style="width:570px; height:270px;" name="ghc_option_css"/><?php echo get_option('ghc_option_css'); ?></textarea></td><td>Add any custom css for this plugin here the classes are: <br />[ghc_display_users] - table = .ghc_stats_table <br />[ghc_form] - table = .ghcform</td>
                </tr>
                <tr>
                    <td><?php submit_button(); ?></td>
                </tr>
            </table>
            </form>
    	</div>
	<?php
	}
};
