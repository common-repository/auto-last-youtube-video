<?php
/*
Plugin Name: Auto Last Youtube Video
Plugin URI: http://wordpress.org/plugins/auto-last-youtube-video/
Description: This plugin providesboth Widget and Shortcode to show latest videos from any public Youtube channel. Using [auto_last_youtube_video user='channel_name' width='450' height='320'][/auto_last_youtube_video] in a page or post will show last video uploaded to that channel and will change if another video is uploaded. The widget let you show as many videos as you want from any Youtube channel.
Version: 1.0.7
Author: davidmerinas
Author URI: https://www.davidmerinas.com
*/
define('AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID', "widget_AUTO_LAST_YOUTUBE_VIDEO");
if ( ! defined( 'AUTO_LAST_YOUTUBE_VIDEO_PATH' ) ) {
	define( 'AUTO_LAST_YOUTUBE_VIDEO_PATH', plugin_dir_path( __FILE__ ));
}
$_SESSION['AUTO_LAST_YOUTUBE_VIDEO_PATH']=plugin_dir_path( __FILE__ );
 
function AUTO_LAST_YOUTUBE_VIDEO_showvideo($canal='davidmerinas',$number=1,$title="",$visittext="Visit Youtube Channel",$introtext="",$type="user"){
	global $wp_embed;
	$videos=youtube($canal,$number,$type);
	echo('<h2 class="widget-title">'.($title!=""?$title:__("Latest from Youtube","autolastyoutubevideo")).'</h2>');
	if($introtext!="")
	{
		echo("<p>".$introtext."</p>");
	}
	foreach($videos as $video)
	{
		echo($wp_embed->run_shortcode('[embed]https://www.youtube.com/watch?v='.$video['idyoutube'].'[/embed]'));
	}

	echo('<a id="autolasvideoseeall" href="https://www.youtube.com/user/'.$canal.'" title="'.$visittext.'">'.$visittext.'</a>');
}

function youtube($canal,$lim=1,$type="user")
{
	require_once 'inc/Zend/Feed.php';
	if($type=="user")
	{
		$url="https://www.youtube.com/feeds/videos.xml?user=".$canal;
	}
	else
	{
		$url="https://www.youtube.com/feeds/videos.xml?channel_id=".$canal;
	}
	$rss=new Zend_Feed();
	$channel = $rss->import($url);
	$i=0;
	$respuesta=array();
	foreach ($channel as $item)
	{
		if ($i<$lim)
		{
			$urlvideo=$item->link("alternate");
			$urlvideo=str_replace("https://www.youtube.com/watch?v=","",$urlvideo);
			$idyoutube=$urlvideo;
			$respuesta[]=array('idyoutube'=>$idyoutube,'imagen'=>'https://i.ytimg.com/vi/'.$idyoutube.'/hqdefault.jpg');
		}
		$i++;
	}

	return $respuesta;
}

function auto_last_youtube_video_func($atts, $content = null) {
	extract(shortcode_atts(array(
	'user'=>'',
	'channel'=>'',
	'width'=>'540',
	'height'=>'405',
	'number'=>1
	), $atts));
	global $wp_embed;
	if($user=="")
	{
		$videos=youtube($channel,$number,'channel');
	}
	else
	{
		$videos=youtube($user,$number,'user');
	}
	
	$contenido="";
	if(count($videos)>0)
	{
		foreach($videos as $video)
		{
			$contenido.='[embed width="'.$width.'" height="'.$height.'"]https://www.youtube.com/watch?v='.$video['idyoutube'].'[/embed]';
			if($number>1)
			{
				$contenido.="<p>&nbsp;</p>";
			}
		}
	}
	return $wp_embed->run_shortcode($contenido);
}

