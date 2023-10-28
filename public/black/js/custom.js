type = ['primary', 'info', 'success', 'warning', 'danger'];

demo = {
    initPickColor: function () {
        $('.pick-class-label').click(function () {
            var new_class = $(this).attr('new-class');
            var old_class = $('#display-buttons').attr('data-class');
            var display_div = $('#display-buttons');
            if (display_div.length) {
                var display_buttons = display_div.find('.btn');
                display_buttons.removeClass(old_class);
                display_buttons.addClass(new_class);
                display_div.attr('data-class', new_class);
            }
        });
    },

    initDashboardPageCharts: function () {
        var ctx = document.getElementById("horizontalStackedBarChart").getContext("2d");

        var options = {
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        stacked: true,
                        ticks: {
                            min: 0,
                            stepSize: 100,
                            max: 1500,
                        },
                    },
                ],
                yAxes: [
                    {
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false
                        },
                        stacked: true,
                    },
                ],
            },
            layout: {
                padding: {
                    top: 0,
                    bottom: 0,
                    left: 10,
                },
            },
            legend: {
                display: false, // Hide the dataset labels
            },
        };

        var datasets = [];

        axios.get(`/api/freshman/get-points?id_user=${id_user}`)
            .then(response => {
                const allLevels = response.data.all_levels;
                const freshman_level = response.data.level;
                const left_points = response.data.left_points;

                var old_points = 0;
                allLevels.forEach(levelData => {
                    const level = levelData.id;
                    const points = levelData.points;

                    if (level <= freshman_level) {
                        datasets.push({
                            label: `Level ${level}`,
                            backgroundColor: '#e14eca',
                            data: [points - old_points],
                        });
                        old_points = points;
                    }
                });

                datasets.push({
                    label: `Points without level`,
                    backgroundColor: '#6c757d',
                    data: [left_points],
                });

                // Create the chart inside this block
                var ctx = document.getElementById("horizontalStackedBarChart").getContext("2d");
                var horizontalStackedBarChart = new Chart(ctx, {
                    type: "horizontalBar",
                    data: {
                        datasets: datasets,
                    },
                    options: options,
                });
            })
            .catch(error => {
                console.error('Error fetching user points:', error);
            });

    },

    showNotification: function (from, align) {
        color = Math.floor((Math.random() * 4) + 1);

        $.notify({
            icon: "tim-icons icon-bell-55",
            message: "Welcome to <b>Black Dashboard</b> - a beautiful freebie for every web developer."

        }, {
            type: type[color],
            timer: 8000,
            placement: {
                from: from,
                align: align
            }
        });
    },
};

