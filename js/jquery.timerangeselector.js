(function($) {  
    $.widget("ui.selectorTable", {
        first: null, last: null, add: "ui-custom-selected", mouseDown: 0, hourComplete: 60,
        options:
        {
            resolution: 10,
            tableClass: null,
            hourStart: 0,
            hourEnd: 23,
            hourText: '',
            selected: null,
            hourFormat24: true,
        },
        _create: function() {
            var that = this;
            var o = that.options;

            that.element.addClass("timeslot-picker");

            that._hourComplete = 60 - o.resolution;

            // Start the table creation
            var table = '<table ';
            if (o.tableClass != null)
                {
                    table += ' class="' + o.tableClass + '" ';
                }
            table += 'id="select-time-table">';
            
            // Create the head with the right resolution
            table += '<thead><tr><th></th>';

            for (i = 0; i <= that._hourComplete; i = i+o.resolution)
            {
                table += '<th class="timepicker-header">' + i + '</th>';
            }
            
            table += '</tr></thead>';

            // Create body
            for (i = o.hourStart; i <= o.hourEnd; i++)
            {
                table += '<tr id="' + that._mkDouble(i) + '" class="hour"><th class="time-picker-row-header">' + that._timeFromHour(i) + ' ' + o.hourText + '</th>';
                for (j = 0; j <= that._hourComplete; j = j + o.resolution)
                {
                    table += '<td id="' + that._mkDouble(i) + '-' + that._mkDouble(j) + '">';
                    table += '</td>';
                }
                table += "</tr>";
            }


            // Close table and append to element
            table += '</table>';
            this.element.append(table);

            // Instance of the table
            t = this.element.children(["table"]);

            // Make the table unselectable
            t.attr('unselectable','on').attr('unselectable','on')
            .css({'-moz-user-select':'-moz-none',
               '-moz-user-select':'none',
               '-o-user-select':'none',
               '-khtml-user-select':'none', /* you could also put this in a class */
               '-webkit-user-select':'none',/* and add the CSS class here instead */
               '-ms-user-select':'none',
               'user-select':'none'
            }).bind('selectstart', function(){ return false; });

            that._preSelect(o.selected);

            $("#select-time-table td").mousedown(function(){
                that.mouseDown = 1;
                that.first = $(this);
                that.last = $(this);
                if ($(this).hasClass("ui-custom-selected")){
                    that.add = "ui-custom-unselecting";
                } else {
                    that.add = "ui-custom-selecting";
                }
                
                that._changeSelection();

            }).mouseover(function(){
                if (that.mouseDown == 1)
                {
                    if (!$(this).hasClass(that.add))
                    {
                        that.last = $(this);
                        var alreadySelected = 0;
                        that._changeSelection();
                    }
                    else
                    {
                        that._removeFromSelection($(this));
                    }

                    // Hack to keep current selected
                    $(this).addClass(that.add);
                }
            }).mouseup(function(){
                that.mouseDown = 0;
                
                that.last = $(this);
                that._changeSelection();
                that._finishSelection();
            });

            $(document).mouseup(function(){
                that.mouseDown = 0;
                that._finishSelection();
            });
        },
        _destroy: function() {
            this.element.removeClass("timeslot-picker");
            this.element.remove();
        },
        getTimes: function()
        {
            var that = this;
            var result = [], inContinue = false, start = null, end = null;
            $("#select-time-table tbody td").each(function(c, cell){
                if (inContinue)
                {
                    if ($(cell).hasClass('ui-custom-selected'))
                    {
                        end = that._toTime(cell, true);
                    } else {
                        var newTime = new Array(start,end);
                        result.push(newTime);
                        start = null; end = null; inContinue = false;
                    }
                } else {
                    if ($(cell).hasClass('ui-custom-selected'))
                    {
                        inContinue = true;
                        start = that._toTime(cell);
                        end = that._toTime(cell, true);
                    }
                }                       
            });
            return result;
        },
        reset: function()
        {
            $("#select-time-table tbody td").each(function(c, cell){
                $(cell).removeClass("ui-custom-selected");
                $(cell).children()
            });
            $('#select-time-table tbody td :checkbox:checked').prop('checked',false);
        },
        _changeSelection: function()
        {
            var that = this;
            if (that.first.attr("id") < that.last.attr("id"))
            {
                that._select(that.first, that.last);
            } else {
                that._select(that.last, that.first);
            }  
        },        
        _select: function(f,l)
        {
            that = this;
            o = that.options;

            // Split to iX[row][col]
            var iF = f.attr("id").split("-");
            var iL = l.attr("id").split("-");
            
            // If selection spans multiple rows...
            if (iF[0] < iL[0])
            {
                // Fill in the first row from the selected element till the end
                for(var i = parseInt(iF[1],10); i <= that.hourComplete; i += o.resolution)
                {
                    $("#" + iF[0] + "-" + that._mkDouble(i)).addClass(that.add);
                }        
                // Fill in all the rows in between the first and last selected element
                for(var k = parseInt(iF[0], 10) + 1; k < iL[0]; k++)
                {
                    for (var j = 0; j <= that._hourComplete; j+= o.resolution)
                    {
                        $("#" + that._mkDouble(k) + "-" + that._mkDouble(j)).addClass(that.add);
                    }
                }
                
                // Fill in the last row from the beginning till the last selected element
                for(var n = 0; n <= iL[1]; n+= o.resolution)
                {
                   $("#" + iL[0] + "-" + that._mkDouble(n)).addClass(that.add);           
                }
            }
            else
            {
                for (var m = parseInt(iF[1], 10); m <= iL[1]; m += o.resolution)
                {
                   $("#" + iF[0] + "-" + that._mkDouble(m)).addClass(that.add);
                }
            }
        },
        _unselect: function(f, l)
        {
            var that = this;
            
            // Split to iX[row][col]
            var iF = f.attr("id").split("-");
            var iL = l.attr("id").split("-");

            var unAdd = (that.add == "ui-custom-selecting") ? "ui-custom-unselecting" : "ui-custom-selecting";

            // If selection spans multiple rows...
            if (iF[0] < iL[0])
            {
                // Fill in the first row from the selected element till the end
                for(var i = parseInt(iF[1],10); i <= that._hourComplete; i += o.resolution)
                {
                    $("#" + iF[0] + "-" + that._mkDouble(i)).removeClass(that.add);
                }        
                // Fill in all the rows in between the first and last selected element
                for(var k = parseInt(iF[0], 10) + 1; k < iL[0]; k++)
                {
                    for (var j = 0; j <= that._hourComplete; j += o.resolution)
                    {
                        $("#" + that._mkDouble(k) + "-" + that._mkDouble(j)).removeClass(that.add);
                    }
                }

                // Fill in the last row from the beginning till the last selected element
                for(var n = 0; n <= iL[1]; n += o.resolution)
                {
                    $("#" + iL[0] + "-" + that._mkDouble(n)).removeClass(that.add);  
                }
            }
            else
            {
                for (var m = parseInt(iF[1],10); m <= iL[1]; m += o.resolution)
                {
                    $("#" + iF[0] + "-" + that._mkDouble(m)).removeClass(that.add);
                }
            }
        },
        _removeFromSelection: function(item)
        {
            var that = this;
            if (that.last.attr("id") < item.attr("id"))
            {
                that._unselect(that.last, item);
            } else {
                that._unselect(item, that.last);
            }
        },
        _finishSelection: function()
        {
            var that = this;
            $(".ui-custom-selecting").each(function(){
                $(this).removeClass("ui-custom-selecting");
                $(this).addClass("ui-custom-selected");
            });
            
            $(".ui-custom-unselecting").each(function(){
                $(this).removeClass("ui-custom-unselecting");
                $(this).removeClass("ui-custom-selected");
            }); 
        },
        
        
        _toTime: function(cell, last=false)
        {
            var that = this;
            var o = that.options;

            var times = $(cell).attr("id").split("-");

            var hour = parseInt(times[0]);

            var pm = "";
            if (!o.hourFormat24)
            {
                if (hour > 12)
                {
                    hour = hour - 12;
                    pm = " pm";
                } else {
                    pm = " am";
                }
            }

            var minutes = parseInt(times[1]);
            if (last)
            {
                minutes += that.options.resolution;
                if (minutes >= 60)
                {
                    hour++;
                    minutes = 0;
                }
            }

            if (minutes == 0)
            {
                minutes = "00";
            }

            return hour + ":" + minutes + pm;
        },
        _mkDouble: function(i)
        {
            if ( i < 10)
            {
                return "0" + i;
            }
            else
            {
                return i;
            } 
        },
        _preSelect: function(times)
        {
            var that = this;
            o = that.options;

            $(times).each(function(){
                var startTime = this[0].split(":");
                var endTime = this[1].split(":");

                endTime[1] = parseInt(endTime[1]) - o.resolution;

                if (endTime[1] < 0)
                {
                    endTime[1] = (60 - parseInt(o.resolution));
                    endTime[0]--;
                }

                var startTD = $("#" + startTime[0] + "-" + startTime[1]);
                var endTD = $("#" + endTime[0] + "-" + endTime[1]);

                var nonExists = $("#100-32");

                if (startTD.attr("id") != null && 
                    endTD.attr("id") != null &&
                    startTD.attr("id") <= endTD.attr("id"))
                {
                    that._select(startTD, endTD);
                }
            });
        },
        _timeFromHour: function(hour)
        {
            var that = this; var o = that.options;

            var am = " am";
            if (o.hourFormat24)
            {
                return hour;
            }
            else 
            {
                if (parseInt(hour) > 12)
                {
                    return (parseInt(hour) - 12) + " pm";
                } else {
                    return [hour + " am"];
                }
            }
        }
    });
})(jQuery);








