<?php
/*
Plugin Name: Quotes Random
Plugin URI: http://quotes8.com/wordpress-plugin
Description: Simple wordpress plugin to show random Famous Quotes at sidebar with Widget or Shortcode [quotes-random].
Version: 1.2
Author: Quotes8
Author URI: http://quotes8.com/
License: GPLv2 or later.
*/
include dirname(__FILE__) . '/simple_html_dom.php';

class quotesrandom extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = __('Quotes Random'));
	}
	function form($instance) {
	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = "Quote of The Day";
		}
?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','Quotes Random'); ?>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</label>
</p>
<?php
}
	function update($new_instance, $old_instance) {
	      $instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

	function widget($args, $instance) {
$title = $instance['title']; if ($title == '') $title = 'Quote of The Day';
$title = apply_filters('widget_title', $instance['title']);
echo $args['before_widget'];
   
 if ( $title ) {
      echo $args['before_title'] . $title. $args['after_title'];
   }
		?>
		
	<?php

$rand1 = mt_rand(1,15);
$rand2 = mt_rand(1,15);
$html = file_get_html("http://quotes8.com/quotes-of-the-day/page/".$rand1."/?ref=".$_SERVER['HTTP_HOST']);
$quotes = "<em>".$html->find("div[class='entry-content']p", $rand2)->plaintext."</em>";
$author = "<em>".$html->find("span[class='quote-author']", $rand2-1)->plaintext."</em>";
$url = $html->find("a[rel='tag']", $rand2-1)->href;
echo $quotes.' <a href="'.$url.'" target="_blank" >'.$author.'</a>';

?>
	
		<?php
  echo $args['after_widget'];
	}
}
function register_quotesrandom()
{
    register_widget( 'quotesrandom' );
}
add_action( 'widgets_init', 'register_quotesrandom');

function quotes_random() {

$rand1 = mt_rand(1,15);
$rand2 = mt_rand(1,15);
$html = file_get_html("http://quotes8.com/quotes-of-the-day/page/".$rand1);
$quotes = "<em>".$html->find("div[class='entry-content']p", $rand2)->plaintext."</em>";
$author = "<em>".$html->find("span[class='quote-author']", $rand2-1)->plaintext."</em>";
$url = $html->find("a[rel='tag']", $rand2-1)->href;
$quotes =  '<p>'.$quotes.' <a href="'.$url.'" target="_blank" >'.$author.'</a></p>';
return $quotes;

}
add_shortcode('quotes-random', 'quotes_random'); 

?>