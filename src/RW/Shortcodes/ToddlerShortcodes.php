<?php 

namespace RW\Shortcodes;

/**
 * Shortcode Pack
 */

class ShortcodePack
{
    public function __construct()
    {
        #add_shortcode('featured_agent', array($this,'wp_featured_agent'));
    }

    public function wp_featured_agent($atts) {
        global $wp_query;

        extract(shortcode_atts(array(
            'total' => 3,
        ), $atts));

        echo View::render('agents/featured-agents.twig', array(
            'wp_query' => $wp_query,
        ));
    }
}