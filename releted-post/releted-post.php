<?php 

/**
 * Plugin name: Releted Post
 * Description: This is my releted post plugin
 * Author: Muslim khan
 */

 class rp_releted_post {
    public function __construct() {
          add_action('init', array($this, 'initialize'));
    }


    function initialize() {
        add_filter('the_content', array($this, 'change_content'));   //hook     
        add_action('wp_enqueue_scripts', array($this, 'load_style'));  //enqueue scripts     
    }    

    public function load_style() {
        $releted_post_path = plugin_dir_url( __FILE__ );   //file path
        $css_path = $releted_post_path."css/";            // css folder path

        wp_enqueue_style("rp_style", $css_path. "frontend.css", [], '1.0.0',);    //css file path
    }


    public function change_content($content) {

        if( ! is_singular( 'post' ) ){
            return $content;
        }
          
        $currentcat = get_the_category(get_the_ID());   // Returns the category information associated with the current post
        $currentcat = wp_list_pluck($currentcat, 'term_id');  //convert the array of id to string
        $currentcat = implode( ', ', $currentcat );   

        $arrg = array(
            "post_type"      => "post", // Setting 'post' as post type
            'cat'            => $currentcat,
            'orderby'        => 'rand',     // Posts will be decorated randomly
            'posts_per_page' => 5,    // 3 posts will be displayed per page      
        );


        // the query.
        $the_query = new WP_Query( $arrg ); 
        
            if ( $the_query->have_posts()) {

                $content.= '<div class="main_div">';

                while( $the_query->have_posts() ) :    //loop
                    $the_query->the_post();
                    $content.= '<div class="main_post">';
                    $content.= '<h1 class="main_title">'.get_the_title().'</h1>'. '<br>';
                    $content.= '<div class="main_thumbnail">'.get_the_post_thumbnail().'</div>';
                    $content.= '<p class="main_content">'.get_the_content().'</p>';
                    $content.= '</div>';
                    
                endwhile;

                $content.='</div>';


                wp_reset_postdata();  //Resets the global post data to the original main query
        
            } else {
            echo 'no post';


        }
        return $content;

    }          
} 
new  rp_releted_post();   















































 