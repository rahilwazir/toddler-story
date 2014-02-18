<?php

namespace RW\Shortcodes;

/**
 * Shortcode Pack
 */
class ShortcodePack
{

    public function __construct()
    {
        add_shortcode('brown_section', array($this, 'brown_section'));
        add_shortcode('mid_section', array($this, 'mid_section'));
        add_shortcode('mid_right_section', array($this, 'mid_right_section'));
        add_shortcode('clear_both', array($this, 'clear_both'));
        add_shortcode('faq_q_a', array($this, 'faq_q_a'));
        add_shortcode('home_video_section', array($this, 'home_video_section'));
    }

    public function brown_section($atts, $content = null)
    {
        global $post;

        extract(shortcode_atts(array(
            'title' => $post->post_title,
        ), $atts));

        $output = '';

        $output = '<section class="first_mid clearfix"><div class="wrapper">
                <h1 class="coni">' . $title . '</h1>
                <div class="coni_para">
                    <p>' . do_shortcode($content) . '</p>
                </div><br clear="all"/>
                </div></section>';
        
        return $output;
    }
    
    public function mid_section($atts, $content = null)
    {
        extract(shortcode_atts(array(
            
        ), $atts));

        $output = '';

        $output = '<section class="sec_mid clearfix"><div class="wrapper">';
        
        $right_content = has_shortcode($content, 'mid_right_section') ? '' : '</div><div class="clear"></div>';
        
        if ( has_shortcode($content, 'mid_right_section') ) {
            $output .= '<div class="mid_left" >';
        }

        $output .= do_shortcode($content) . $right_content.'</section>';
        
        return $output;
    }
    
    public function mid_right_section($atts, $content = null)
    {
        extract(shortcode_atts(array(
            
        ), $atts));

        $output = '';

        $output = '</div><div class="mid_right">'.do_shortcode($content) . '</div>';
        
        return $output;
    }
    
    public function clear_both()
    {
        return '<div class="clearfix"></div>';
    }
    
    public function faq_q_a($atts, $content=null)
    {
        extract(shortcode_atts(array(
            'title' => '',
            'description' => ''
        ), $atts));

        $output = '';
        
        $output = '<h1>' . $title . '</h1>
            <div class="para_div"><p>' . $description . '</p></div>
         <div class="clear"></div>';
        
        return $output;
    }
    
    public function home_video_section($atts, $content=null)
    {
        extract(shortcode_atts(array(
            'title' => '',
            'orange_title' => '',
            'description' => ''
        ), $atts));

        $output = '';
        
        $output = '<div class="video_sec">
            <div class="left">
                <h1>' . $title . '<span class="orange"> ' . $orange_title . '</span></h1>
                <p>' . $description . '</p>';
        $output .= (!is_user_logged_in()) ? '<a href="#" data-load-popup="value_R" class="basic join get_started">Get Started</a>' : '';

        $output .= '</div>
            <div class="right"><img src="'. get_bloginfo('template_url') . '/images/video_img.png" /></div>
            <br clear="all" />
        </div>
        <br clear="all" />';
        
        return $output;
    }
}
