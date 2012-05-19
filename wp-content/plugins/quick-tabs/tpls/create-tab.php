<div class="wrap">
    <div class="icon32">
    <img src="<?php echo plugins_url();?>/quick-tabs/images/tab_new.png" alt="">
    </div>
    
<h2><?php echo $_GET['task']==''?'New':'Edit';?> Tab 
</h2>

<form action="admin.php?page=quick-tabs&execute=<?php echo $_GET['task']==''?'wpqt_save_new_tab':'wpqt_update_tab';?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $tab[id]; ?>" />
<input type="hidden" name="redirect" value="admin.php?page=quick-tabs" />
 
<div  style="width: 75%;float:left;">   
<table cellpadding="5" cellspacing="5" width="100%">
<tr>
 
<td><input style="font-size:16pt;width:100%;color:<?php echo $tab['tab_title']?'#000':'#ccc'; ?>" onfocus="if(this.value=='Enter title here') {this.value=''; jQuery(this).css('color','#000'); }" onblur="if(this.value==''||this.value=='Enter title here') {this.value='Enter title here'; jQuery(this).css('color','#ccc');}" type="text" value="<?php echo $tab['tab_title']?$tab['tab_title']:'Enter title here'; ?>" name="tab[tab_title]" /></td>
</tr>

<tr>
<td valign="top"> 
<div id="poststuff" class="postarea">
                <?php the_editor(stripslashes($tab['tab_content']),'tab[tab_content]','tab[tab_content]', true); ?>
                <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
                <?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
                </div>
 
</td>
</tr>
</table>
</div>

<div style="float: right;width:23%">
  
<?php do_action("add_new_tab_sidebar"); ?> 

<div class="postbox " id="submitdiv">
<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Actions</span></h3>
<div class="inside">
<div id="submitpost" class="submitbox">

    

<div id="major-publishing-actions">
<div id="publishing-action">
<img alt="" id="ajax-loading" class="ajax-loading" src="images/wpspin_light.gif">
        <input type="hidden" value="Publish" id="original_publish" name="original_publish">
        <input type="submit" accesskey="p" tabindex="5" value="Save Tab" class="button-primary" id="publish" name="publish"></div>
<div class="clear"></div>
</div>
</div>

</div>
</div>
 
</div>

</form>

</div>
<script language="JavaScript">
<!--
  jQuery(function(){
      
     // jQuery('#editor-toolbar').prepend('<div style="float:left;margin-top:10px;">Tab Content:</div>');
      
  });
//-->
</script>