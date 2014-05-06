
        google.load("visualization", "1");

        // Set callback to run when API is loaded
        google.setOnLoadCallback(drawVisualization);

        var timeline;
        var data;

        // Make a callback function for the select item
        var onselect = function (event) {
            var row = getSelectedRow();
        };

        // callback function for the change item
        var onchange = function () {
            var row = getSelectedRow();
        };

        // callback function for the delete item
        var ondelete = function () {
            var row = getSelectedRow();
        };

        // callback function for the edit item
        var onedit = function () {
            var row = getSelectedRow();
            var content = data.getValue(row, 2);
            var newContent = prompt("Enter content", content);
            if (newContent != undefined) {
                data.setValue(row, 2, newContent);
            }
            timeline.redraw();
        };

        // callback function for the add item
        var onadd = function () {
            var row = getSelectedRow();
            var content = data.getValue(row, 2);
            var newContent = prompt("Enter content", content);
            if (newContent != undefined) {
                data.setValue(row, 2, newContent);
                timeline.redraw();
            }
            else {
                // cancel adding the item
                timeline.cancelAdd();
            }
        };


        function onrangechange() {
            // adjust the values of startDate and endDate
            var range = timeline.getVisibleChartRange();
        }

        function onrangechanged() {
            //document.getElementById("info").innerHTML += "range changed<br>";
        }

        // adjust start and end time.
        function setTime() {
            if (!timeline) return;

            //var newStartDate = new Date(document.getElementById('startDate').value);
            //var newEndDate   = new Date(document.getElementById('endDate').value);
            //timeline.setVisibleChartRange(newStartDate, newEndDate);
        }

        // set the visible range to the current time
        function setCurrentTime() {
            if (!timeline) return;

            timeline.setVisibleChartRangeNow();
            onrangechange();
        }

        // Format given date as "yyyy-mm-dd hh:ii:ss"
        // @param datetime   A Date object.
        function dateFormat(date) {
            var datetime =   date.getFullYear() + "-" +
                    ((date.getMonth()   <  9) ? "0" : "") + (date.getMonth() + 1) + "-" +
                    ((date.getDate()    < 10) ? "0" : "") +  date.getDate() + " " +
                    ((date.getHours()   < 10) ? "0" : "") +  date.getHours() + ":" +
                    ((date.getMinutes() < 10) ? "0" : "") +  date.getMinutes() + ":" +
                    ((date.getSeconds() < 10) ? "0" : "") +  date.getSeconds();
            return datetime;
        }
        
        function getSelectedRow() {
            var row = undefined;
            var sel = timeline.getSelection();
            if (sel.length) {
                if (sel[0].row != undefined) {
                    row = sel[0].row;
                }
            }
            return row;
        }

        // Called when the Visualization API is loaded.
        function drawVisualization() {
            // Create and populate a data table.
            data = new google.visualization.DataTable();
            data.addColumn('datetime', 'start');
            data.addColumn('datetime', 'end');
            data.addColumn('string', 'content');
            data.addColumn('string', 'className');

            data.addRows(fechaJson);

            // specify options
            var options = {
                width:  "100%",
                height: "auto",
                //height: "auto",
                editable: false,   // enable dragging and editing items
                axisOnTop: false,
                style: "box",
                locale: "es",
                box: { align: "left"},
                max: new Date(2015, 0, 1),
                min: new Date(2013, 0, 1),
                zoomMin: 1000 * 60 * 60 * 24 * 8,
                zoomMax: 1000 * 60 * 60 * 24 * 31 * 3,
                showNavigation: true,
                zoomable: false
            };

            // Instantiate our timeline object.
            timeline = new links.Timeline(document.getElementById('mytimeline'));

            // Add event listeners
            google.visualization.events.addListener(timeline, 'select', onselect);
            google.visualization.events.addListener(timeline, 'change', onchange);
            google.visualization.events.addListener(timeline, 'add', onadd);
            google.visualization.events.addListener(timeline, 'edit', onedit);
            google.visualization.events.addListener(timeline, 'delete', ondelete);
            google.visualization.events.addListener(timeline, 'rangechange', onrangechange);
            google.visualization.events.addListener(timeline, 'rangechanged', onrangechanged);

            // Draw our timeline with the created data and options
            timeline.draw(data, options);
            onrangechange();
        }