custom = {
    initMultipleSelection: function () {
        $('body').unbind('click').bind('click').on('click', '[id^="role-checkbox-"], [id^="freshman-checkbox-"]', function () {

            const checkbox = $(this).find('input[type="checkbox"]');

            if (!checkbox.is(':checked')) {
                checkbox.prop('checked', true).attr('checked', 'checked');
                if ($(this).is('#freshman-checkbox-all')) {
                    $('[id^="freshman-checkbox-"] input[type="checkbox"]').prop('checked', true);
                }
            } else {
                checkbox.prop('checked', false).removeAttr('checked');
                if ($(this).is('#freshman-checkbox-all')) {
                    $('[id^="freshman-checkbox-"] input[type="checkbox"]').prop('checked', false);
                } else {
                    $('[id^="freshman-checkbox-all"] input[type="checkbox"]').prop('checked', false);
                }
            }
        });
    },

    initTaskType: function () {
        togglePointsInput();

        $('select[name="type"]').on('change', function () {
            togglePointsInput();
        });

        function togglePointsInput() {
            var selectedType = $('select[name="type"]').val();
            if (selectedType == window.appConfig.id_quest) {
                $('#points').hide();
                $('#description').hide();
                $('#date_from').hide();
                $('#date_to').hide();
                $('#active').hide();
            } else {
                $('#points').show();
                $('#description').show();
                $('#date_from').show();
                $('#date_to').show();
                $('#active').show();
            }
        }
    },

    selectTask: function () {
        $('.dropdown-item[data-target="#assign"]').click(function () {
            var taskValue = $(this).data('task');
            var $select = $('select[name="task"]');

            $select.val(taskValue);
            $select.find('option:not(:selected)').prop('disabled', true);

            axios.get(`/api/task/get-assigned?id_task=${taskValue}`)
                .then(response => {
                    const assigned = response.data.assigned;
                    const all = response.data.all;

                    assigned.forEach(freshman => {
                        $('input[type="checkbox"][name="freshman[]"][value="' + freshman.id_user + '"]').prop('checked', true);
                    });

                    if (all) {
                        $('input[type="checkbox"][name="freshman[]"][value="all"]').prop('checked', true);
                    }
                })
                .catch(error => {
                    console.error('Error fetching task assigned freshmen:', error);
                });
        });
    },

    finishTask: function () {
        var previousState = {};

        $('.task-checkbox').change(function () {
            var isChecked = $(this).is(':checked');
            var id_user_point = $(this).val();

                var confirmationMessage = isChecked
                    ? 'Are you sure to finish this task?'
                    : 'Are you sure to mark this task unfinished?';

                Swal.fire({
                    title: confirmationMessage,
                    showDenyButton: true,
                    confirmButtonText: 'Yes',
                    denyButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) {

                        axios.post('/api/freshman/finish-task', {
                            id_user_point: id_user_point,
                            finished: isChecked
                        })
                            .then(function (response) {
                                console.log(response.data);
                            })
                            .catch(function (error) {
                                console.error(error);
                            });
                    } else if (result.isDenied) {
                        $(this).prop('checked', !isChecked);
                    }
                })
    });
    },

    dropdownItemShow: function () {
        if (window.screen.width < 769) {
            $(document).on('shown.bs.dropdown', '.dropdown', function () {
                var height = $(this).find('.dropdown-menu').height();
                $(this).closest('.table').css('margin-bottom', height + 'px');
            });

            $(document).on('hidden.bs.dropdown', '.dropdown', function () {
                $(this).closest('.table').css('margin-bottom', '');
            });
        }
    },

    confirmFrom: function () {
        $('.confirm-form').on('click', function() {
            event.preventDefault();
            var confirmationMessage = $(this).data('confirm');

            Swal.fire({
                title: confirmationMessage,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Yes!',
                confirmButtonColor: '#32CD32',
                denyButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            })
        });
    },

    hideShowTableRows: function () {
        $('.show-more').click(function() {
            var tableId = $(this).data('toggle');
            var $table = $('#table-' + tableId);

            var $hiddenRows = $table.find('.hide-row');
            var $showMore = $table.find('.show-more-row');
            var $showLess = $table.find('.show-less-row');

            $hiddenRows.css('display', 'table-row');
            $showMore.css('display', 'none');
            $showLess.css('display', 'table-row');
        });

        $('.show-less').click(function() {
            var tableId = $(this).data('toggle');
            var $table = $('#table-' + tableId);

            var $hiddenRows = $table.find('.hide-row');
            var $showMore = $table.find('.show-more-row');
            var $showLess = $table.find('.show-less-row');

            $hiddenRows.css('display', 'none');
            $showMore.css('display', 'table-row');
            $showLess.css('display', 'none');
        });
    },

    hideTasksByType: function () {
        $('tr[data-id-type]').hide();

        $('tr.tasks-type').off().click(function() {
            var typeId = $(this).attr('id').replace('tasks-', ''); // Extract the id
            var $targetRows = $('tr[data-id-type="' + typeId + '"]');
            var $showMoreBtn = $(this).find('.show-more');
            var $showLessBtn = $(this).find('.show-less');

            // Toggle visibility of the target rows
            $targetRows.toggle();
            $showMoreBtn.toggle();
            $showLessBtn.toggle();
        });
    },

    initCalendar: function ($events) {

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });

        var calendar = $('#calendar').fullCalendar({
            editable:true,
            header:{
                left:'prev,next today',
                center:'title',
                right:'month,agendaWeek,agendaDay'
            },
            events:$events,
            selectable:true,
            selectHelper: true,
            firstDay: 1,
            select:function(start, end, allDay)
            {
                var title = prompt('Event Title:');

                if(title)
                {
                    var start = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');

                    var end = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

                    axios.post('/api/calender/action', {
                        title: title,
                        start: start,
                        end: end,
                        type: 'add'
                    })
                        .then(function (response) {

                            alert("Event Created Successfully");
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                }
            },
            editable:true,
            eventResize: function(event, delta)
            {
                var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
                var title = event.title;
                var id = event.id;
                axios.post('/api/calender/action', {
                    title: title,
                    start: start,
                    end: end,
                    id: id,
                    type: 'update'
                })
                    .then(function (response) {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Updated Successfully");
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            },
            eventDrop: function(event, delta)
            {
                var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
                var title = event.title;
                var id = event.id;
                axios.post('/api/calender/action', {
                    title: title,
                    start: start,
                    end: end,
                    id: id,
                    type: 'update'
                })
                    .then(function (response) {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Updated Successfully");
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            },

            eventClick:function(event)
            {
                if(confirm("Are you sure you want to remove it?"))
                {
                    var id = event.id;
                    axios.post('/api/calender/action', {
                        id: id,
                        type: "delete"
                    })
                        .then(function (response) {
                            calendar.fullCalendar('refetchEvents');
                            alert("Event Deleted Successfully");
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                }
            }
        });

    }
};

