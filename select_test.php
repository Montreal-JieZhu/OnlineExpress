<?php
//https://api.chucknorris.io/
//base url of our web service
$base_url = "http://locationservicejac.azurewebsites.net/api/";

//function that handles all the curl requests
function fetch_curl($url) {
    $ch = curl_init($url); //initialize the fetch command
    //prevent automatic output to screen
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // in case of MAMP issues
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $results = curl_exec($ch); //execute the fetch command
    curl_close($ch); //close curl request
    //decode JSON that is returned
    $data = json_decode($results);

    return $data;
}

//request all countries
$countries = fetch_curl($base_url . 'country');

//request all provinces
$provinces = fetch_curl($base_url . 'province');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Chuck Norris</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {

                //var children_elements = $(".province").children();

                //console.log(children_elements);

                $(".province").children().each(function (index, item) {
                    if (this.value.substring(0, 2) != $(".country").val()) {
                        $(item).attr("hidden", true);
                    } 
                });
                



                $(".country").change(function () {

                    $(".province").children().removeAttr("hidden");

                    $(".province").children().each(function (index, item) {
                        if (this.value.substring(0, 2) != $(".country").val()) {
                            $(item).attr("hidden", true);
                        } 
                    });
                    
                   
                    //alert($(".country").val());

                    /*
                     $.ajax({
                     url: "http://locationservicejac.azurewebsites.net/api/province/CA", // + $(".country").val(),
                     type: "GET",
                     timeout: 30000,
                     
                     success: function (data) {
                     alert("sccussful");
                     },
                     error: function () {
                     alert("error");
                     }
                     });
                     */
                });

            });



        </script>

    </head>
    <body>

        <h1>Random Chuck Norris Facts</h1>
        <p class="result">888</p>
        <form>

            <select id="country" class="country">
                <?php
                foreach ($countries as $country) {
                    ?>
                    <option value="<?= $country->code ?>"><?= $country->name ?></option>
                    <?php
                }
                ?>
            </select>

            <br/><br/>


            <select id="province" class="province">
                <option value="">Select a province</option>
                <?php
                foreach ($provinces as $province) {
                    ?>
                    <option value="<?= $province->countryCode . $province->code ?>"><?= $province->name ?></option>
                    <?php
                }
                ?>

            </select>

        </form>
        <br/>




    </body>

</html>