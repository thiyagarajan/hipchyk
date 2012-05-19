<html>
<head>
<?php
    wp_enqueue_script('jquery'); 
?>
<style>
.itabs{
    padding: 20px;
}
.itabs li{
    float:left;
    width:32%;
}
</style>
</head>
<body>  
<h4>Abailable Tabs</h4>

<ul class="itabs">
<?php
    foreach($tabs as $tab){
       echo "<li><input rel='{$tab[tab_title]}' value='{$tab[id]}' class='tbc' type='checkbox' name='tab[]' /> {$tab[tab_title]}</li>" ;
    }
?>
</ul>
<div class="clear"></div>
<br />
<input type="button" class="button-primary" value="Insert" name="act" id="insert">

<script language="JavaScript">
<!--
  jQuery('#insert').click(function(){
      var tbs='';
      jQuery('.tbc').each(function(){
          if(this.checked){
              jQuery('#tg_<?php echo $_GET['tgid'];?>').append("<li class='button-secondary'><input type=hidden name='tg_tab[]' value='"+jQuery(this).val()+"' />"+jQuery(this).attr('rel')+" <a href='#' onclick='jQuery(\"#tb_<?php echo $_GET[tgid]; ?>_"+jQuery(this).val()+"\").fadeOut().remove();return false;'>[x]</a></li>");
          }
      });
   jQuery('.sortable').sortable();   
  tb_remove();
  });
//-->
</script>
</body>
</html>