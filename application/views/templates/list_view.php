<!-- DataTables -->
<?=link_tag('js/datatables/media/css/jquery.dataTables.css'); ?>
<script type="text/javascript" src="//cdn.datatables.net/1.9.4/js/jquery.dataTables.min.js"></script>
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
			"iDisplayLength": <?=isset($id) ? 10 : 25; ?>,
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
if (!isset($id)) 
{
	echo $this->session->flashdata('message');
}
?>

	<div id="info">
		<?=isset($page_info) ? $page_info : ''; ?>
	</div>

	<div class="data">
		<?=$this->table->generate(); ?>
	</div>

	<div id="actions">
		<?php
		if (isset($action_urls) && $action_urls) 
		{
			echo heading(lang('actions'), 3);
			$actions = array();
			foreach ($action_urls as $action_url) 
			{
				array_push($actions, anchor($action_url['url'], $action_url['title'], array('title' => $action_url['title'])));
			}
			echo ul($actions);
		}
		?>
	</div>

	<div id="filters">
		<?php
		if (isset($filter_options) && $filter_options) 
		{
			echo heading(lang('filters'), 3);
			echo form_open(current_url(), array('class' => 'pure-form'));
			$filters = array();
			foreach ($filter_options as $f) 
			{
				echo form_single_checkbox_and_label($f['name'], $f['value'], $f['checked'], '', '', TRUE);
				echo br();
			}
			echo form_submit_only();
			echo form_close();
		}
		?>
	</div>
</div>
