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
        $('body').unbind('click').bind('click').on('click', '[id^="role-checkbox-"]', function () {

            const checkbox = $(this).find('input[type="checkbox"]');

            if (!checkbox.is(':checked')) {
                checkbox.prop('checked', true).attr('checked', 'checked');
            } else {
                checkbox.prop('checked', false).removeAttr('checked');
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
            $('select[name="task"]').val(taskValue);
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
};

