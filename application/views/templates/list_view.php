<!-- DataTables -->
<?=link_tag('js/datatables/media/css/jquery.dataTables.css'); ?>
<script
	type="text/javascript"
	src="js/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	    $("<?=isset($id) ? '#' . $id : '.dataTable'; ?>").dataTable( {
	    	"bProcessing": true,
	    	"bServerSide": true,
	    	"sAjaxSource": "<?=isset($ajax_source) ? $ajax_source : ''; ?>",
            "fnServerData": function (sSource, aoData, fnCallback) {
                $.ajax
                ({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            },
            "fnInitComplete": function() {
	            this.fnAdjustColumnSizing(true);
            },
			"sPaginationType": "full_numbers",
			"iDisplayLength": "<?=isset($id) ? '10' : '25'; ?>",
			"oLanguage": {
				"sUrl": "<?=lang('table_language'); ?>"
			},
	    	"aaSorting": [[ "<?=isset($sort_column) ? $sort_column : 0; ?>", 
	    					"<?=isset($sort_order)  ? $sort_order  : 'asc'; ?>"]],
	    	"aoColumnDefs": [
	    		{
	    			"aTargets" : [ <?=isset($hide_columns) ? $hide_columns : ""; ?> ],
	    			"bVisible" : false
	    		}
	    	]
		});
	});
</script>

<?=isset($id) ? '' : heading($page_title, 2); ?>

<div id="list">
<?php
if (!isset($id)) {
	echo $this->session->flashdata('message');
} ?>

	<div id="info">
	<?=isset($page_info) ? $page_info : ''; ?>
	</div>

	<div class="data">
	<?=$this->table->generate(); ?>
	</div>

	<div id="actions">
	<?php
	if (isset($action_urls)) {
		echo '<h3>' . lang('actions') . '</h3>';
		echo '<ul>';
		foreach ($action_urls as $action_url) {
			echo '<li>' . anchor($action_url['url'], $action_url['title'], array('title' => $action_url['title'])) . '</li>';
		}
		echo '</ul>';
	}
	?>
	</div>
</div>
