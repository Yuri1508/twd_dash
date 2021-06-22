<?php
require 'class/api.class.php';
require 'class/apidata.class.php';

$api = new twd();
$db_api = new Dbapi();

$ct_label = array();
$ft_label = array();
$cm_label = array();
$fm_label = array();
$c_tahiti = array();
$f_tahiti = array();
$c_moorea = array();
$f_moorea = array();

// count
$label_count_ct = 1;
$label_count_ft = 1;
$label_count_cm = 1;
$label_count_fm = 1;
$T = "T";
$M = "M";

if (isset($_POST['btn'])) {

    if ($_POST['btn'] == "récup") {

        $p_start = $_POST['day'];

        $db_api->truncate_travel();
        // get data from api
        $j_data = $api->getData($p_start, $p_start);

        //specific day
        foreach ($j_data as $date => $va) {

            foreach ($j_data[$date]["T"] as $stime => $value) {
                if (isset($value['CATAM'])) {
                    $percent = round($value['CATAM']['place_dispo']);
                    if ($percent < 0) {
                        $percent = 0;
                    }
                    // insert into db
                    $db_api->insertdatatravel($T, $value['CATAM']['code_societe'], $percent, $date);
                } else {
                    $percent = round($value['FERRY']['place_dispo']);
                    if ($percent < 0) {
                        $percent = 0;
                    }
                    // insert into db
                    $db_api->insertdatatravel($T, $value['FERRY']['code_societe'], $percent, $date);
                }
            }

            foreach ($j_data[$date]["M"] as $stime => $value) {
                if (isset($value['CATAM'])) {
                    $percent = round($value['CATAM']['place_dispo']);
                    if ($percent < 0) {
                        $percent = 0;
                    }
                    // insert into db
                    $db_api->insertdatatravel($M, $value['CATAM']['code_societe'], $percent, $date);
                } else {
                    $percent = round($value['FERRY']['place_dispo']);
                    if ($percent < 0) {
                        $percent = 0;
                    }
                    // insert into db
                    $db_api->insertdatatravel($M, $value['FERRY']['code_societe'], $percent, $date);
                }
            }
        }
    }
}

$list_data_ct = $db_api->getdata_ct();
$list_data_ft = $db_api->getdata_ft();
$list_data_cm = $db_api->getdata_cm();
$list_data_fm = $db_api->getdata_fm();

$total_ct = mysqli_num_rows($list_data_ct);
$total_ft = mysqli_num_rows($list_data_ft);
$total_cm = mysqli_num_rows($list_data_cm);
$total_fm = mysqli_num_rows($list_data_fm);

while ($row = mysqli_fetch_object($list_data_ct)) {

    $total_car_travel_day = 4 * $total_ct;

    $taux_final = $row->y / $total_car_travel_day;
    $taux_final = $taux_final * 100;
    $taux_final = (100 - $taux_final);
    $taux_final = round($taux_final);
    array_push($c_tahiti, array("y" => $taux_final));
    array_push($ct_label, "V" . $label_count_ct);
    $label_count_ct++;
}

while ($row = mysqli_fetch_object($list_data_ft)) {

    $total_car_travel_day = 86 * $total_ft;
    $taux_final = $row->y / $total_car_travel_day;
    $taux_final = $taux_final * 100;
    $taux_final = (100 - $taux_final);
    $taux_final = round($taux_final);
    array_push($f_tahiti, array("y" => $taux_final));
    array_push($ft_label, "V" . $label_count_ft);
    $label_count_ft++;
}



while ($row = mysqli_fetch_object($list_data_cm)) {

    $total_car_travel_day = 4 * $total_cm;
    $taux_final = $row->y / $total_car_travel_day;
    $taux_final = $taux_final * 100;
    $taux_final = (100 - $taux_final);
    $taux_final = round($taux_final);
    array_push($c_moorea, array("y" => $taux_final));
    array_push($cm_label, "V" . $label_count_cm);
    $label_count_cm++;
}