function widget_AUTO_LAST_YOUTUBE_VIDEO_control() {
	$options = get_option(AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID);
	if (!is_array($options)) {
		$options = array();
	}
	$widget_data = $_POST[AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID];
	if ($widget_data['submit']) {
		$options['AUTO_LAST_YOUTUBE_VIDEO_title'] = $widget_data['AUTO_LAST_YOUTUBE_VIDEO_title'];
		$options['AUTO_LAST_YOUTUBE_VIDEO_introtext'] = $widget_data['AUTO_LAST_YOUTUBE_VIDEO_introtext'];
		$options['AUTO_LAST_YOUTUBE_VIDEO_channelurl'] = $widget_data['AUTO_LAST_YOUTUBE_VIDEO_channelurl'];
		$options['AUTO_LAST_YOUTUBE_VIDEO_number'] = $widget_data['AUTO_LAST_YOUTUBE_VIDEO_number'];
		$options['AUTO_LAST_YOUTUBE_VIDEO_type'] = $widget_data['AUTO_LAST_YOUTUBE_VIDEO_type'];
		$options['AUTO_LAST_YOUTUBE_VIDEO_visittext'] = $widget_data['AUTO_LAST_YOUTUBE_VIDEO_visittext'];
		update_option(AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID, $options);
	}
	// Datos para el formulario
	$AUTO_LAST_YOUTUBE_VIDEO_title = $options['AUTO_LAST_YOUTUBE_VIDEO_title'];
	$AUTO_LAST_YOUTUBE_VIDEO_introtext = $options['AUTO_LAST_YOUTUBE_VIDEO_introtext'];
	$AUTO_LAST_YOUTUBE_VIDEO_channelurl = $options['AUTO_LAST_YOUTUBE_VIDEO_channelurl'];
	$AUTO_LAST_YOUTUBE_VIDEO_number = $options['AUTO_LAST_YOUTUBE_VIDEO_number'];
	$AUTO_LAST_YOUTUBE_VIDEO_type = $options['AUTO_LAST_YOUTUBE_VIDEO_type'];
	$AUTO_LAST_YOUTUBE_VIDEO_visittext = $options['AUTO_LAST_YOUTUBE_VIDEO_visittext'];
	
	// Codigo HTML del formulario	
	?>
	<p>
	  <label for="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID;?>-title">
	    <?php _e('Title','autolastyoutubevideo');?>
	  </label>
	  <input class="widefat"
	    type="text"
	    name="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>[AUTO_LAST_YOUTUBE_VIDEO_title]"
	    id="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>-title"
	    value="<?php echo $AUTO_LAST_YOUTUBE_VIDEO_title; ?>"/>
	</p>
	<p>
	  <label for="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID;?>-introtext">
	    <?php _e('Intro Text','autolastyoutubevideo');?>
	  </label>
	  <input class="widefat"
	    type="text"
	    name="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>[AUTO_LAST_YOUTUBE_VIDEO_introtext]"
	    id="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>-introtext"
	    value="<?php echo $AUTO_LAST_YOUTUBE_VIDEO_introtext; ?>"/>
	</p>
	<p>
	  <label for="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID;?>-channelurl">
	    <?php _e('User / Channel Name','autolastyoutubevideo');?>
	  </label>
	  <input class="widefat"
	    type="text"
	    name="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>[AUTO_LAST_YOUTUBE_VIDEO_channelurl]"
	    id="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>-channelurl"
	    value="<?php echo $AUTO_LAST_YOUTUBE_VIDEO_channelurl; ?>"/>
	</p>
	<p>
	  <label for="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID;?>-type">
	    <?php _e('User or Channel?','autolastyoutubevideo');?>
	  </label>
	  <select class="widefat"
	    name="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>[AUTO_LAST_YOUTUBE_VIDEO_type]"
	    id="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>-type">
	    <option value="channel" <?php echo($AUTO_LAST_YOUTUBE_VIDEO_type=="channel"?"selected":"");?>>Channel ID</option>
	    <option value="user" <?php echo($AUTO_LAST_YOUTUBE_VIDEO_type=="user"?"selected":"");?>>User ID</option>
	  </select>
	</p>
	<p>
	  <label for="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID;?>-channelurl">
	    <?php _e('Anchor Text Channel URL','autolastyoutubevideo');?>
	  </label>
	  <input class="widefat"
	    type="text"
	    name="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>[AUTO_LAST_YOUTUBE_VIDEO_visittext]"
	    id="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>-visittext"
	    value="<?php echo $AUTO_LAST_YOUTUBE_VIDEO_visittext; ?>"/>
	</p>
	<p>
	  <label for="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID;?>-number">
	    <?php _e('Number','autolastyoutubevideo');?>
	  </label>
	  <input class="widefat"
	    type="text"
	    name="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>[AUTO_LAST_YOUTUBE_VIDEO_number]"
	    id="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>-number" value="<?php echo $AUTO_LAST_YOUTUBE_VIDEO_number; ?>"/>
	</p>

	<input type="hidden"
	  name="<?php echo AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID; ?>[submit]"
	  value="1"/>
	<?php
}

// WIDGET
function widget_AUTO_LAST_YOUTUBE_VIDEO($args) {
	extract($args, EXTR_SKIP);
	$options = get_option(AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID);
	// Query the next scheduled post
	$AUTO_LAST_YOUTUBE_VIDEO_title = $options["AUTO_LAST_YOUTUBE_VIDEO_title"];
	$AUTO_LAST_YOUTUBE_VIDEO_introtext = $options["AUTO_LAST_YOUTUBE_VIDEO_introtext"];
	$AUTO_LAST_YOUTUBE_VIDEO_channelurl = $options["AUTO_LAST_YOUTUBE_VIDEO_channelurl"];
	$AUTO_LAST_YOUTUBE_VIDEO_type = $options["AUTO_LAST_YOUTUBE_VIDEO_type"];
	$AUTO_LAST_YOUTUBE_VIDEO_number = $options["AUTO_LAST_YOUTUBE_VIDEO_number"];
	$AUTO_LAST_YOUTUBE_VIDEO_visittext = $options["AUTO_LAST_YOUTUBE_VIDEO_visittext"];

	echo $before_widget;
	AUTO_LAST_YOUTUBE_VIDEO_showvideo($AUTO_LAST_YOUTUBE_VIDEO_channelurl,$AUTO_LAST_YOUTUBE_VIDEO_number,$AUTO_LAST_YOUTUBE_VIDEO_title,$AUTO_LAST_YOUTUBE_VIDEO_visittext,$AUTO_LAST_YOUTUBE_VIDEO_introtext,$AUTO_LAST_YOUTUBE_VIDEO_type);
	echo $after_widget;
}

function widget_AUTO_LAST_YOUTUBE_VIDEO_init() {
	wp_register_sidebar_widget(AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID,
			__('Auto Last Youtube Video'), 'widget_AUTO_LAST_YOUTUBE_VIDEO');
	wp_register_widget_control(AUTO_LAST_YOUTUBE_VIDEO_WIDGET_ID,
			__('AUTO_LAST_YOUTUBE_VIDEO'), 'widget_AUTO_LAST_YOUTUBE_VIDEO_control');
}

function autolastyoutubevideo_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'autolastyoutubevideo-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'autolastyoutubevideo-style' );
}

// Registrar el widget en WordPress
load_plugin_textdomain('autolastyoutubevideo', false, basename( dirname( __FILE__ ) ) . '/languages' );
add_action('wp_enqueue_scripts', 'autolastyoutubevideo_stylesheet');
add_action("plugins_loaded", "widget_AUTO_LAST_YOUTUBE_VIDEO_init");

//Registrar el shortcode
add_shortcode('auto_last_youtube_video', 'auto_last_youtube_video_func');


?>