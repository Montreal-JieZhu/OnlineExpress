<?php
//header("Content-Type:application/json");
//header("api-key:7YPRCFZhpdEfNSXWHCG5TyZwu6dGx9MwdK+r1R0FrUU");
//get origin information form the FORM
$countryFrom = $_POST['countryFrom'];
$provinceFrom = $_POST['provinceFrom'];  //'QC'; //
$cityFrom = $_POST['cityFrom'];
$streetFrom = $_POST['streetFrom'];
$postalCodeFrom = $_POST['postalCodeFrom'];

//get destination information form the FORM
$countryTo = $_POST['countryTo'];  //'CA'; //
$provinceTo = $_POST['provinceTo'];  //'QC'; //
$cityTo = $_POST['cityTo'];
$streetTo = $_POST['streetTo'];
$postalCodeTo = $_POST['postalCodeTo'];

//get weight information form the FORM
$weight = $_POST['weight'];
$weightUnit = $_POST['weightUnit'];

//get dimension information form the FORM
$length = $_POST['length'];
$width = $_POST['width'];
$height = $_POST['height'];
$dimensionUnit = $_POST['dimensionUnit'];

//set request JSON
$ship_to = array(
    'address_line1' => $streetTo,
    'city_locality' => $cityTo,
    'state_province' => $provinceTo,
    'postal_code' => $postalCodeTo,
    'country_code' => $countryTo
);

$ship_from = array(
    'name' => 'dummy name',
    'phone' => '514-123-4567',
    'address_line1' => $streetFrom,
    'city_locality' => $cityFrom,
    'state_province' => $provinceFrom,
    'postal_code' => $postalCodeFrom,
    'country_code' => $countryFrom
);

$packages = array(
    array(
        'weight' => array(
            'value' => $weight,
            'unit' => $weightUnit
        ),
        'dimensions' => array(
            'unit' => $dimensionUnit,
            'length' => $length,
            'width' => $width,
            'height' => $height
        )
    )
);

$shipment = array(
    'validate_address' => "no_validation",
    'ship_to' => $ship_to,
    'ship_from' => $ship_from,
    'packages' => $packages
);

$rate_options = array(
    'carrier_ids' => array('se-241902')
);

$request_json = json_encode(array(
    'shipment' => $shipment,
    'rate_options' => $rate_options
        ));


//API URL
$url = 'https://api.shipengine.com/v1/rates';

//create a new cURL resource
$ch = curl_init($url);

//attach encoded JSON string to the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);

//set the content type to application/json and api-key
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type:application/json',
    'api-key:7YPRCFZhpdEfNSXWHCG5TyZwu6dGx9MwdK+r1R0FrUU'
));

//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
$result = curl_exec($ch);

//close cURL resource
curl_close($ch);

//print_r($result);
//print_r($request_json);

$result_obj = json_decode($result);

$rates = $result_obj -> rate_response -> rates;


