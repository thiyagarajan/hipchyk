<?php
$url = $_SERVER['REQUEST_URI'];

$pluginUrl = explode('function',$url);
$ajaxurl =  $pluginUrl[0] . 'functions.php';
?>
function submitILike(id){
                        var url = "<?php echo $ajaxurl ;?>";
                        new Ajax.Request(url, {
                              method: "post",
                              parameters: "id="+id,
                              onSuccess: function(transport) {
                                if(transport.responseText!="")
                                  {
                                      alert(transport.responseText);
                                  }
                                  else
                                  {
                                      if($("ilike"+id))
                                        $("ilike"+id).innerHTML = parseInt($("ilike"+id).innerHTML) + 1 ;
                                  }
                              }
                        });
                }
