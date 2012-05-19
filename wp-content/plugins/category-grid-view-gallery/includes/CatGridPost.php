<?php
/**
The file to load the post content in Colorbox. DO NOT REMOVE wp-config.php include..
it is needed for using wordpress functions.
Author : Anshul Sharma (contact@anshulsharma.in)
 */
if(!function_exists('get_post'))
{
require_once("../../../../wp-load.php");
}
$thepost = get_post($_GET["ID"]);
$thecontent = $thepost->post_content;
$thetitle = $thepost->post_title;
$thelink = get_permalink($_GET["ID"]);
?>
<div id="cg-post-container" style="width:<?php echo get_cg_option('lightbox_width'); ?>px;">
	<div id="cg-post-title">
		<a href="<?php echo $thelink; ?>"><?php echo $thetitle; ?></a>
    </div>
	<div id="cg-post-content">	
			<?php echo $thecontent; ?>
    </div>			
</div>

       
