<div class="wrap">
<div class="icon32"><img src="<?php echo plugins_url();?>/quick-tabs/images/tabs.png" alt=""></div>
    
<h2>Quick Tabs <a class="button add-new-h2" href="admin.php?page=quick-tabs/add-new">add new</a> 
</h2>


<form method="get" action="" id="posts-filter">
<div class="tablenav">

<div class="alignleft actions">
<select class="select-action" name="task">
<option selected="selected" value="">Bulk Actions</option>
<option value="DeleteFile">Delete Permanently</option>
</select>
<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="Apply">
 
  


</div>

<br class="clear">
</div>

<div class="clear"></div>

<table cellspacing="0" class="widefat fixed">
    <thead>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>    
    <th style="" class="manage-column column-media" id="media" scope="col">Tab Name</th>    
    <th style="" class="manage-column column-author" id="author" scope="col">Content Type</th>    
    <!--<th style="" class="manage-column column-media" id="media" scope="col">Embed code</th>-->
    </tr>
    </thead>

    <tfoot>
    <tr>
    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>    
    <th style="" class="manage-column column-media" id="media" scope="col">Tab Name</th>
    <th style="" class="manage-column column-author" id="author" scope="col">Content Type</th>    
    <!--<th style="" class="manage-column column-media" id="media" scope="col">Embed code</th>-->
    </tr>
    </tfoot>

    <tbody class="list:post" id="the-list">
    <?php foreach($tabs as $tab) { 
        
                         
        ?>
    <tr valign="top" class="alternate author-self status-inherit" id="post-8">

                <th class="check-column" scope="row"><input type="checkbox" value="8" name="id[]"></th>                
                <td class="media column-media">
                    <strong><a title="Edit" href="admin.php?page=quick-tabs&task=wpqt_edit_tab&id=<?php echo $tab['id']?>"><?php echo $tab['tab_title']?></a></strong><br>                
                    <div class="row-actions"><span class="edit"><a href="admin.php?page=quick-tabs&task=wpqt_edit_tab&id=<?php echo $tab['id']?>">Edit</a> | </span><span class="delete"><a href="admin.php?page=quick-tabs&execute=wpqt_delete_tab&id=<?php echo $tab['id']?>" onclick="return showNotice.warn();" class="submitdelete">Delete Permanently</a></div>
                </td>
                <td class="author column-author"><?php echo $tab['tab_content_type']; ?></td>                
                <!--<td class="parent column-parent"><input style="text-align:center" type="text" onclick="this.select()" size="20" title="Simply Copy and Paste in post contents" value="[quick-tabs tabid=<?php echo $tab['id'];?>]" /></td>                     -->
     </tr>
     <?php } ?>
    </tbody>
</table>

<?php
$page_links = paginate_links( array(
    'base' => add_query_arg( 'paged', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($total/$limit),
    'current' => $_GET['paged']
));


?>

<div id="ajax-response"></div>

<div class="tablenav">

<?php if ( $page_links ) { ?>
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
    number_format_i18n( ( $_GET['paged'] - 1 ) * $limit + 1 ),
    number_format_i18n( min( $_GET['paged'] * $limit, $total ) ),
    number_format_i18n( $row[total] ),
    $page_links
); echo $page_links_text; ?></div>
<?php } ?>

<div class="alignleft actions">
<select class="select-action" name="action2">
<option selected="selected" value="-1">Bulk Actions</option>
<option value="delete">Delete Permanently</option>
</select>
<input type="submit" class="button-secondary action" id="doaction2" name="doaction2" value="Apply">

</div>

<br class="clear">
</div>
    <div style="display: none;" class="find-box" id="find-posts">
        <div class="find-box-head" id="find-posts-head">Find Posts or Pages</div>
        <div class="find-box-inside">
            <div class="find-box-search">
                
                <input type="hidden" value="" id="affected" name="affected">
                <input type="hidden" value="3a4edcbda3" name="_ajax_nonce" id="_ajax_nonce">                <label for="find-posts-input" class="screen-reader-text">Search</label>
                <input type="text" value="" name="ps" id="find-posts-input">
                <input type="button" class="button" value="Search" onclick="findPosts.send();"><br>

                <input type="radio" value="posts" checked="checked" id="find-posts-posts" name="find-posts-what">
                <label for="find-posts-posts">Posts</label>
                <input type="radio" value="pages" id="find-posts-pages" name="find-posts-what">
                <label for="find-posts-pages">Pages</label>
            </div>
            <div id="find-posts-response"></div>
        </div>
        <div class="find-box-buttons">
            <input type="button" value="Close" onclick="findPosts.close();" class="button alignleft">
            <input type="submit" value="Select" class="button-primary alignright" id="find-posts-submit">
        </div>
    </div>
</form>

</div>