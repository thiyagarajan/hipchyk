/* Comments Likes Javascript */

function cl_like_this(ajaxurl,commentid) {
    var $j = jQuery.noConflict();
    var elem = "#comment-like-cnt-"+commentid;
    $j(elem).fadeOut("fast");
    var data = {
	action: 'cl_add_like',
	commentid: commentid
    };
    $j.post(ajaxurl, data, function(response) {
        document.getElementById("comment-like-cnt-"+commentid).innerHTML = response;
        $j(elem).fadeIn("slow");
    });
}