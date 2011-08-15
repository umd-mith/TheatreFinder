<link href="<?php echo base_url(); ?>theatres/exhibit_json" type="application/json" rel="exhibit/data" />

<script src="http://static.simile.mit.edu/exhibit/api-2.0/exhibit-api.js"
		type="text/javascript"></script>
<script src="http://static.simile.mit.edu/exhibit/extensions-2.0/time/time-extension.js"></script>
<script type="text/javascript">
function confirmDeleteTheatre(url) {
  if (confirm("Are you sure you want to delete the theatre?")) {
    document.location = url;
  }
}
</script>
<link rel="stylesheet" href="<?php echo base_url();?>css/exhibit.css" />