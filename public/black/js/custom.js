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
                            max: 1000,
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

        axios.get(`/api/users/calculate-points?id_user=${id_user}`)
            .then(response => {
                const levelColors = response.data.level_colors;
                const pointsToNextLevel = response.data.to_next_level;
                const pointsByLevel = response.data.points_by_level;
                const totalPoints = response.data.total_points;

                var index = 0;
                pointsByLevel.forEach((points, level) => {
                    datasets.push({
                        label: `Level ${level + 1}`,
                        backgroundColor: levelColors[index],
                        data: [points],
                    });
                    index++;
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

        $("#0").click(function () {
            var data = datasets.config.data;
            data.datasets[0].data = chart_data;
            data.labels = chart_labels;
            myChartData.update();
        });
        $("#1").click(function () {
            var chart_data = [80, 120, 105, 110, 95, 105, 90, 100, 80, 95, 70, 120];
            var data = myChartData.config.data;
            data.datasets[0].data = chart_data;
            data.labels = chart_labels;
            myChartData.update();
        });

        $("#2").click(function () {
            var chart_data = [60, 80, 65, 130, 80, 105, 90, 130, 70, 115, 60, 130];
            var data = myChartData.config.data;
            data.datasets[0].data = chart_data;
            data.labels = chart_labels;
            myChartData.update();
        });

        function getRandomColor() {
            const letters = "0123456789ABCDEF"; // Hexadecimal digits
            let color = "#";

            // Generate a random six-character color code
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }

            return color;
        }

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

            if (!checkbox.is(':checked')){
                checkbox.prop('checked', true).attr('checked', 'checked');
            }
            else {
                checkbox.prop('checked', false).removeAttr('checked');
            }
        });
    }
};

