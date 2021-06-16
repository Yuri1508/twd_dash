<?php
    require 'connection/connect.php'
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./assets/css/bootstrap.min.css" rel="stylesheet">

        <title>Dashboard TWD</title>

        <style>
            * {
                overflow: hidden;
            }
        </style>
    </head>

    <body>
        <div class="row pt-5" style="justify-content: center;">
            <div class="col-md-3">
                <div class="container">
                    <div class="jumbotron">
                        <div class="card">
                            <label for="date"> Date Début : </label>
                            <input id="date" class="Dstart" name="date" type="date">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="container">
                    <div class="jumbotron">
                        <div class="card">
                            <label for="date"> Date Fin : </label>
                            <input id="date" class="Dend" name="date" type="date">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="jumbotron">
                <div id="columnchart_material" style="width: 800px; height: 500px;"></div>
            </div>
        </div>


        <script src="assets/js/jquery.js"></script>
        <script src="./assets/js/bootstrap.bundle.min.js" type="text/javascript"></script>
        <script src="./assets/js/loader.js" type="text/javascript"></script>


        <script>

            jQuery(document).ready(function($) {

                $('.Dstart').change(function(){
                    Dstart = $('.Dstart').val();
                    Dend = $('.Dend').val();

                    getAjax(Dstart,Dend);
                })

                $('.Dend').change(function(){
                    Dstart = $('.Dstart').val();
                    Dend = $('.Dend').val();

                    getAjax(Dstart,Dend);
                })
            })

            function getAjax(Dstart,Dend){
                $.ajax({
                    type: 'post',
                    url: "ajax.php",
                    data: {
                        'Dstart': Dstart,
                        'Dend': Dend
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert('succes');
                    }
                });
            }
        </script>

        <script type="text/javascript">
            google.charts.load("current", { packages: ["bar"] });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Jours", "Départ Tahiti", "Départ Moorea"],
                    ["Lundi", 200, 500],
                    ["Mardi", 1170, 460],
                    ["Mercredi", 660, 1120],
                    ["Jeudi", 1030, 540],
                    ["Vendredi", 1030, 540],
                    ["Samedi", 1030, 540],
                    ["Dimanche", 1030, 540],
                ]);

                var options = {
                    chart: {
                        title: "Dashboard TWD",
                        subtitle: "...",
                    },
                };

                var chart = new google.charts.Bar(document.getElementById("columnchart_material"));

                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
        </script>
    </body>
</html>