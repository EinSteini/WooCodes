<?php
add_action( 'admin_menu', 'wcs_add_admin_menu' );
add_action( 'admin_init', 'wcs_settings_init' );
add_action( 'admin_post_wcsadmin_newcat', 'wcs_add_category' );


function wcs_add_category(){
    $categories = get_option('woocodes_categories');
    array_push($categories, $_POST['wcsadmin_newcatname']);
    update_option('woocodes_categories', $categories);
    wp_insert_term( 'WCS_'.$_POST['wcsadmin_newcatname'], 'product_cat' );
    wp_redirect( 'admin.php?page=wcs');
}
function wcs_category_settings(string $cat_name){
    $cat_namelow = mb_strtolower($cat_name);
    add_settings_section(
        'wcs_'.$cat_namelow,
        __($cat_name, 'WooCodes'),
        function(){},
        'woocodes'
    );
    add_settings_field(
        'password',
        __('Password', 'WooCodes'),
        function() use(&$cat_namelow){
            $options = get_option('woocodes');
            echo '<input type="text" name="woocodes[password_'.$cat_namelow.']" value="'.$options['password_'.$cat_namelow].'">';
        },
        'woocodes',
        'wcs_'.$cat_namelow
    );
    add_settings_field(
        'link',
        __('Link', 'WooCodes'),
        function() use(&$cat_namelow){
            $options = get_option('woocodes');
            ?>
            <input type='text' name='woocodes[link_<?php echo $cat_namelow;?>]' value='<?php echo $options['link_'.$cat_namelow];?>'>
            <?php
        },
        'woocodes',
        'wcs_'.$cat_namelow
    );
    add_settings_field(
        'id',
        __('Meeting ID', 'WooCodes'),
        function() use(&$cat_namelow){
            $options = get_option('woocodes');
            ?>
            <input type='text' name='woocodes[id_<?php echo $cat_namelow;?>]' value='<?php echo $options['id_'.$cat_namelow];?>'>
            <?php
        },
        'woocodes',
        'wcs_'.$cat_namelow
    );
    add_settings_field(
        'content',
        __('Content', 'WooCodes'),
        function() use(&$cat_namelow){
            $options = get_option('woocodes');
            ?>
            <textarea name='woocodes[content_<?php echo $cat_namelow;?>]' rows='4' cols='50'>
                <?php echo $options['content_'.$cat_namelow]?>
            </textarea><br>
            <label for='woocodes[content_<?php echo $cat_namelow;?>]'>
                When the password should be inserted, please write 'wcs_pw'.<br>
                When the link should be inserted, please write 'wcs_link'.<br>
                When the meeting id should be inserted, please write 'wcs_id'.<br>
                (everything without quotes)
            </label>
            <?php
        },
        'woocodes',
        'wcs_'.$cat_namelow
    );
}

function wcs_add_admin_menu(  ) { 
	add_menu_page( 'WooCodes', 'WooCodes', 'manage_options', 'wcs', 'wcs_options_page', 'dashicons-hammer' );
}

function wcs_settings_init(){
    $options = get_option('woocodes');
    $categories = get_option( 'woocodes_categories' );
    register_setting('woocodes','woocodes');
    foreach($categories as $cat_name){

        wcs_category_settings($cat_name);
    }
    
}
function wcs_options_page(  ) { 
    ?>
    <form action='admin-post.php' method='POST' style='margin-top:10px;' >
        <input type='hidden' name='action' value='wcsadmin_newcat'>
        <input type='text' name='wcsadmin_newcatname' placeholder='Category Name'>
        <input type='submit' name='wcsadmin_newcatsub' value='Create New Category'>
    </form>
    <?php
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