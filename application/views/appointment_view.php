<!-- Calender -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.0.2/fullcalendar.css" />
<link rel="stylesheet" href="http://qtip2.com/v/2.2.0/jquery.qtip.min.css" />
<link rel="stylesheet" href="css/calendar.css" />
<script type="text/javascript" src="http://qtip2.com/v/2.2.0/jquery.qtip.min.js"></script>
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.0.2/fullcalendar.js"></script>
<script type="text/javascript" src="js/fullCalendar.nl.js"></script>

<script>
	$(document).ready(function() {
		//Page is now ready, initialize calender
		$('#calendar').fullCalendar({
			weekNumbers: {month: true, basicWeek: true, 'default': false},
			lang: '<?=$lang;?>',
			timeFormat: 'HH:mm',
			events: <?=$events;?>,
		    header:
		    {
			    left: 'prev,next,today',
				center: 'title',
			    right: 'month,agendaWeek,agendaDay',
		    },
		    eventHeight: 50,
		    displayEventEnd: true,
		    slotEventOverlap: false, 
		    eventRender: function(event, element) {
				element.css('cursor', 'pointer');
		        element.qtip({
		            content: stripslashes(event.tooltip),
		            position: {
		                my: 'bottom left',
		                at: 'top left'
		            },
			        show: {
			            event: 'click'
			        },
			        hide: {
				        event: 'click',
				        inactive: 3000,
			        }
		        });
		        element.add(event.message);
		    },
		});

		// Legend button
		var legendButton = '<span unselectable="on" id="legend"';
		legendButton += 'class="fc-button fc-button-next fc-state-default"';
		legendButton += 'style="-moz-user-select: none;"><?=lang('legend'); ?></span>';
		$('#calendar td.fc-header-left').append(legendButton);
		$('#legend').qtip({
			content: '<?=$legend;?>',
			position: {
				my: 'top center',
				at: 'bottom center'
			},
			show: {
				event: 'click'
			},
			hide: {
				event: 'click'
			}
		});
	});

	
	function stripslashes(str) {
		str=str.replace(/\\'/g,'\'');
		str=str.replace(/\\"/g,'"');
		str=str.replace(/\\0/g,'\0');
		str=str.replace(/\\\\/g,'\\');
		return str;
	}
</script>

<?=heading($page_title); ?>
<div id='calendar'></div>