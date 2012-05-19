
<div class="wrap">
    <div class="icon32"><img src="<?php echo plugins_url();?>/quick-tabs/images/tabs.png" alt=""></div>
    
<h2>Tab Groups</h2>

<form method="post" action="admin.php?page=quick-tabs&execute=wpqt_save_tab_group">
<input type="hidden" name="redirect" value="admin.php?page=quick-tabs/tab-groups" />
<div id="submitdiv" class="postbox">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Create New Tab Group</span></h3>
<div class="inside">
<div class="insidec">
   <table>
   <tr><td>Group Name</td><td><input size="50" type="text" name="tg[tg_name]" value="<?php echo $tg['tg_name']; ?>"></td></tr>
   <!--<tr><td>Group Desciption</td><td><textarea name="tg[tg_content]"><?php echo $tg['tg_content']; ?></textarea></td></tr>-->
   </table>
</div>
<div class="submitbox" id="submitpost">
<div style="padding: 5px;" class="actionbar">    
<div id="publishing-action">
<img src="images/wpspin_light.gif" class="ajax-loading" id="ajax-loading" alt="">
        <input type="hidden" name="original_publish" id="original_publish" value="Publish">
        <input type="submit" name="publish" id="publish" class="button-primary" value="Create Group" tabindex="5" accesskey="p"></div>
<div class="clear"></div>
</div>
</div>

</div>
</div> 

</form>

<?php foreach($tgs as $tg){ ?>
<form class="tgform" action="admin.php?page=quick-tabs&execute=wpqt_update_tab_group" method="post">
<input type="hidden" name="tgid" value="<?php echo $tg['id']; ?>" />
<div id="submitdiv" class="postbox">
<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span><?php echo $tg['tg_name']; ?></span></h3>
<div class="inside">
<div class="insidec">
<ul id="tg_<?php echo $tg['id']; ?>" class="tabs sortable dragable">
<?php
    $tabs = @unserialize($tg['tg_tabs']);
    if(is_array($tabs)){
    foreach($tabs as $tab){
        $tab = wpqt_get_tab($tab);
        echo "<li id='tb_{$tg[id]}_{$tab[id]}' class='button-secondary'><input type=hidden name='tg_tab[]' value='{$tab[id]}' />{$tab[tab_title]} <a href='#' onclick='jQuery(\"#tb_{$tg[id]}_{$tab[id]}\").fadeOut().remove();return false;'>[x]</a></li>" ;
    }}
?>
</ul>

<div class="clear"></div>      
</div>
<div class="submitbox" id="submitpost">
<div style="padding: 5px;" class="actionbar">    
<div style="float: left;margin-top:5px;"><a href='admin.php?page=quick-tabs&execute=wpqt_delete_tg&id=<?php echo $tg['id'];?>' onclick="return confirm('are you sure?','yesno');" class="submitdelete deletion">delete</a>
 | place the shortcode anywhere inside page or post content <input type="text" size="25" style="text-align: center;" readonly="readonly" onfocus="this.select()" onclick="this.select()" value="[quick-tab tab_group=<?php echo $tg['id'];?>]">
</div>
<div id="publishing-action">
<img src="images/wpspin_light.gif" class="ajax-loading" id="ajax-loading" alt="">
        <input type="hidden" name="original_publish" id="original_publish" value="Publish">
        <a  href='admin.php?page=quick-tabs&execute=wpqt_add_tabs&tgid=<?php echo $tg['id']; ?>' class="thickbox button-primary" style="padding: 4px 10px;">Add Tab</a>        
        <input type="submit" name="publish" id="publish" class="button-primary" value="Save Changes" tabindex="5" accesskey="p"></div>
<div class="clear"></div>
</div>
</div>

</div>
</div>
</form>
<?php } ?>
  
</div>
<script language="JavaScript">
<!--
  jQuery(function(){       
      jQuery('.sortable').sortable();    
      var options = {
          beforesubmit: function(){
              
          },
          success: function(res){
            alert('saved')  ;
          }
      }
      jQuery('.tgform').submit(function(){
          jQuery(this).ajaxSubmit(options)
          return false;
      });
        
  });
//-->
</script>