?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Shipping Fee Calculator</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../assets/vendor/linearicons/style.css">
        <!-- MAIN CSS -->
        <link rel="stylesheet" href="../assets/css/main.css">
        <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
        <link rel="stylesheet" href="../assets/css/demo.css">
        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
        <!-- ICONS -->
        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
        <link rel="icon" type="../image/png" sizes="96x96" href="../assets/img/favicon.png">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

        <style>
            .myContainer {
                margin-top: 120px;
            }
        </style>

    </head>
    <body>
        <!-- WRAPPER -->
        <div id="wrapper">
            <!-- NAVBAR -->
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="brand">
                    <a href="index.html">
                        <!--<img src="assets/img/logo-dark.png" alt="Klorofil Logo" class="img-responsive logo">-->
                        <label style="font-size:xx-large">Express</label>
                    </a>
                </div>
                <div class="container-fluid">
                    <div class="navbar-btn">
                        <button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
                    </div>
                    <form class="navbar-form navbar-left">
                        <div class="input-group">
                            <input type="text" value="" class="form-control" placeholder="Search dashboard...">
                                <span class="input-group-btn"><button type="button" class="btn btn-primary">Go</button></span>
                        </div>
                    </form>
                    <div class="navbar-btn navbar-btn-right">
                        <a class="btn btn-success update-pro" href="https://www.themeineed.com/downloads/klorofil-pro-bootstrap-admin-dashboard-template/?utm_source=klorofil&utm_medium=template&utm_campaign=KlorofilPro" title="Upgrade to Pro" target="_blank"><i class="fa fa-rocket"></i> <span>UPGRADE TO PRO</span></a>
                    </div>
                    <div id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                    <i class="lnr lnr-alarm"></i>
                                    <span class="badge bg-danger">5</span>
                                </a>
                                <ul class="dropdown-menu notifications">
                                    <li><a href="#" class="notification-item"><span class="dot bg-warning"></span>System space is almost full</a></li>
                                    <li><a href="#" class="notification-item"><span class="dot bg-danger"></span>You have 9 unfinished tasks</a></li>
                                    <li><a href="#" class="notification-item"><span class="dot bg-success"></span>Monthly report is available</a></li>
                                    <li><a href="#" class="notification-item"><span class="dot bg-warning"></span>Weekly meeting in 1 hour</a></li>
                                    <li><a href="#" class="notification-item"><span class="dot bg-success"></span>Your request has been approved</a></li>
                                    <li><a href="#" class="more">See all notifications</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="lnr lnr-question-circle"></i> <span>Help</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Basic Use</a></li>
                                    <li><a href="#">Working With Data</a></li>
                                    <li><a href="#">Security</a></li>
                                    <li><a href="#">Troubleshooting</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="../assets/img/user.png" class="img-circle" alt="Avatar"> <span>Jie</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#"><i class="lnr lnr-user"></i> <span>My Profile</span></a></li>
                                    <li><a href="#"><i class="lnr lnr-envelope"></i> <span>Message</span></a></li>
                                    <li><a href="#"><i class="lnr lnr-cog"></i> <span>Settings</span></a></li>
                                    <li><a href="#"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
                                </ul>
                            </li>
                            <!-- <li>
                                <a class="update-pro" href="https://www.themeineed.com/downloads/klorofil-pro-bootstrap-admin-dashboard-template/?utm_source=klorofil&utm_medium=template&utm_campaign=KlorofilPro" title="Upgrade to Pro" target="_blank"><i class="fa fa-rocket"></i> <span>UPGRADE TO PRO</span></a>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid myContainer">
                <!-- OVERVIEW -->
                <div class="panel panel-headline">
                    <div class="panel-body">
                        <div class="row">
                            
                                <div class="panel panel-headline">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Shipping Cost Calculator</h3>

                                    </div>

                                    <form>
                                        
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Selection</th>
                                                    <th>Service Type</th>
                                                    <th>Delivery Days</th>
                                                    <th>Estimated Delivery Date</th>                         
                                                    <th>Guaranteed Service</th>
                                                    <th>Trackable</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                foreach ($rates as $rate) {
                                                    //get estimated delivery date
                                                    if (isset($rate -> estimated_delivery_date)) {
                                                        $date=date_create($rate -> estimated_delivery_date);
                                                        $delivery_date = date_format($date,"Y/m/d  H:i:s");
                                                    } else {
                                                        $delivery_date = 'N/A';
                                                    }
                                                    
                                                    //get delivery days
                                                    if (isset($rate -> delivery_days)) {
                                                        $delivery_days = $rate -> delivery_days;
                                                    } else {
                                                        $delivery_days = 'N/A';
                                                    }
                                                    
                                                    //get garanteed serivce
                                                    if ($rate -> guaranteed_service == true) {
                                                        $checked_garanteed_service = "checked";
                                                    } else {
                                                        $checked_garanteed_service = "";
                                                    }                                      
                                             
                                                    // get trackable
                                                    if ($rate -> trackable == true) {
                                                        $checked_trackable = "checked";
                                                    } else {
                                                        $checked_trackable = "";
                                                    } 
                                                    
                                                ?>
                                                <tr>
                                                    <td>&emsp;&emsp13;<input type="radio" /></td>
                                                    <td><?= $rate -> service_type ?></td>
                                                    <td>&emsp;&emsp;<?= $delivery_days ?></td>
                                                    <td><?= $delivery_date ?></td>                           
                                                    <td>&emsp;&emsp;&emsp;&emsp;<input type="checkbox" disabled  <?= $checked_garanteed_service ?>></td>
                                                    <td>&emsp;&emsp;<input type="checkbox" disabled  <?= $checked_trackable ?>></td>
                                                    <td><?= $rate -> shipping_amount -> amount . ' ' . strtoupper($rate -> shipping_amount -> currency) ?></td>
                                                </tr>
                                                
                                                <?php
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </form>
                                    

                                </div>


                            
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- END MAIN -->
            <div class="clearfix"></div>
            <footer>
                <div class="container-fluid">
                    <p class="copyright">
                        Developed by Jing Wang, Jie Zhu, Anita
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>


