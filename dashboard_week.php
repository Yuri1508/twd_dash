<?php
    require 'class/api.class.php';
    require 'class/apidata.class.php';


    $api = new twd();
    $db_api = new Dbapi();

    $p_start = $_POST['start_day'];
    $p_end = $_POST['end_day'];

    // get data from api
    $j_data = $api->getData($p_start, $p_end);

    $tahiti = array();
    $label = array();
    $moorea = array();
    $count_bdd = 0;
    $label_count = 1;
    $count_moz = 0;

    if(isset($_POST['btn'])){
        
        if($_POST['btn'] == "récup"){

            $db_api->truncate();
            
            foreach($j_data[$p_start]["T"] as $stime => $value){
            
                if(isset($value['CATAM'])){

                    if ($value['CATAM']['place_dispo'] == 0) {
                        $percent = 100;
                        // insert into db
                        $db_api->insertdata($value['CATAM']['code_societe'],$percent,$count_bdd);
                        $count_bdd++;
                    }else{
                        // insert into db
                        $percent = round($value['CATAM']['place_dispo']);
                        $db_api->insertdata($value['CATAM']['code_societe'],$percent,$count_bdd);
                        $count_bdd++;
                    }   
                }
            }
        }
    }

    $list_db_api = $db_api->getdata();

    while ($row = mysqli_fetch_object($list_db_api)) {
        if ($row->code_societe == "CATAM") {
            array_push($tahiti, array("label"=> $row->label, "y"=> $row->y));
        }
    }
    
    foreach($j_data[$p_start]["T"] as $stime => $value){
        if ($value['CATAM']) {
        array_push($label, 'V'.$label_count);
        }

        $label_count++;
    }

    

    if(isset($_POST['btn'])){
        
        if($_POST['btn'] == "récup"){

            $db_api->truncate();
            
            foreach($j_data[$p_start]["M"] as $stime => $value){
            
                if(isset($value['CATAM'])){

                    // var_dump($value['CATAM']['place_dispo']);
                    if ($value['CATAM']['place_dispo'] == 0) {
                        $percent = 100;
                        // insert into db
                        $db_api->insertdata($value['CATAM']['code_societe'],$percent,$count_moz);
                        $count_moz++;
                    }else{
                        // insert into db
                        $percent = round($value['CATAM']['place_dispo']);
                        $db_api->insertdata($value['CATAM']['code_societe'],$percent,$count_moz);
                        $count_moz++;
                    }   
                }
            }
        }
    }

    $list_db_api = $db_api->getdata();

    while ($row = mysqli_fetch_object($list_db_api)) {
        if ($row->code_societe == "CATAM") {
            array_push($moorea, array("label"=> $row->label, "y"=> $row->y));
            // array_push($label,$row->label);
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

    </head>

    <body>
        <form action="" method="post">
            <div class="row pt-5" style="justify-content: center;">
                <div class="col-md-3">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card">
                                <label for="date"> Date Début : </label>
                                <input id="date" class="Dstart" name="start_day" type="date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card">
                                <label for="date"> Date Fin : </label>
                                <input id="date" class="Dend" name="end_day" type="date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" name="btn" value="récup">valider</button>
        </form>

        <div class="container">
            <div class="jumbotron">
                <!--Section: Demo-->
                <section class="border p-4 mb-4 d-flex justify-content-center">
                    <div class="col-lg-7">
                        <div id="chart-bar-double-datasets-example" class="chart">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas width="564" height="282" style="display: block; width: 564px; height: 282px;" class="chartjs-render-monitor"></canvas>
                        </div>
                    </div>
                </section>
                <!--Section: Demo-->
            </div>
        </div>


        <script src="assets/js/jquery.js"></script>
        <script src="./assets/js/bootstrap.bundle.min.js" type="text/javascript"></script>
        <script src="./assets/js/loader.js" type="text/javascript"></script>

        <script type="text/javascript"
        src="https://mdbcdn.b-cdn.net/wp-content/themes/mdbootstrap4/js/mdb5/3.8.0/compiled.min.js?ver=3.8.0-update.4"
        id="mdb-js2-js"></script>

        <!-- Chart bar double datasets example -->
        <script>
            // Data
            const dataChartBarDoubleDatasetsExample = {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($label, JSON_NUMERIC_CHECK); ?>,
                    
                    datasets: [
                        {
                            label: 'Départ Tahiti',
                            data: <?php echo json_encode($tahiti, JSON_NUMERIC_CHECK); ?>,
                            backgroundColor: '#DAAB3A',
                            borderColor: 'aliceblue',
                        },
                        {
                            label: 'Départ Moorea',
                            data: <?php echo json_encode($moorea, JSON_NUMERIC_CHECK); ?>,
                            backgroundColor: '#137C8B',
                            borderColor: 'aliceblue',
                        },
                    ],
                },
            };

            // Options
            const optionsChartBarDoubleDatasetsExample = {
                options: {
                    scales: {
                        yAxes: [
                            {
                                stacked: false,
                                ticks: {
                                    beginAtZero: true,
                                },
                            },
                        ],
                        xAxes: [
                            {
                                stacked: false,
                            },
                        ],
                    },
                },
            };

            new mdb.Chart(
                document.getElementById('chart-bar-double-datasets-example'),
                dataChartBarDoubleDatasetsExample,
                optionsChartBarDoubleDatasetsExample
            );
        </script>
    </body>
</html>