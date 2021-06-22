<?php
require 'class/api.class.php';
require 'class/apidata.class.php';

$api = new twd();
$db_api = new Dbapi();

$label = array();
$t_week = array();
$c_tahiti = array();
$f_tahiti = array();
$c_moorea = array();
$f_moorea = array();

// count
$label_count = 1;

//Select Island
$T = "T";
$M = "M";

// if boutton click
if (isset($_POST['btn'])) {

    if ($_POST['btn'] == "récup") {

        // period of day
        $p_start = $_POST['start_day'];
        $p_end = $_POST['end_day'];

        // specific day
        $p_day = $_POST['day'];

        // delete all data from twd table
        $db_api->truncate();

        // get data from api
        $j_data = $api->getData($p_start, $p_end);

        //specific day
        $period = floor((strtotime($p_end) - strtotime($p_start)) / (24 * 60 * 60));

        // loop for specific date
        for ($i = 0; $i < $period; $i++) {
            if (in_array(date('l', strtotime("$p_start +$i day")), [$p_day])) {

                //specific date on period selected
                $specific_date = date('Y-m-d', strtotime("$p_start +$i day"));

                //$specific_date to Date() format 
                $date_format = new DateTime($specific_date);

                // format date to natural language
                $label_date = $date_format->format('l j M');

                // initialize label of graphique
                array_push($label, $label_date);

                // format date to day natural language
                $week = $date_format->format('l');

                // initialize array of weeks
                // use to verify if is the week or not
                array_push($t_week, $week);

                // loop to list of data api
                foreach ($j_data as $date => $va) {

                    if ($specific_date == $date) {


                        foreach ($j_data[$date]["T"] as $stime => $value) {

                            if (isset($value['CATAM'])) {

                                $percent = round($value['CATAM']['place_dispo']);
                                if ($percent < 0) {
                                    $percent = 0;
                                }

                                // insert into db
                                $db_api->insertdata($T, $value['CATAM']['code_societe'], $percent, $date);
                            } else {

                                $percent = round($value['FERRY']['place_dispo']);
                                if ($percent < 0) {
                                    $percent = 0;
                                }

                                // insert into db
                                $db_api->insertdata($T, $value['FERRY']['code_societe'], $percent, $date);
                            }
                        }

                        foreach ($j_data[$date]["M"] as $stime => $value) {
                            if (isset($value['CATAM'])) {
                                $percent = round($value['CATAM']['place_dispo']);
                                if ($percent < 0) {
                                    $percent = 0;
                                }

                                // insert into db
                                $db_api->insertdata($M, $value['CATAM']['code_societe'], $percent, $date);
                            } else {
                                $percent = round($value['FERRY']['place_dispo']);
                                if ($percent < 0) {
                                    $percent = 0;
                                }

                                // insert into db
                                $db_api->insertdata($M, $value['FERRY']['code_societe'], $percent, $date);
                            }
                        }
                    }
                }
            }
        }
    }
}


$list_db_api = $db_api->getdata();

while ($row = mysqli_fetch_object($list_db_api)) {

    // selection par ile
    if ($row->ile == "T") {

        // selection par type bateau
        if ($row->code_societe == "CATAM") {
            $total_date = $row->count_date;
            $total_car_travel_day = 4 * $total_date;
            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);
            $taux_final = round($taux_final);
            array_push($c_tahiti, array("y" => $taux_final));
        } else {
            $total_date = $row->count_date;

            // selection taux max selon week || semaine
            if (in_array('Friday', $t_week) || in_array('Saturday', $t_week) || in_array('Sunday', $t_week)) {

                $total_car_travel_day = 133 * $total_date;
            } else {

                $total_car_travel_day = 86 * $total_date;
            }

            // calcul taux en %
            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);

            // arrondir
            $taux_final = round($taux_final);

            // alimenté le tableaux f_tahiti
            array_push($f_tahiti, array("y" => $taux_final));
        }

        // sinon Moorea
    } else {

        if ($row->code_societe == "CATAM") {
            $total_date = $row->count_date;
            $total_car_travel_day = 4 * $total_date;
            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);
            $taux_final = round($taux_final);
            array_push($c_moorea, array("y" => $taux_final));
        } else {
            $total_date = $row->count_date;

            if (in_array('Friday', $t_week) || in_array('Saturday', $t_week) || in_array('Sunday', $t_week)) {
                $total_car_travel_day = 133 * $total_date;
            } else {
                $total_car_travel_day = 86 * $total_date;
            }

            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);
            $taux_final = round($taux_final);

            array_push($f_moorea, array("y" => $taux_final));
        }
    }
}
?>


