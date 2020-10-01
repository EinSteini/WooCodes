<?php
add_action( 'admin_menu', 'wcs_add_admin_menu' );
add_action( 'admin_init', 'wcs_settings_init' );

function wcs_add_admin_menu(  ) { 

	add_menu_page( 'WooCodes', 'WooCodes', 'manage_options', 'wcs', 'wcs_options_page', 'dashicons-hammer' );

}

function wcs_settings_init(){
    register_setting('woocodes','woocodes');

    add_settings_section(
        'wcs_general',
        __('General', 'WooCodes'),
        'wcs_general_callback',
        'woocodes'
    );
    add_settings_field(
        'password',
        __('Password', 'WooCodes'),
        'password_render',
        'woocodes',
        'wcs_general'
    );
}
function password_render(){
    $options = get_option('woocodes');
    ?>
    <input type='text' name='woocodes[password]' value='<?php echo $options['password'];?>'>
    <?php
}
function wcs_options_page(  ) { 

    ?>
    <form action='options.php' method='post'>

        <h1>WooCodes Options</h1>
        <?php
        settings_fields( 'woocodes' );
        do_settings_sections( 'woocodes' );
        submit_button();
        ?>

    </form>
    <?php

}
?>