while ($row = mysqli_fetch_object($list_data_fm)) {

    $total_car_travel_day = 86 * $total_fm;
    $taux_final = $row->y / $total_car_travel_day;
    $taux_final = $taux_final * 100;
    $taux_final = (100 - $taux_final);
    $taux_final = round($taux_final);
    array_push($f_moorea, array("y" => $taux_final));
    array_push($fm_label, "V" . $label_count_fm);
    $label_count_fm++;
}
?>


<!-- #############################-----HTML-----############################# -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <title>Dashboard TWD</title>

    <style>
        .row {
            width: 100%;
        }
        .cardDash {
            padding: 20px;
            border-radius: 50px;
            box-shadow: 2px 3px 10px grey;
        }
    </style>
</head>

<body>


    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
            <div class="col-md-4">
                <div class="container">
                    <div class="jumbotron">
                        <div class="card mb-2">
                            <div class="form-group">
                                <label for="date"> Date de votre choix </label>
                                <input type="date" class="Dstart form-control" name="day" value="p_start" type="date">
                            </div>
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
            <!--Section: Demo-->
            <section class="border p-4 mb-4 d-flex justify-content-center">
                <div class="cardDash">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <canvas id="lineChartCatamTahiti" style="height: 400px; "></canvas>
                        </div>
                        <div class="col-lg-6">
                            <canvas id="lineChartFerryTahiti" style="height: 400px; "></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <script src="assets/js/jquery.js" type="text/javascript"></script>
    <script src="./assets/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="./assets/js/loader.js" type="text/javascript"></script>

    <script type="text/javascript" src="https://mdbcdn.b-cdn.net/wp-content/themes/mdbootstrap4/js/mdb5/3.8.0/compiled.min.js?ver=3.8.0-update.4" id="mdb-js2-js"></script>

    <!-- TAHITI -->
    <!-- CATAM -->
    <script type="text/javascript">
        var ctxL = document.getElementById("lineChartCatamTahiti").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($ct_label, JSON_NUMERIC_CHECK); ?>,
                datasets: [{
                        label: "CATAM Tahiti",
                        data: <?php echo json_encode($c_tahiti, JSON_NUMERIC_CHECK); ?>,
                        backgroundColor: [
                            'rgba(0, 0, 0, 0)',
                        ],
                        borderColor: [
                            '#DAA520',
                        ],
                        borderWidth: 4
                    },
                    {
                        label: "CATAM Moorea",
                        data: <?php echo json_encode($c_moorea, JSON_NUMERIC_CHECK); ?>,
                        backgroundColor: [
                            'rgba(0, 0, 0, 0)',
                        ],
                        borderColor: [
                            '#9400D3',
                        ],
                        borderWidth: 4
                    }
                ]
            },
            options: {
                responsive: true,

            }
        });
    </script>

    <!-- TAHITI -->
    <!-- FERRY -->
    <script type="text/javascript">
        var ctxL = document.getElementById("lineChartFerryTahiti").getContext('2d');
        var myLineChart = new Chart(ctxL, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($ft_label, JSON_NUMERIC_CHECK); ?>,
                datasets: [{
                        label: "FERRY Tahiti",
                        data: <?php echo json_encode($f_tahiti, JSON_NUMERIC_CHECK); ?>,
                        backgroundColor: [
                            'rgba(0, 0, 0, 0)',
                        ],
                        borderColor: [
                            '#008000',
                        ],
                        borderWidth: 4
                    },
                    {
                        label: "FERRY Moorea",
                        data: <?php echo json_encode($f_moorea, JSON_NUMERIC_CHECK); ?>,
                        backgroundColor: [
                            'rgba(0, 0, 0, 0)',
                        ],
                        borderColor: [
                            '#0000FF',
                        ],
                        borderWidth: 4
                    }
                ]
            },
            options: {
                responsive: true,
            }
        });
    </script>
</body>

</html>