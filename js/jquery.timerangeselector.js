(function($) {  
    $.widget("ui.selectorTable", {
        first: null, last: null, add: null, mouseDown: 0,
        options:
        {
            resolution: 10,
            tableClass: null,
            hourStart: 0,
            hourEnd: 23,
            hourText: 'uur',
            selected: null
        },
        _create: function() {
            var first, last, add, mouseDown;
            var o = this.options;

            // Start the table creation
            var table = '<table ';
            if (o.tableClass != null)
                {
                    table += ' class="' + o.tableClass + '" ';
                }
            table += 'id="select-time-table">';
            
            // Create the head with the right resolution
            table += '<thead><tr><th></th>';

            for (i = 0; i <= 60; i = i+o.resolution)
            {
                table += '<th>' + i + '</th>';
            }
            
            table += '</tr></thead>';

            // Create body
            for (i = o.hourStart; i <= o.hourEnd; i++)
            {
                table += '<tr id="' + mkDouble(i) + '" class="hour"><th>' + i + ' ' + o.hourText + '</th>';
                for (j = 0; j <= 60; j = j + o.resolution)
                {
                    table += '<td id="' + mkDouble(i) + '-' + j + '"><input type="checkbox" unchecked name="time[' + mkDouble(i) + '-' + j + ']"/></td>';
                }
                table += "</tr>";
            }


            // Close table and append to element
            table += '</table>';
            this.element.append(table);

            //$('#select-time-table').css('background-color', 'red');

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

            $("#select-time-table td").mousedown(function(){
                mouseDown = 1;
                first = $(this);
                last = $(this);
                if ($(this).hasClass("ui-custom-selected")){
                    add = "ui-custom-unselecting";
                } else {
                    add = "ui-custom-selecting";
                }
                
                changeSelection();

            }).mouseover(function(){
                if (mouseDown == 1)
                {
                    if (!$(this).hasClass(add))
                    {
                        last = $(this);
                        var alreadySelected = 0;
                        changeSelection();
                    }
                    else
                    {
                        removeFromSelection($(this));
                    }

                    // Hack to keep current selected
                    $(this).addClass(add);
                }
            }).mouseup(function(){
                mouseDown = 0;
                
                last = $(this);
                changeSelection();
                finishSelection();
            });

            $(document).mouseup(function(){
                mouseDown = 0;
                finishSelection();
            });

            function changeSelection(){
                if (first.attr("id") < last.attr("id"))
                {
                    select(first, last);
                } else {
                    select(last, first);
                }
            }

            function removeFromSelection(item)
            {
                if (last.attr("id") < item.attr("id"))
                {
                    unselect(last, item);
                } else {
                    unselect(item, last);
                }
            }

            function select(f, l)
            {
                // Split to iX[row][col]
                var iF = f.attr("id").split("-");
                var iL = l.attr("id").split("-");
                
                // If selection spans multiple rows...
                if (iF[0] < iL[0])
                {
                    // Fill in the first row from the selected element till the end
                    for(var i = parseInt(iF[1],10); i <= 60; i += o.resolution)
                    {
                        $("#" + iF[0] + "-" + i).addClass(add);
                    }        
                    // Fill in all the rows in between the first and last selected element
                    for(var k = parseInt(iF[0], 10) + 1; k < iL[0]; k++)
                    {
                        for (var j = 0; j <= 60; j+= o.resolution)
                        {
                            $("#" + mkDouble(k) + "-" + j).addClass(add);
                        }
                    }
                    
                    // Fill in the last row from the beginning till the last selected element
                    for(var n = 0; n <= iL[1]; n+= o.resolution)
                    {
                       $("#" + iL[0] + "-" + n).addClass(add);           
                    }
                }
                else
                {
                    for (var m = parseInt(iF[1], 10); m <= iL[1]; m += o.resolution)
                    {
                       $("#" + iF[0] + "-" + m).addClass(add);
                    }
                }
            }

            function unselect(f, l)
            {
               // Split to iX[row][col]
               var iF = f.attr("id").split("-");
               var iL = l.attr("id").split("-");
                
                var unAdd = (add == "ui-custom-selecting") ? "ui-custom-unselecting" : "ui-custom-selecting";
                
                // If selection spans multiple rows...
                if (iF[0] < iL[0])
                {
                    // Fill in the first row from the selected element till the end
                    for(var i = parseInt(iF[1],10); i <= 60; i += o.resolution)
                    {
                        $("#" + iF[0] + "-" + i).removeClass(add);
                    }        
                    // Fill in all the rows in between the first and last selected element
                    for(var k = parseInt(iF[0], 10) + 1; k < iL[0]; k++)
                    {
                        for (var j = 0; j <= 60; j += o.resolution)
                        {
                            $("#" + mkDouble(k) + "-" + j).removeClass(add);
                        }
                    }
                    
                    // Fill in the last row from the beginning till the last selected element
                    for(var n = 0; n <= iL[1]; n += o.resolution)
                    {
                       $("#" + iL[0] + "-" + n).removeClass(add);  
                    }
                }
                else
                {
                    for (var m = parseInt(iF[1],10); m <= iL[1]; m += o.resolution)
                    {
                       $("#" + iF[0] + "-" + m).removeClass(add);
                    }
                }
            }

            function finishSelection()
            {
                $(".ui-custom-selecting").each(function(){
                    $(this).removeClass("ui-custom-selecting");
                    $(this).addClass("ui-custom-selected");
                    $(this).children(["input"]).prop("checked", true);
                });
                
                $(".ui-custom-unselecting").each(function(){
                    $(this).removeClass("ui-custom-unselecting");
                    $(this).removeClass("ui-custom-selected");
                    $(this).children(["input"]).prop("checked", false);
                });
            }

            function mkDouble(i)
            {
               if ( i < 10)
                {
                    return "0" + i;
                }
                else
                {
                    return i;
                } 
            }


        },
        _destroy: function() {
            this.element.remove();
        },
        getTimes: function()
        {
            // Returns Json with times
        }


    });
})(jQuery);








