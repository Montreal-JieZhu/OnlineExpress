﻿<?php
//https://api.chucknorris.io/
//base url of our web service
$base_url = "http://locationrestfull.azurewebsites.net/api/countries";

//function that handles all the curl requests
function fetch_curl($url) {
    $ch = curl_init($url); //initialize the fetch command
    //prevent automatic output to screen
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // in case of MAMP issues
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $results = curl_exec($ch); //execute the fetch command
    curl_close($ch); //close curl request
    //decode JSON that is returned
    $data = json_decode($results);
    return $data;
}

$countries = fetch_curl($base_url);
?>
<!doctype html>
<html lang="en">

    <head>
        <title>Online Super Express</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <!-- VENDOR CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/vendor/linearicons/style.css">
        <!-- MAIN CSS -->
        <link rel="stylesheet" href="assets/css/main.css">
        <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
        <link rel="stylesheet" href="assets/css/demo.css">
        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
        <!-- ICONS -->
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
       <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?setLang=en&key=AuqsNVXfKfPx5B6juGoyi9rYuEZkIkYns-8GRbMbrx3BnhxpT5KsRNrRUgbyOpsm' async defer></script>
        <script type='text/javascript'>
            var map;
            var center;

            var SET_SHIP_FROM = 0;
            var SET_SHIP_TO = 1;
            var SET_NOTHING = 2;

            var setFlag = SET_SHIP_FROM;
            function loadMapScenario(position) {
                if (position != null) {
                    center = new Microsoft.Maps.Location(position.coords.latitude, position.coords.longitude);
                    map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
                        /* No need to set credentials if already passed in URL */
                        center: center
                    });
                    convertGeoToAddress(center);
                } else {
                    map = new Microsoft.Maps.Map(document.getElementById('myMap'), {});
                }

                Microsoft.Maps.Events.addHandler(map, 'click', getLatlng);

            }

            function getLatlng(e) {
                if (e.targetType == "map" && setFlag != SET_NOTHING) {
                    var point = new Microsoft.Maps.Point(e.getX(), e.getY());
                    var locTemp = e.target.tryPixelToLocation(point);
                    var location = new Microsoft.Maps.Location(locTemp.latitude, locTemp.longitude);
                    convertGeoToAddress(location);
                }
            }

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(loadMapScenario, showError);
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }
            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }
                loadMapScenario(null);
            }

            function fillShipAddress(address) {
                var countryISO = address.countryRegionISO2;
                var country = address.countryRegion;
                var postalCode = address.postalCode;
                //var city = address.locality;
                var city = address.district;
                var street = address.addressLine;
                var province = address.adminDistrict;
                switch (setFlag) {
                    case SET_SHIP_FROM:
					
						$("#countryFrom").children().each(function (index, element) {
                            if ($(element).attr("selected") != undefined && $(element).text().indexOf(country) == -1) {
                                $(element).removeAttr("selected");
                            }
                            if ($(element).text().indexOf(country) > -1) {
                                $(element).attr("selected", "selected");
                                $(element).prependTo("#countryFrom");
                            }
                        });
                        $("#cityFrom").val(city);
                        $("#postalCodeFrom").val(postalCode);
                        $("#streetFrom").val(street);
                        $("#provinceFrom").val(province);
                        $("#btnSetFrom").html("Set Ship From");
					
                        break;
                    case SET_SHIP_TO:
                        $("#countryTo").children().each(function (index, element) {
                            if ($(element).attr("selected") != undefined && $(element).text().indexOf(country) == -1) {
                                $(element).removeAttr("selected");
                            }
                            if ($(element).text().indexOf(country) > -1) {
                                $(element).attr("selected", "selected");
                                $(element).prependTo("#countryTo");
                            }
                            //alert($(element).text())
                        });
                        $("#cityTo").val(city);
                        $("#postalCodeTo").val(postalCode);
                        $("#streetTo").val(street);
                        $("#provinceTo").val(province);
                        $("#btnSetTo").html("Set Ship To");
                        break;
                }
                setFlag = SET_NOTHING;
            }

            function deletePushpin(who) {
                for (var i = map.entities.getLength() - 1; i >= 0; i--) {
                    var pushpin = map.entities.get(i);
                    if (pushpin instanceof Microsoft.Maps.Pushpin && pushpin.getText().indexOf(who) > -1) {
                        map.entities.removeAt(i);
                    }
                }
            }

            function convertGeoToAddress(location) {
                Microsoft.Maps.loadModule('Microsoft.Maps.Search', function () {
                    var searchManager = new Microsoft.Maps.Search.SearchManager(map);
                    var reverseGeocodeRequestOptions = {
                        location: location,
                        callback: function (answer, userData) {
                            map.setView({bounds: answer.bestView});
                            if (setFlag == SET_SHIP_FROM) {
                                deletePushpin("S");
                                map.entities.push(new Microsoft.Maps.Pushpin(reverseGeocodeRequestOptions.location, {
                                    icon: 'https://bingmapsisdk.blob.core.windows.net/isdksamples/defaultPushpin.png',
                                    anchor: new Microsoft.Maps.Point(12, 39),
                                    text: 'S'
                                }));
                            } else if (setFlag == SET_SHIP_TO) {
                                deletePushpin("E");
                                map.entities.push(new Microsoft.Maps.Pushpin(reverseGeocodeRequestOptions.location, {
                                    icon: 'https://www.bingmapsportal.com/Content/images/poi_custom.png',
                                    anchor: new Microsoft.Maps.Point(12, 39),
                                    text: 'E'
                                }));
                            }
                            //alert(answer.address.formattedAddress);
                            fillShipAddress(answer.address);
                        }
                    };
                    searchManager.reverseGeocode(reverseGeocodeRequestOptions);
                });
            }

        </script>
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
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="assets/img/user.png" class="img-circle" alt="Avatar"> <span>Jie</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
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
                            <div class="col-md-7" id='myMap' style='height: 80vh;'></div>

                            <div class="col-md-5">
                                <form action="shipment/rateCaculator.php" method="POST">
                                    <div class="panel panel-headline">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Step1: Origin/Destination Information</h3>
                                            <p>All Fields Are Required</p>
                                        </div>
                                        <div class="panel-body row">
                                            <div class="col-md-6">
                                                <button type="button" id="btnSetFrom" class="btn btn-primary btn-sm">Set Ship From</button>
                                                <fieldset id="fromFileds">
                                                    <div class="form-group">
                                                        <label for="countryFrom">Country:</label>

                                                        <select class="form-control" id="countryFrom" name="countryFrom">
                                                            <option value="CA">Canada</option>
                                                        </select>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="provinceFrom">Province:</label>
                                                        <input required type="text" class="form-control" id="provinceFrom" name="provinceFrom">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cityFrom">City:</label>
                                                        <input required type="text" class="form-control" id="cityFrom" name="cityFrom">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="streetFrom">Street:</label>
                                                        <input required type="text" class="form-control" id="streetFrom" name="streetFrom">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="postalCodeFrom">Postal Code:</label>
                                                        <input required type="text" class="form-control" id="postalCodeFrom" name="postalCodeFrom">
                                                    </div>

                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" id="btnSetTo" class="btn btn-primary btn-sm">Set Ship To</button>
                                                <fieldset id="toFileds">
                                                    <div class="form-group">
                                                        <label for="countryTo">Country:</label>
                                                        <?php if (isset($countries)) { ?>
                                                            <select class="form-control" id="countryTo" name="countryTo">
                                                                <?php foreach ($countries as $country) { ?>
                                                                    <option value="<?= $country->code; ?>"><?= $country->name; ?></option>
                                                                <?php }//endloop categories dropdown ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <select class="form-control" id="countryTo" name="countryTo">
                                                                <option value="AF">Afghanistan</option>
                                                                <option value="AX">Aland Islands</option>
                                                                <option value="AL">Albania</option>
                                                                <option value="DZ">Algeria*</option>
                                                                <option value="AS">American Samoa</option>
                                                                <option value="AD">Andorra*</option>
                                                                <option value="AO">Angola</option>
                                                                <option value="AI">Anguilla</option>
                                                                <option value="AG">Antigua &amp; Barbuda</option>
                                                                <option value="AR">Argentina*</option>
                                                                <option value="AM">Armenia*</option>
                                                                <option value="AW">Aruba</option>
                                                                <option value="AU">Australia*</option>
                                                                <option value="AT">Austria*</option>
                                                                <option value="AZ">Azerbaijan*</option>
                                                                <option value="A2">Azores*</option>
                                                                <option value="BS">Bahamas</option>
                                                                <option value="BH">Bahrain</option>
                                                                <option value="BD">Bangladesh*</option>
                                                                <option value="BB">Barbados</option>
                                                                <option value="BY">Belarus*</option>
                                                                <option value="BE">Belgium*</option>
                                                                <option value="BZ">Belize</option>
                                                                <option value="BJ">Benin</option>
                                                                <option value="BM">Bermuda</option>
                                                                <option value="BT">Bhutan</option>
                                                                <option value="BO">Bolivia</option>
                                                                <option value="BL">Bonaire</option>
                                                                <option value="BA">Bosnia*</option>
                                                                <option value="BW">Botswana</option>
                                                                <option value="BR">Brazil*</option>
                                                                <option value="VG">British Virgin Isles</option>
                                                                <option value="BN">Brunei</option>
                                                                <option value="BG">Bulgaria*</option>
                                                                <option value="BF">Burkina Faso</option>
                                                                <option value="BI">Burundi</option>
                                                                <option value="KH">Cambodia</option>
                                                                <option value="CM">Cameroon</option>
                                                                <option value="CA">Canada*</option>
                                                                <option value="IC">Canary Islands*</option>
                                                                <option value="CV">Cape Verde</option>
                                                                <option value="KY">Cayman Islands</option>
                                                                <option value="CF">Central African Republic</option>
                                                                <option value="TD">Chad</option>
                                                                <option value="CL">Chile</option>
                                                                <option value="CN">China, People's Republic of*</option>
                                                                <option value="CO">Colombia</option>
                                                                <option value="KM">Comoros</option>
                                                                <option value="CG">Congo</option>
                                                                <option value="CK">Cook Islands</option>
                                                                <option value="CR">Costa Rica</option>
                                                                <option value="HR">Croatia*</option>
                                                                <option value="CB">Curacao</option>
                                                                <option value="CY">Cyprus*</option>
                                                                <option value="CZ">Czech Republic*</option>
                                                                <option value="CD">Democratic Republic of Congo</option>
                                                                <option value="DK">Denmark*</option>
                                                                <option value="DJ">Djibouti</option>
                                                                <option value="DM">Dominica</option>
                                                                <option value="DO">Dominican Republic</option>
                                                                <option value="EC">Ecuador</option>
                                                                <option value="EG">Egypt</option>
                                                                <option value="SV">El Salvador</option>
                                                                <option value="EN">England*</option>
                                                                <option value="GQ">Equatorial Guinea</option>
                                                                <option value="ER">Eritrea</option>
                                                                <option value="EE">Estonia*</option>
                                                                <option value="ET">Ethiopia</option>
                                                                <option value="FO">Faeroe Islands*</option>
                                                                <option value="FJ">Fiji</option>
                                                                <option value="FI">Finland*</option>
                                                                <option value="FR">France*</option>
                                                                <option value="GF">French Guiana</option>
                                                                <option value="PF">French Polynesia</option>
                                                                <option value="GA">Gabon</option>
                                                                <option value="GM">Gambia</option>
                                                                <option value="GE">Georgia*</option>
                                                                <option value="DE">Germany*</option>
                                                                <option value="GH">Ghana</option>
                                                                <option value="GI">Gibraltar</option>
                                                                <option value="GR">Greece*</option>
                                                                <option value="GL">Greenland*</option>
                                                                <option value="GD">Grenada</option>
                                                                <option value="GP">Guadeloupe</option>
                                                                <option value="GU">Guam</option>
                                                                <option value="GT">Guatemala</option>
                                                                <option value="GG">Guernsey*</option>
                                                                <option value="GN">Guinea</option>
                                                                <option value="GW">Guinea-Bissau</option>
                                                                <option value="GY">Guyana</option>
                                                                <option value="HT">Haiti</option>
                                                                <option value="HO">Holland*</option>
                                                                <option value="HN">Honduras</option>
                                                                <option value="HK">Hong Kong</option>
                                                                <option value="HU">Hungary*</option>
                                                                <option value="IS">Iceland*</option>
                                                                <option value="IN">India*</option>
                                                                <option value="ID">Indonesia*</option>
                                                                <option value="IQ">Iraq</option>
                                                                <option value="IE">Ireland, Republic of</option>
                                                                <option value="IL">Israel*</option>
                                                                <option value="IT">Italy*</option>
                                                                <option value="CI">Ivory Coast</option>
                                                                <option value="JM">Jamaica</option>
                                                                <option value="JP">Japan*</option>
                                                                <option value="JE">Jersey*</option>
                                                                <option value="JO">Jordan</option>
                                                                <option value="KZ">Kazakhstan*</option>
                                                                <option value="KE">Kenya</option>
                                                                <option value="KI">Kiribati</option>
                                                                <option value="KO">Kosrae*</option>
                                                                <option value="KW">Kuwait</option>
                                                                <option value="KG">Kyrgyzstan*</option>
                                                                <option value="LA">Laos</option>
                                                                <option value="LV">Latvia*</option>
                                                                <option value="LB">Lebanon</option>
                                                                <option value="LS">Lesotho</option>
                                                                <option value="LR">Liberia</option>
                                                                <option value="LY">Libya</option>
                                                                <option value="LI">Liechtenstein*</option>
                                                                <option value="LT">Lithuania*</option>
                                                                <option value="LU">Luxembourg*</option>
                                                                <option value="MO">Macau</option>
                                                                <option value="MK">Macedonia (Fyrom)*</option>
                                                                <option value="MG">Madagascar</option>
                                                                <option value="M3">Madeira*</option>
                                                                <option value="MW">Malawi</option>
                                                                <option value="MY">Malaysia*</option>
                                                                <option value="MV">Maldives</option>
                                                                <option value="ML">Mali</option>
                                                                <option value="MT">Malta</option>
                                                                <option value="MH">Marshall Islands*</option>
                                                                <option value="MQ">Martinique*</option>
                                                                <option value="MR">Mauritania</option>
                                                                <option value="MU">Mauritius</option>
                                                                <option value="YT">Mayotte</option>
                                                                <option value="MX">Mexico*</option>
                                                                <option value="FM">Micronesia*</option>
                                                                <option value="MD">Moldova*</option>
                                                                <option value="MC">Monaco*</option>
                                                                <option value="MN">Mongolia*</option>
                                                                <option value="ME">Montenegro</option>
                                                                <option value="MS">Montserrat</option>
                                                                <option value="MA">Morocco</option>
                                                                <option value="MZ">Mozambique</option>
                                                                <option value="MP">N. Mariana Islands</option>
                                                                <option value="NA">Namibia</option>
                                                                <option value="NP">Nepal</option>
                                                                <option value="NL">Netherlands*</option>
                                                                <option value="AN">Netherlands Antilles</option>
                                                                <option value="NC">New Caledonia</option>
                                                                <option value="NZ">New Zealand*</option>
                                                                <option value="NI">Nicaragua</option>
                                                                <option value="NE">Niger</option>
                                                                <option value="NG">Nigeria</option>
                                                                <option value="NF">Norfolk Island</option>
                                                                <option value="NB">Northern Ireland*</option>
                                                                <option value="NO">Norway*</option>
                                                                <option value="OM">Oman</option>
                                                                <option value="PK">Pakistan*</option>
                                                                <option value="PW">Palau*</option>
                                                                <option value="PA">Panama</option>
                                                                <option value="PG">Papua New Guinea</option>
                                                                <option value="PY">Paraguay</option>
                                                                <option value="PE">Peru</option>
                                                                <option value="PH">Philippines*</option>
                                                                <option value="PL">Poland*</option>
                                                                <option value="PO">Ponape*</option>
                                                                <option value="PT">Portugal*</option>
                                                                <option value="PR">Puerto Rico*</option>
                                                                <option value="QA">Qatar</option>
                                                                <option value="RE">Reunion*</option>
                                                                <option value="RO">Romania*</option>
                                                                <option value="RT">Rota</option>
                                                                <option value="RU">Russia*</option>
                                                                <option value="RW">Rwanda</option>
                                                                <option value="SS">Saba</option>
                                                                <option value="SP">Saipan</option>
                                                                <option value="WS">Samoa</option>
                                                                <option value="SM">San Marino*</option>
                                                                <option value="SA">Saudi Arabia*</option>
                                                                <option value="SF">Scotland*</option>
                                                                <option value="SN">Senegal</option>
                                                                <option value="RS">Serbia*</option>
                                                                <option value="SC">Seychelles</option>
                                                                <option value="SL">Sierra Leone</option>
                                                                <option value="SG">Singapore*</option>
                                                                <option value="SK">Slovakia*</option>
                                                                <option value="SI">Slovenia*</option>
                                                                <option value="SB">Solomon Islands</option>
                                                                <option value="ZA">South Africa*</option>
                                                                <option value="KR">South Korea*</option>
                                                                <option value="ES">Spain*</option>
                                                                <option value="LK">Sri Lanka*</option>
                                                                <option value="NT">St. Barthelemy</option>
                                                                <option value="SW">St. Christopher</option>
                                                                <option value="SX">St. Croix*</option>
                                                                <option value="EU">St. Eustatius</option>
                                                                <option value="UV">St. John*</option>
                                                                <option value="KN">St. Kitts &amp; Nevis</option>
                                                                <option value="LC">St. Lucia</option>
                                                                <option value="MB">St. Maarten</option>
                                                                <option value="TB">St. Martin</option>
                                                                <option value="VL">St. Thomas*</option>
                                                                <option value="VC">St. Vincent/Grenadines</option>
                                                                <option value="SR">Suriname</option>
                                                                <option value="SZ">Swaziland</option>
                                                                <option value="SE">Sweden*</option>
                                                                <option value="CH">Switzerland*</option>
                                                                <option value="SY">Syria</option>
                                                                <option value="TA">Tahiti</option>
                                                                <option value="TW">Taiwan*</option>
                                                                <option value="TJ">Tajikistan*</option>
                                                                <option value="TZ">Tanzania</option>
                                                                <option value="TH">Thailand*</option>
                                                                <option value="TL">Timor Leste</option>
                                                                <option value="TI">Tinian</option>
                                                                <option value="TG">Togo</option>
                                                                <option value="TO">Tonga</option>
                                                                <option value="ZZ">Tortola</option>
                                                                <option value="TT">Trinidad &amp; Tobago</option>
                                                                <option value="TU">Truk*</option>
                                                                <option value="TN">Tunisia</option>
                                                                <option value="TR">Turkey*</option>
                                                                <option value="TM">Turkmenistan*</option>
                                                                <option value="TC">Turks &amp; Caicos Islands</option>
                                                                <option value="TV">Tuvalu</option>
                                                                <option value="UG">Uganda</option>
                                                                <option value="UA">Ukraine*</option>
                                                                <option value="UI">Union Island</option>
                                                                <option value="AE">United Arab Emirates</option>
                                                                <option value="GB">United Kingdom*</option>
                                                                <option value="US">United States*</option>
                                                                <option value="UY">Uruguay*</option>
                                                                <option value="VI">US Virgin Islands*</option>
                                                                <option value="UZ">Uzbekistan*</option>
                                                                <option value="VU">Vanatu</option>
                                                                <option value="VA">Vatican City State*</option>
                                                                <option value="VE">Venezuela</option>
                                                                <option value="VN">Vietnam*</option>
                                                                <option value="VR">Virgin Gorda</option>
                                                                <option value="WL">Wales*</option>
                                                                <option value="WF">Wallia &amp; Futuna Islands</option>
                                                                <option value="YA">Yap*</option>
                                                                <option value="YE">Yemen</option>
                                                                <option value="ZM">Zambia</option>
                                                                <option value="ZW">Zimbabwe</option>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="provinceTo">Province:</label>
                                                        <input required type="text" class="form-control" id="provinceTo" name="provinceTo">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cityTo">City:</label>
                                                        <input required type="text" class="form-control" id="cityTo" name="cityTo">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="streetTo">Street:</label>
                                                        <input required type="text" class="form-control" id="streetTo" name="streetTo">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="postalCodeTo">Postal Code:</label>
                                                        <input required type="text" class="form-control" id="postalCodeTo" name="postalCodeTo">
                                                    </div>

                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-headline">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Step 2: Shipment Information</h3>
                                        </div>
                                        <div class="panel-body row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="weight">Weight:</label>
                                                    <input required type="number" step="0.1" class="form-control" id="weight" name="weight">
                                                </div>
                                                <div class="form-group">
                                                    <label for="length">Length:</label>
                                                    <input required type="number" step="0.1" class="form-control" id="length" name="length">
                                                </div>
                                                <div class="form-group">
                                                    <label for="width">Width:</label>
                                                    <input required type="number" step="0.1" class="form-control" id="width" name="width">
                                                </div>
                                                <div class="form-group">
                                                    <label for="height">Height:</label>
                                                    <input required type="number" step="0.1" class="form-control" id="height" name="height">
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="weightUnit">Weight Unit:</label>
                                                    <select class="form-control" id="weightUnit" name="weightUnit">
                                                        <option value="pound">Pound (lbs)</option>
                                                        <option value="gram">Gram (g)</option>
                                                        <option value="ounce">Ounce (oz)</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="dimensionUnit">Dimension Unit:</label>
                                                    <select class="form-control" id="dimensionUnit" name="dimensionUnit">
                                                        <option value="inch">Inch (in)</option>
                                                        <option value="centimeter">Centimeter (cm)</option>
                                                    </select>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="panel-body row">
                                            <div class="col-md-6">
                                                <div class="form-group"  >
                                                    <button type="submit" class="btn btn-success">Calculate</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
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
        <!-- END WRAPPER -->
        <!-- Javascript -->

        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/vendor/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/scripts/klorofil-common.js"></script>
        <script>
            $(document).ready(function () {

                getLocation();

                $("#btnSetFrom").click(function () {

                    setFlag = SET_SHIP_FROM;
                    if ($(this).text().indexOf("Refresh") == -1) {
                        $(this).html("<i class='fa fa-refresh fa-spin'></i> Refreshing");
                    }
                });

                $("#btnSetTo").click(function () {
                    setFlag = SET_SHIP_TO;
                    if ($(this).text().indexOf("Refresh") == -1) {
                        $(this).html("<i class='fa fa-refresh fa-spin'></i> Refreshing");
                    }
                });
            });
        </script>

    </body>

</html>
