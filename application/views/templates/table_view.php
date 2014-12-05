<!-- DataTables -->
<?=link_tag('js/datatables/media/css/jquery.dataTables.css'); ?>
<script
	type="text/javascript"
	src="js/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	    $('.dataTable').dataTable( {
			"sPaginationType": "full_numbers",
			"iDisplayLength": 25,
			"oLanguage": {
				"sUrl": "<?=lang('table_language'); ?>"
			},
	    	"aaSorting": [[ "<?=isset($sort_column) ? $sort_column : 0; ?>", 
	    					"<?=isset($sort_order)  ? $sort_order  : 'asc'; ?>"]]
		});
	});
</script>

<?=isset($id) ? '' : heading($page_title, 2); ?>

<div id="list">
<?=$this->session->flashdata('message'); ?>

	<div id="info">
	<?=isset($page_info) ? $page_info : ''; ?>
	</div>

	<div class="data">
	<?=$table; ?>
	</div>
</div>
