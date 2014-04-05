<?php
/**
 * The WordPress Plugin Boilerplate.
*
* A foundation off of which to build well-documented WordPress plugins that
* also follow WordPress Coding Standards and PHP best practices.
*
* @package   Plugin_Name
* @author    Your Name <email@example.com>
* @license   GPL-2.0+
* @link      http://example.com
* @copyright 2014 Your Name or Company Name
*
* @wordpress-plugin
* Plugin Name:       Rocket Wiki 
* Plugin URI:        @TODO
* Description:       @TODO
* Version:           1.0.0
* Author:            @TODO
* Author URI:        @TODO
* Text Domain:       plugin-name-locale
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Domain Path:       /languages
* GitHub Plugin URI: https://github.com/<owner>/<repo>
* WordPress-Plugin-Boilerplate: v2.6.1
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

function link_to_tag($content) {
    // Match tags with data-rocketwiki. Convert to [[]]
    $pre_pattern = "/\<a\s+.*?\<\/a\>/";
    $content = preg_replace_callback($pre_pattern, function($matches){
        $doc = new DOMDocument();
        $doc->loadHTML($matches[0]);
        return "[[".$doc->textContent."]]";
    }, $content);
    return $content;

}

function tag_to_link($content) {
    // Match anything within [[]]
    $pattern = "/\[\[(?:-?[A-z]+)+\]\]/";
    $content = preg_replace_callback($pattern, function($matches){
        $tag = preg_replace('/\[\[|\]\]/', '', $matches[0]);
        $link = "<a data-rocketwiki='".$tag."' href='http://localhost/".$tag."'>".$tag."</a>";
        create_post_from_tag($tag, 'fo');
        return $link;

    }, $content);
    return $content;
}

function create_post_from_tag($tag, $title) {
    // TODO: Check if post exists...

    $args = array(
        'post_content' => 'Blank Wiki Page',
        'post_name' => 'tag',
        'post_title' => $title 
    );    
}
function underline_links($content) {
    /* Var Dumper ============================= */
    echo '<bold>Dumping var $content</bold><br/>';
    echo '<pre>'; var_dump( $content ); echo '</pre>';
    $pre_pattern = "/\<a\s+.*?\<\/a\>/";
    $content = preg_replace_callback($pre_pattern, function($matches){
        $doc = new DOMDocument();
        $doc->loadHTML($matches[0]);
        /* Var Dumper ============================= */
        echo '<bold>Dumping var $doc</bold><br/>';
        echo '<pre>'; var_dump( $doc ); echo '</pre>';
        $doc->documentElement->setAttribute('style', 'bar');
        return $doc->saveHTML();
    }, $content);
    return $content;
}

add_filter('content_save_pre', 'tag_to_link', 10, 1);
add_filter('content_edit_pre', 'link_to_tag', 10, 1);
add_filter('the_content', 'underline_links', 10, 1);

function rocket_wiki_add_meta_box() {
    add_meta_box(
        'rocketwiki_start',
        __( 'Rocket Wiki Title', 'rocket-wiki-textdomain'),
        'rocketwiki_inner_box',
        'page' 
    );
}

add_action('add_meta_boxes', 'rocket_wiki_add_meta_box');

function rocketwiki_inner_box() {
    wp_nonce_field('rocketwiki_inner_box', 'rocketwiki_inner_box_nonce');
    
    $value = get_post_meta($post->ID, '_my_meta_value_key', true );
    
    ?>
    <label for="rocketwiki-field">Field</label>
    <input type="text" id="foo" name="foo" value="woot" size="25" />
    <?php
}
?>