<!-- #############################-----HTML-----############################# -->

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./assets/css/bootstrap.min.css" rel="stylesheet">

        <title>Dashboard TWD</title>

        <style>
            .row {
                width: 100%;
            }
        </style>

    </head>

    <body>

        <!-- navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Dashboard TWD</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="dashboard_travel.php">Journalier</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard_week.php">Période</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- formulaire -->
        <form action="" method="post">
            <div class="row pt-5" style="justify-content: center;">

                <!-- date de debut -->
                <div class="col-md-2">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card">
                                <label for="date"> Date Début : </label>
                                <input id="date" class="Dstart" name="start_day" type="date">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- date de fin -->
                <div class="col-md-2">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card">
                                <label for="date"> Date Fin : </label>
                                <input id="date" class="Dend" name="end_day" type="date">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- selection du jour -->
                <div class="col-md-2">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card">
                                <label for="date"> Jours </label>
                                <select name="day" class="form-control">
                                    <option value="Monday">
                                        lundi
                                    </option>
                                    <option value="Tuesday">
                                        mardi
                                    </option>
                                    <option value="Wednesday">
                                        mercredi
                                    </option>
                                    <option value="Thursday">
                                        jeudi
                                    </option>
                                    <option value="Friday">
                                        vendredi
                                    </option>
                                    <option value="Saturday">
                                        samedi
                                    </option>
                                    <option value="Sunday">
                                        dimanche
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- boutton -->
                <div class="col-md-2">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card">
                                <button type="submit" name="btn" class="btn-outline-primary form-control" value="récup">VALID</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="container">
            <div class="jumbotron">
                <section class="border  d-flex justify-content-center">
                    <div class="col-lg-7">
                        <div id="chart-bar" style="height: 100%;" class="chart"></div>
                    </div>
                </section>
            </div>
        </div>


        <script src="assets/js/jquery.js" type="text/javascript"></script>
        <script src="assets/js/bootstrap.bundle.min.js" type="text/javascript"></script>
        <script src="assets/js/loader.js" type="text/javascript"></script>

        <script type="text/javascript" src="https://mdbcdn.b-cdn.net/wp-content/themes/mdbootstrap4/js/mdb5/3.8.0/compiled.min.js?ver=3.8.0-update.4" id="mdb-js2-js"></script>

        <!-- Chart bar double datasets example -->
        <script type="text/javascript">
            // Data
            const dataChartBarDoubleDatasetsExample = {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($label, JSON_NUMERIC_CHECK); ?>,

                    datasets: [{
                            label: 'Catam Tahiti',
                            data: <?php echo json_encode($c_tahiti, JSON_NUMERIC_CHECK); ?>,
                            backgroundColor: '#521BFA',
                            borderColor: 'aliceblue',
                        },
                        {
                            label: 'Ferry Tahiti',
                            data: <?php echo json_encode($f_tahiti, JSON_NUMERIC_CHECK); ?>,
                            backgroundColor: '#F0ED51',
                            borderColor: 'aliceblue',
                        },
                        {
                            label: 'Catam Moorea',
                            data: <?php echo json_encode($c_moorea, JSON_NUMERIC_CHECK); ?>,
                            backgroundColor: '#55FA77',
                            borderColor: 'aliceblue',
                        },
                        {
                            label: 'Ferry Moorea',
                            data: <?php echo json_encode($f_moorea, JSON_NUMERIC_CHECK); ?>,
                            backgroundColor: '#58C0E3',
                            borderColor: 'aliceblue',
                        }
                    ],
                },
            };

            // Options
            const optionsChartBarDoubleDatasetsExample = {
                options: {
                    scales: {
                        yAxes: [{
                            stacked: false,
                            ticks: {
                                beginAtZero: true,
                            },
                        }, ],
                        xAxes: [{
                            stacked: false,
                        }, ],
                    }
                },
            };

            new mdb.Chart(
                document.getElementById('chart-bar'), dataChartBarDoubleDatasetsExample, optionsChartBarDoubleDatasetsExample);
        </script>

    </body>
</html>