<?php
/**
 *     Image slider with description
 *     Copyright (C) 2012  www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	
?>

<div class="wrap">
  <?php
  	global $wpdb;
    @$title = __('Image slider with description');
    @$mainurl = get_option('siteurl')."/wp-admin/admin.php?page=ImgSlider_image_management";
    @$DID=@$_GET["DID"];
    @$AC=@$_GET["AC"];
    @$submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['ImgSlider_link']) <>"")
    {
			if($_POST['ImgSlider_id'] == "" )
			{
					$sql = "insert into ".WP_ImgSlider_TABLE.""
					. " set `ImgSlider_path` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_path']))
					. "', `ImgSlider_link` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_link']))
					. "', `ImgSlider_target` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_target']))
					. "', `ImgSlider_title` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_title']))
					. "', `ImgSlider_desc` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_desc']))
					. "', `ImgSlider_order` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_order']))
					. "', `ImgSlider_status` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_status']))
					. "', `ImgSlider_type` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_type']))
					. "'";	
			}
			else
			{
					$sql = "update ".WP_ImgSlider_TABLE.""
					. " set `ImgSlider_path` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_path']))
					. "', `ImgSlider_link` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_link']))
					. "', `ImgSlider_target` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_target']))
					. "', `ImgSlider_title` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_title']))
					. "', `ImgSlider_desc` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_desc']))
					. "', `ImgSlider_order` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_order']))
					. "', `ImgSlider_status` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_status']))
					. "', `ImgSlider_type` = '" . mysql_real_escape_string(trim($_POST['ImgSlider_type']))
					. "' where `ImgSlider_id` = '" . $_POST['ImgSlider_id'] 
					. "'";	
			}
			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".WP_ImgSlider_TABLE." where ImgSlider_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".WP_ImgSlider_TABLE." where ImgSlider_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $ImgSlider_id_x = htmlspecialchars(stripslashes($data->ImgSlider_id)); 
		if ( !empty($data) ) $ImgSlider_path_x = htmlspecialchars(stripslashes($data->ImgSlider_path)); 
        if ( !empty($data) ) $ImgSlider_link_x = htmlspecialchars(stripslashes($data->ImgSlider_link));
		if ( !empty($data) ) $ImgSlider_target_x = htmlspecialchars(stripslashes($data->ImgSlider_target));
        if ( !empty($data) ) $ImgSlider_title_x = htmlspecialchars(stripslashes($data->ImgSlider_title));
		if ( !empty($data) ) $ImgSlider_desc_x = htmlspecialchars(stripslashes($data->ImgSlider_desc));
		if ( !empty($data) ) $ImgSlider_order_x = htmlspecialchars(stripslashes($data->ImgSlider_order));
		if ( !empty($data) ) $ImgSlider_status_x = htmlspecialchars(stripslashes($data->ImgSlider_status));
		if ( !empty($data) ) $ImgSlider_type_x = htmlspecialchars(stripslashes($data->ImgSlider_type));
        $submittext = "Update Message";
    }
    ?>
  <h2>Image slider with description</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/image-slider-with-description/setting.js"></script>
  <form name="ImgSlider_form" method="post" action="<?php echo @$mainurl; ?>" onsubmit="return ImgSlider_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="2" align="left" valign="middle">Enter Image Path:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="ImgSlider_path" type="text" id="ImgSlider_path" value="<?php echo @$ImgSlider_path_x; ?>" size="125" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter Target Link:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="ImgSlider_link" type="text" id="ImgSlider_link" value="<?php echo @$ImgSlider_link_x; ?>" size="125" /></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter Target Option:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">
        <select name="ImgSlider_target" id="ImgSlider_target">
        	<option value='_blank' <?php if(@$ImgSlider_target_x=='_blank') { echo 'selected' ; } ?>>_blank</option>
            <option value='_parent' <?php if(@$ImgSlider_target_x=='_parent') { echo 'selected' ; } ?>>_parent</option>
            <option value='_self' <?php if(@$ImgSlider_target_x=='_self') { echo 'selected' ; } ?>>_self</option>
            <option value='_new' <?php if(@$ImgSlider_target_x=='_new') { echo 'selected' ; } ?>>_new</option>
          </select>
        </td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter Image Title:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="ImgSlider_title" type="text" id="ImgSlider_title" value="<?php echo @$ImgSlider_title_x; ?>" size="125" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter Image Description:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="ImgSlider_desc" type="text" id="ImgSlider_desc" value="<?php echo @$ImgSlider_desc_x; ?>" size="125" /></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Select Gallery Group (This is to group the images):</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">
        <select name="ImgSlider_type" id="ImgSlider_type">
            <option value='GROUP1' <?php if(@$ImgSlider_type_x=='GROUP1') { echo 'selected' ; } ?>>Group1</option>
            <option value='GROUP2' <?php if(@$ImgSlider_type_x=='GROUP2') { echo 'selected' ; } ?>>Group2</option>
            <option value='GROUP3' <?php if(@$ImgSlider_type_x=='GROUP3') { echo 'selected' ; } ?>>Group3</option>
            <option value='GROUP4' <?php if(@$ImgSlider_type_x=='GROUP4') { echo 'selected' ; } ?>>Group4</option>
            <option value='GROUP5' <?php if(@$ImgSlider_type_x=='GROUP5') { echo 'selected' ; } ?>>Group5</option>
            <option value='GROUP6' <?php if(@$ImgSlider_type_x=='GROUP6') { echo 'selected' ; } ?>>Group6</option>
            <option value='GROUP7' <?php if(@$ImgSlider_type_x=='GROUP7') { echo 'selected' ; } ?>>Group7</option>
            <option value='GROUP8' <?php if(@$ImgSlider_type_x=='GROUP8') { echo 'selected' ; } ?>>Group8</option>
            <option value='GROUP9' <?php if(@$ImgSlider_type_x=='GROUP9') { echo 'selected' ; } ?>>Group9</option>
            <option value='GROUP0' <?php if(@$ImgSlider_type_x=='GROUP0') { echo 'selected' ; } ?>>Group0</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="left" valign="middle">Display Status:</td>
        <td align="left" valign="middle">Display Order:</td>
      </tr>
      <tr>
        <td width="22%" align="left" valign="middle"><select name="ImgSlider_status" id="ImgSlider_status">
            <option value='YES' <?php if(@$ImgSlider_status_x=='YES') { echo 'selected' ; } ?>>Yes</option>
            <option value='NO' <?php if(@$ImgSlider_status_x=='NO') { echo 'selected' ; } ?>>No</option>
          </select>
        </td>
        <td width="78%" align="left" valign="middle"><input name="ImgSlider_order" type="text" id="ImgSlider_rder" size="10" value="<?php echo @$ImgSlider_order_x; ?>" maxlength="3" /></td>
      </tr>
      <tr>
        <td height="35" colspan="2" align="left" valign="bottom"><table width="100%">
            <tr>
              <td width="50%" align="left"><input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
                <input name="publish" lang="publish" class="button-primary" onclick="ImgSlider_redirect()" value="Cancel" type="button" />
				<input name="Help" lang="publish" class="button-primary" onclick="ImgSlider_help()" value="Help" type="button" />
              </td>
              <td width="50%" align="right">
			  </td>
            </tr>
          </table></td>
      </tr>
      <input name="ImgSlider_id" id="ImgSlider_id" type="hidden" value="<?php echo @$ImgSlider_id_x; ?>">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WP_ImgSlider_TABLE." order by ImgSlider_type,ImgSlider_order");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="frm_ImgSlider_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="8%" align="left" scope="col">Type
              </td>
            <th align="left" scope="col">Title
              </td>
            <th width="5%" align="left" scope="col">Order
              </td>
            <th width="5%" align="left" scope="col">Display
              </td>
            <th width="8%" align="left" scope="col">Action
              </td>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		if($data->ImgSlider_status=='YES') { $displayisthere="True"; }
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->ImgSlider_type)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->ImgSlider_title)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->ImgSlider_order)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->ImgSlider_status)); ?></td>
            <td align="left" valign="middle"><a href="admin.php?page=ImgSlider_image_management&DID=<?php echo($data->ImgSlider_id); ?>">Edit</a> &nbsp; <a onClick="javascript:ImgSlider_delete('<?php echo($data->ImgSlider_id); ?>')" href="javascript:void(0);">Delete</a> </td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
        <?php if(@$displayisthere<>"True") { ?>
        <tr>
          <td colspan="5" align="center" style="color:#FF0000" valign="middle">No message available with display status 'Yes'!' </td>
        </tr>
        <?php } ?>
      </table><br />
	  Note: Use the short code to add the gallery in to the posts and pages.
     <br /><br />  Check official website <a target="_blank" href="http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/">www.gopiplus.com</a> for live demo and more info.
</form>
  </div>
</div>