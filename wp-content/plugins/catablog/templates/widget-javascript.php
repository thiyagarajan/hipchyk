<script type='text/javascript'>
/* <![CDATA[ */
	var cb_dropdown = document.getElementById("catablog-terms");
	function onCataBlogCatChange() {
		if ( cb_dropdown.options[cb_dropdown.selectedIndex].value.length > 0 ) {
			location.href = "%%HOME_URL%%/?catablog-terms="+cb_dropdown.options[cb_dropdown.selectedIndex].value;
		}
	}
	cb_dropdown.onchange = onCataBlogCatChange;
/* ]]> */
</script>
