<?php
/**
 * Plugin Name: Pronamic Multisite Languages
 * Plugin URI: http://pronamic.nl
 * Author: Pronamic
 * Author URI: http://pronamic.nl
 */

add_action( 'network_admin_menu', 'pronamic_multisite_languages_admin_menu' );
function pronamic_multisite_languages_admin_menu() {
    add_menu_page(
        __( 'Multisite Languages', 'pronamic-multisite-languages' ),
        __( 'Multisite Languages', 'pronamic-multisite-languages' ),
        'manage_network_options',
        'pronamic-multisite-languages',
        'view_pronamic_multisite_languages_admin_page',
        'dashicons-admin-site'
    );
}

function view_pronamic_multisite_languages_admin_page() {
    // Get all sites
    $all_sites = wp_get_sites();

    // Get all the active site blog ids
    $active_sites = pronamic_multisite_languages();
    $active_sites_ids = pronamic_multisite_languages_blog_ids();

    foreach ( $all_sites as $key => $all_site ) {
        if ( in_array( $all_site['blog_id'], $active_sites_ids ) )
            unset( $all_sites[$key] );
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo get_admin_page_title(); ?></h1>
        <p><?php _e( 'Add a string representation you can save with each site and use in your themes to load correct images or build language dropdowns', 'pronamic-multisite-languages' ); ?></p>
        <h3><?php _e( 'Active Language Sites', 'pronamic-multisite-languages' ); ?></h3>
        <?php if ( ! empty( $active_sites ) ) : ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th class="row-title"><?php _e( 'Blog ID', 'pronamic-multisite-languages' ); ?></th>
                        <th class="row-title"><?php _e( 'Domain', 'pronamic-multisite-languages' ); ?></th>
                        <th class="row-title"><?php _e( 'Language?', 'pronamic-multisite-languages' ); ?></th>
                        <th class="row-title"><?php _e( 'Order', 'pronamic-multisite-languages' ); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $active_sites as $site ) : ?>
                    <tr>
                        <form action="" method="POST">
                            <input type="hidden" name="pronamic_multisite_languages_action" />
                            <input type="hidden" name="blog_id" value="<?php echo $site->blog_id; ?>" />
                            <td><?php echo $site->blog_id; ?></td>
                            <td><?php echo $site->domain; ?></td>
                            <td>
                                <input class="small-text" type="text" name="language" value="<?php echo $site->language; ?>" />
                            </td>
                            <td>
                                <input class="small-text" type="text" name="order" value="<?php echo $site->order; ?>" />
                            </td>
                            <td>
                                <input class="button button-primary" type="submit" value="<?php _e( 'Update', 'pronamic-sml' ); ?>" />
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php _e( 'No sites have a language set. You can set one below', 'pronamic-multisite-languages' ); ?></p>
        <?php endif; ?>
        <h3><?php _e( 'All Sites', 'pronamic-multisite-languages' ); ?></h3>
        <?php if ( ! empty( $all_sites ) ) : ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th class="row-title"><?php _e( 'Blog ID', 'pronamic-multisite-languages' ); ?></th>
                        <th class="row-title"><?php _e( 'Domain', 'pronamic-multisite-languages' ); ?></th>
                        <th class="row-title"><?php _e( 'Language?', 'pronamic-multisite-languages' ); ?></th>
                        <th class="row-title"><?php _e( 'Order', 'pronamic-multisite-languages' ); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $all_sites as $site ) : ?>
                            <tr>
                                <form action="" method="POST">
                                    <input type="hidden" name="pronamic_multisite_languages_action" />
                                    <input type="hidden" name="blog_id" value="<?php echo $site['blog_id']; ?>" />
                                    <td><?php echo $site['blog_id']; ?></td>
                                    <td><?php echo $site['domain']; ?></td>
                                    <td>
                                        <input class="small-text" type="text" name="language" value="" />
                                    </td>
                                    <td>
                                        <input class="small-text" type="text" name="order" value="" />
                                    </td>
                                    <td>
                                        <input class="button button-primary" type="submit" value="<?php _e( 'Update', 'pronamic-sml' ); ?>" />
                                    </td>
                                </form>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php _e( 'All sites have a language', 'pronamic-multisite-languages' ); ?></p>
        <?php endif; ?>
        <h3><?php _e( 'Usage', 'pronamic-multisite-languages' ); ?></h3>
        <p><?php _e( 'Below is an example usage to show links to your language sites.', 'pronamic-multisite-languages' ); ?></p>
        <pre>
            <?php echo esc_html('
<?php if ( function_exists( "pronamic_multisite_languages" ) ) : ?>
    <?php $language_blogs = pronamic_multisite_languages(); ?>
    <?php foreach ( $language_blogs as $language_blog ) : ?>
        <a href="<?php echo $language_blog->siteurl; ?>">
            <img src="<?php echo pronamic_multisite_languages_flag( $language_blog->language ); ?>" alt="<?php echo $language_blog->blogname; ?>"/>
        </a>
    <?php endforeach; ?>
<?php endif; ?>
            '); ?>
        </pre>
        <div>
            <?php if ( function_exists( 'pronamic_multisite_languages' ) ) : ?>
                <?php $language_blogs = pronamic_multisite_languages(); ?>
                <?php foreach ( $language_blogs as $language_blog ) : ?>
                    <a href="<?php echo $language_blog->siteurl; ?>">
                        <img src="<?php echo pronamic_multisite_languages_flag( $language_blog->language ); ?>" alt="<?php echo $language_blog->blogname; ?>"/>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Saves the passed in information for each blog.
 */
add_action( 'admin_init', 'save_pronamic_multisite_languages_admin_page' );
function save_pronamic_multisite_languages_admin_page() {
    if ( ! filter_has_var( INPUT_POST, 'pronamic_multisite_languages_action' ) )
        return;

    if ( ! current_user_can( 'manage_network_options' ) )
        return;

    // get the posted values
    $blog_id  = filter_input( INPUT_POST, 'blog_id', FILTER_SANITIZE_NUMBER_INT );
    $language = filter_input( INPUT_POST, 'language', FILTER_SANITIZE_STRING );
    $order    = filter_input( INPUT_POST, 'order', FILTER_SANITIZE_NUMBER_INT );

    if ( empty( $blog_id ) )
        return;

    // Multisite extra details
    $multisite_extra = maybe_unserialize( get_site_option( '_pronamic_multisite_languages', array() ) );
    
    // Get all the specified blog ids
    $multisite_languages_blog_ids = pronamic_multisite_languages_blog_ids();   

    if ( ! empty( $language ) ) {
        // Get the blog id details
        $blog_details = get_blog_details( $blog_id );

        // ensure is a valid blog id
        if ( ! $blog_details )
            return;

        // clear the blog cache
        clean_blog_cache( $blog_details );

        // ensure its not already part of the specified blog ids
        if ( ! in_array( $blog_id, $multisite_languages_blog_ids ) )
            $multisite_languages_blog_ids[] = $blog_id;

        // Add the multisite extra
        $multisite_extra[$blog_id] = array(
            'language' => $language,
            'order'    => $order
        );

    } else {
        if ( array_key_exists( $blog_id, $multisite_extra ) ) {
            unset( $multisite_extra[$blog_id] );
        }

        if ( in_array( $blog_id, $multisite_languages_blog_ids ) ) {
            $key = array_search( $blog_id, $multisite_languages_blog_ids );
            unset( $multisite_languages_blog_ids[$key] );
        }
    }

    // update the extra details
    update_site_option( '_pronamic_multisite_languages', serialize( $multisite_extra ) );
    update_site_option( '_pronamic_multisite_languages_blog_ids', serialize( $multisite_languages_blog_ids ) );
}

/**
 * Filters the blog_details save and call to add the additional properties of order
 * and the language string.  This will mean when you call get_blog_details() you will
 * also get the saved additional information.
 */
add_filter( 'blog_details', 'pronamic_multisite_languages_blog_details' );
function pronamic_multisite_languages_blog_details( $details ) {
    $blog_id = $details->blog_id;

    // get the existing languages
    $pronamic_multisite_languages = maybe_unserialize( get_site_option( '_pronamic_multisite_languages' ) );
    
    if ( array_key_exists( $blog_id, $pronamic_multisite_languages ) ) {
        $details->order = $pronamic_multisite_languages[$blog_id]['order'];
        $details->language = $pronamic_multisite_languages[$blog_id]['language'];
    }

    return $details;
}

/**
 * Custom usort function for ordering the blog details by their new property.
 * 
 * @param  [stdClass] $a
 * @param  [stdClass] $b
 * @return [int]
 */
function _pronamic_multisite_languages_usort( $a, $b ) {
    return $a->order - $b->order;
}

/**
 * Main Function to be used. Will get all the saved chosen blog ids. Get all the details for those
 * blog ids and sort them by there order property.
 * 
 * @return [array]
 */
function pronamic_multisite_languages() {
    // get the ids for the language blog sites
    $multisite_languages_blog_ids = maybe_unserialize( get_site_option( '_pronamic_multisite_languages_blog_ids', array() ) );

    // get all the blog details
    $site_details = array();
    foreach ( $multisite_languages_blog_ids as $blog_id ) {
        $site_details[] = get_blog_details( $blog_id );
    }

    usort( $site_details, '_pronamic_multisite_languages_usort' );
    return $site_details;
}

/**
 * Get all the saved blog ids that have been specified to have a language
 *
 * @return [string]
 */
function pronamic_multisite_languages_blog_ids() {
    return maybe_unserialize( get_site_option( '_pronamic_multisite_languages_blog_ids' ), array() );
}

/**
 * Return the URL to a flag if the ISO Flag code matches one of the files.
 * @param  [string] $iso_flag_code
 * @return [string]
 */
function pronamic_multisite_languages_flag( $iso_flag_code ) {
    return plugins_url( 'flags/' . $iso_flag_code . '.png', __FILE__ );
}