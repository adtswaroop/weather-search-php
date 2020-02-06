    <html>
    <meta http-equiv="Content-Type" content="text/html">
    <meta charset="utf-8">

    <head>
      <style type="text/css">
        #mainContainer {
          background-color: #32ab39;
          color: white;
          width: 600px;
          height: 300px;
          margin-left: auto;
          margin-right: auto;
          border-radius: 10px;
        }

        #inputForm {
          padding: 15px;
        }

        #warning {
          height: auto;
          margin-left: auto;
          margin-right: auto;
          width: 400px;
          border: 2px solid grey;
          padding: 10px;
          display: none;
          background: #e4e4e4;
          text-align: center;
        }

        div {
          margin-top: 10px
        }

        label {
          width: 20px;
        }

        p {
          margin: 0px;
        }

        h1 {
          margin: 0px;
        }

        td {
          text-align: center;
        }

        #weatherCard {
          width: 500px;
          height: fit-content;
          margin-left: auto;
          margin-right: auto;
          color: white;
          background-color: #5dc4f4;
          border-radius: 15px;
          padding: 35px;
          display: none;
        }

        #tblDivDaily{
          display: none;
        }

        #dailyWeatherDeets{
          display: none;
          margin-left: auto;
          margin-right: auto;
        }

        #tblDaily{
          border-collapse: collapse;
          background: #9fc9ee;
          color: white;
          width: 750px;
        }

        #tblDaily td{
          border: solid 2px #58a2e4;
        }

        #clickedCard{
          background-color: #a7d0d9;
          color:white;
          width: 500px;
          height: 400px;
          border-radius: 10px;
          padding: 20px;
          position: relative;
          margin-left: auto;
          margin-right: auto;
        }

        #indv_temp{
          position: absolute;
          top: 150px;
          font-size: 6em;
        } 

        #clickedCardWeValues
        {
          color: white;
          margin-top: 220px;
          margin-right: 50px;
          float: right;
          font-weight: bold;
          font-size: 1.2em;
        }

        #summShort{
          position: absolute;
          top: 70;
          font-size: 30px;
        }

        .summaryCell:hover{
          cursor: pointer;
        }

        #iconToggle:hover{
          cursor:pointer;
        }
        
        #graphDiv{
          text-align: center;  
          margin-left: auto;
          margin-right: auto;
        } 
      </style>
    
  
      <script>
        //declarations
        var inp_street;
        var inp_city;
        var inp_state;
        var lat;
        var long;
        var tblBody;

        // function resetPage(){
        //   document.getElementById("weatherCard").style.display = "none";
        //   document.getElementById("tblDivDaily").style.display = "none";
        //   document.getElementById("clickedCard").style.display = "none";
        //   //document.getElementById("weatherCard").style.display = "none";
        // }
        
        
        function clearInput(callFrom) {
             document.getElementById("weatherCard").style.display = "none";
          document.getElementById("tblDivDaily").style.display = "none";
          document.getElementById("dailyWeatherDeets").style.display = "none";
          if ((document.getElementById("currentLocation").checked) || callFrom == "btnClear") {
            document.getElementById("street").value = "";
            document.getElementById("city").value = "";
            document.getElementById("state").value = "State";
          }
          if (document.getElementById("currentLocation").checked) {
            document.getElementById("street").disabled = true;
            document.getElementById("street").style.cursor = "not-allowed";
            document.getElementById("city").disabled = true;
            document.getElementById("city").style.cursor = "not-allowed";
            document.getElementById("state").disabled = true;
            document.getElementById("state").style.cursor = "not-allowed";
          }
          if (!document.getElementById("currentLocation").checked) {
            document.getElementById("street").disabled = false;
            document.getElementById("city").disabled = false;
            document.getElementById("state").disabled = false;
            document.getElementById("street").style.cursor = "text";            
            document.getElementById("city").style.cursor = "text";
            document.getElementById("state").style.cursor = "text";
            document.getElementById("street").value = "";            
            document.getElementById("city").value = "";
            document.getElementById("cityIP").value = "";
            document.getElementById("state").value = "State";
          }
        }


        function validateInput() {
          var msgs = "";
          document.getElementById("weatherCard").style.display = "none";
          document.getElementById("tblDivDaily").style.display = "none";
          document.getElementById("dailyWeatherDeets").style.display = "none";
          var warningDiv = document.getElementById("warning");
          if (!document.getElementById("currentLocation").checked)
          {
          if (document.getElementById("street").value == "") {
            msgs += "Please check the street address <br>";
          } 
          else
            inp_street = document.getElementById("street").value;

          if (document.getElementById("city").value == "") {
            msgs += "Please check the city <br>"
          } 
          else
            inp_city = document.getElementById("city").value;
          
          if (document.getElementById("state").value == "") {
            msgs += "Please check the state";
          } 
          else
          {
            inp_state = document.getElementById("state").value;
            inp_state = inp_state.substring(3).trim();
          }
          }
        else{
          callIpAPI();
        }

        if ((msgs != "")) {
            warningDiv.innerHTML = msgs;
            warningDiv.style.display = "block";
        } 
        else {
            warningDiv.style.display = "none";
            callgeocodeAPI();
        }
      }

        function callgeocodeAPI() {
          //alert(inp_street);
          var XMLResult;
          var geocodeURL = "https://maps.googleapis.com/maps/api/geocode/xml?address=" + encodeURIComponent(inp_street) + "+" + encodeURIComponent(inp_city) + "+" + encodeURIComponent(inp_state) + "&key=AIzaSyBrBr9aAaxmyFGIoSsXAY0giluT4TL5DrQ";
          var xmlhttpGeo = new XMLHttpRequest();
          xmlhttpGeo.open("GET", geocodeURL, false);
          xmlhttpGeo.onerror = function() {
            alert("Sorry! An Error Occured: " + xmlhttpGeo.status + "  " + xmlhttpGeo.statusText);
          }
          xmlhttpGeo.onload = function() {
            if (xmlhttpGeo.status != 200) {
              alert("Sorry! An Error Occured: " + xmlhttpGeo.status + "  " + xmlhttpGeo.statusText);
            }
          }
          xmlhttpGeo.send();
          XMLResult = xmlhttpGeo.responseText;
          parser = new DOMParser();
          XMLResult = parser.parseFromString(XMLResult, "text/xml");
          geometryNode = XMLResult.getElementsByTagName("geometry");
          locationNode = geometryNode[0].childNodes[1];
          lat = locationNode.childNodes[1].innerHTML;
          long = locationNode.childNodes[3].innerHTML;

          //send values to php
          document.getElementById("php_lat").value = lat;
          document.getElementById("php_long").value = long;
          document.getElementById("cityIP").value = "";
          document.inputForm.submit();
          // document.forPHP.onsubmit = check();
        }

        function callIpAPI(){
          var jsonResult;
          var ipURL = "http://ip-api.com/json";
          var xmlhttpIP = new XMLHttpRequest();
          xmlhttpIP.open("GET", ipURL, false);
          xmlhttpIP.onerror = function() {
            alert("Sorry! An Error Occured: " + xmlhttpIP.status + "  " + xmlhttpIP.statusText);
          }
          xmlhttpIP.onload = function() {
            if (xmlhttpIP.status != 200) {
              alert("Sorry! An Error Occured: " + xmlhttpIP.status + "  " + xmlhttpIP.statusText);
            }
          }
          xmlhttpIP.send();
          jsonResult = JSON.parse(xmlhttpIP.responseText);
          var lat = jsonResult.lat;
          var lon = jsonResult.lon;
          document.getElementById("php_lat").value = lat;
          document.getElementById("php_long").value = lon;
          document.getElementById("cityIP").value = jsonResult.city;
          document.inputForm.submit();
        }
      </script>

    </head>

    <body>
      <?php
      
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST))
        { 
              if(! isset($_POST["clickedTimeStamp"]))
              {
                $searched = true;
                $street = $_POST["street"];
                $city   = isset($_POST["city"]) ? $_POST["city"] : $_POST["cityIP"];
                $state_temp = explode(":", $_POST["state"]);
                $state = preg_replace('/\s+/', '', $state_temp[1]);
                $latitude = $_POST["php_lat"];
                $longitude = $_POST["php_long"];
                //calling Forecast API
                $urlForecast = "https://api.forecast.io/forecast/10d21b84c3e1da9fb0df0600d59e2440/" . urlencode($latitude) . "," . urlencode($longitude) . "?exclude=minutely,hourly,alerts,flags";
                $forecastApiResponse = file_get_contents($urlForecast);
                $jsonObj = json_decode($forecastApiResponse);
                
                $crd_timezone = $jsonObj->timezone;
                $crd_temperature = $jsonObj->currently->temperature;
                $crd_summary = $jsonObj->currently->summary;
                $crd_humidity = $jsonObj->currently->humidity;
                $crd_pressure = $jsonObj->currently->pressure;
                $crd_windSpeed = $jsonObj->currently->windSpeed;
                $crd_visibility = $jsonObj->currently->visibility;
                $crd_cloudCover = $jsonObj->currently->cloudCover;
                $crd_ozone = $jsonObj->currently->ozone;

                $daily = $jsonObj->daily->data;
                $array_fin = json_encode($daily);
                //print_r($array_fin);
                //echo $array_fin;
              }
              else
              {
                //var_dump($_POST);
                $street2 = $_POST["street2"];
                $city2 = $_POST["city2"];
                $state2 = $_POST["state2"];
                $urlIndividualForecast = "https://api.forecast.io/forecast/10d21b84c3e1da9fb0df0600d59e2440/".$_POST["php_lat2"].",".$_POST["php_long2"];
                //echo $urlIndividualForecast;
                if(isset($_POST["clickedTimeStamp"]))
                {
                  $timess = $_POST["clickedTimeStamp"];
                  $ipFlag = $_POST["ipFlag"];
                  $urlIndividualForecast .= ",".$timess."?exclude=minutely";
                  $darskyApiResponse = file_get_contents($urlIndividualForecast);
                  $jsonObjDarkSky = json_decode($darskyApiResponse);
                  //individual card elements
                  $indv_timezone = $jsonObjDarkSky->timezone;
                  //echo $indv_timezone;
                  $indv_summary = $jsonObjDarkSky->currently->summary;
                  $indv_temp = $jsonObjDarkSky->currently->temperature;
                  $indv_icon = strval($jsonObjDarkSky->currently->icon);
                  $indv_percip = $jsonObjDarkSky->currently->precipIntensity;
                  $indv_coRain = $jsonObjDarkSky->currently->precipProbability;
                  $indv_windSpeed = $jsonObjDarkSky->currently->windSpeed;
                  $indv_humidity = $jsonObjDarkSky->currently->humidity;
                  $indv_visibility = $jsonObjDarkSky->currently->visibility;
                  $indv_sunRiseRes = $jsonObjDarkSky->daily->data["0"]->sunriseTime;
                  $indv_sunRise = new DateTime('@'.$indv_sunRiseRes);
                  $indv_sunRise->setTimeZone(new DateTimeZone($indv_timezone));
                  $indv_sunSetRes = $jsonObjDarkSky->daily->data["0"]->sunsetTime;    
                  $indv_sunSet = new DateTime('@'.$indv_sunSetRes);
                  $indv_sunSet->setTimeZone(new DateTimeZone($indv_timezone)); 
                  
                  //return false;             
                  $hourlyData = json_encode($jsonObjDarkSky->hourly->data);
                }
          }
        }
      }
      
      ?>

      <div id="mainContainer" align="center">
        <h1 style="padding-top: 10px; margin-bottom:0px"><i>Weather Search</i></h1>
        <form id="inputForm" name="inputForm" method="POST">
          <div style="float:left; padding:15px">
          <table style="color:white">
            <tr>
              <td><label for="street">Street</label></td>
              <td><input type="text" id="street" name="street" placeholder="Enter Street Address" value="<?php echo isset($street) ? $street : ( isset($street2) ? $street2 : ''); ?>"></td>
            </tr>
            <tr>
              <td><label for="city">City</label></td>
              <td><input type="text" id="city" name="city" placeholder="Enter City" value="<?php echo isset($city) ? $city : ( isset($city2) ? $city2 : ''); ?>"></td>
            </tr>
            <tr>
              <td><label for="state">State</label></td>
              <td><select id="state" name="state">
                <option value=""><?php echo isset($state) ? $state : ( isset($state2) ? $state2 : 'State'); ?></option>
                <option value="AL:Alabama             ">Alabama </option>
                <option value="AK:Alaska              ">Alaska </option>
                <option value="AZ:Arizona             ">Arizona </option>
                <option value="AR:Arkansas            ">Arkansas </option>
                <option value="CA:California          ">California </option>
                <option value="CO:Colorado            ">Colorado </option>
                <option value="CT:Connecticut         ">Connecticut </option>
                <option value="DE:Delaware            ">Delaware </option>
                <option value="DC:District Of Columbia">District Of Columbia </option>
                <option value="FL:Florida             ">Florida </option>
                <option value="GA:Georgia             ">Georgia </option>
                <option value="HI:Hawaii              ">Hawaii </option>
                <option value="ID:Idaho               ">Idaho </option>
                <option value="IL:Illinois            ">Illinois </option>
                <option value="IN:Indiana             ">Indiana </option>
                <option value="IA:Iowa                ">Iowa </option>
                <option value="KS:Kansas              ">Kansas </option>
                <option value="KY:Kentucky            ">Kentucky </option>
                <option value="LA:Louisiana           ">Louisiana </option>
                <option value="ME:Maine               ">Maine </option>
                <option value="MD:Maryland            ">Maryland </option>
                <option value="MA:Massachusetts       ">Massachusetts </option>
                <option value="MI:Michigan            ">Michigan </option>
                <option value="MN:Minnesota           ">Minnesota </option>
                <option value="MS:Mississippi         ">Mississippi </option>
                <option value="MO:Missouri            ">Missouri </option>
                <option value="MT:Montana             ">Montana </option>
                <option value="NE:Nebraska            ">Nebraska </option>
                <option value="NV:Nevada              ">Nevada </option>
                <option value="NH:New Hampshire       ">New Hampshire </option>
                <option value="NJ:New Jersey          ">New Jersey </option>
                <option value="NM:New Mexico          ">New Mexico </option>
                <option value="NY:New York            ">New York </option>
                <option value="NC:North Carolina      ">North Carolina </option>
                <option value="ND:North Dakota        ">North Dakota </option>
                <option value="OH:Ohio                ">Ohio </option>
                <option value="OK:Oklahoma            ">Oklahoma </option>
                <option value="OR:Oregon              ">Oregon </option>
                <option value="PA:Pennsylvania        ">Pennsylvania </option>
                <option value="RI:Rhode Island        ">Rhode Island </option>
                <option value="SC:South Carolina      ">South Carolina </option>
                <option value="SD:South Dakota        ">South Dakota </option>
                <option value="TN:Tennessee           ">Tennessee </option>
                <option value="TX:Texas               ">Texas </option>
                <option value="UT:Utah                ">Utah </option>
                <option value="VT:Vermont             ">Vermont </option>
                <option value="VA:Virginia            ">Virginia </option>
                <option value="WA:Washington          ">Washington </option>
                <option value="WV:West Virginia       ">West Virginia </option>
                <option value="WI:Wisconsin           ">Wisconsin </option>
                <option value="WY:Wyoming             ">Wyoming </option>
              </select></td>
            </tr>
          </table>
          </div>
          <div style="border-left: 3px solid white; width:3px; height:150px"></div>
          <div style="float:right; position: relative; bottom: 120; right: 75;">
            <input type="checkbox" id="currentLocation" onclick="clearInput()">
            <label for="currentLocation">Current Location</label>
          </div>
          <button type="button" id="btnSearch" style="margin-top:20px; margin-left:100px" onclick="validateInput(); return false;">Search</button>
          <button type="button" id="btnClear" onclick="clearInput(this.id)">Clear</button>
          <input type="hidden" id="php_lat" name="php_lat">
          <input type="hidden" id="php_long" name="php_long">
          <input type="hidden" id="cityIP" name="cityIP">
        </form>
        <!-- <form name="forPHP" method="POST">
          <input type="hidden" id="php_lat" name="php_lat">
          <input type= "hidden" id="php_long" name="php_long">
          <input type= "hidden" id="php_street" name="php_street">
          <input type= "hidden" id="php_city" name="php_city">
          <input type= "hidden" id="php_state" name="php_state">          
        </form> -->
      </div>
      <div id="warning"></div>

      <div id="weatherCard">
        <h1 id="crdCityHeading"><?php echo isset($city) ? $city : '' ?></h1>
        <p id="crdTimezone"><?php echo isset($crd_timezone) ? $crd_timezone : '' ?> </p>
        <p id="crdTemperature" style="font-size:5em; margin-top:10px; font-weight:700"><?php echo isset($crd_temperature) ? $crd_temperature : '45' ?><sup style="font-size:40px; color:black">&deg</sup><small style="font-size:50px">F</small></p>
        <h1 id="crdSummary">Clear</h1>
        <table style="width:100%;margin-top:10px;margin-left:-20px;table-layout:fixed">
          <tr>
            <td>
              <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-16-512.png" width="25px" title="Humidity">
            </td>
            <td>
              <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-25-512.png" width="25px" title="Pressure">
            </td>
            <td>
              <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png" width="25px" title="Wind Speed">
            </td>
            <td>
              <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-30-512.png" width="25px" title="Visibility">
            </td>
            <td>
              <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png" width="25px" title="Cloud Cover">
            </td>
            <td>
              <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-24-512.png" width="25px" title="Ozone">
            </td>
          </tr>
          <tr style="color:white; font-weight:600; font-size:20px">
            <td id="crdHumidity"><?php echo isset($crd_humidity) ? round($crd_humidity , 3) : 'N/A' ?></td>
            <td id="crdPressure"><?php echo isset($crd_pressure) ? round($crd_pressure, 3) : 'N/A' ?></td>
            <td id="crdWindSpeed"><?php echo isset($crd_windSpeed) ? round($crd_windSpeed, 3) : 'N/A' ?></td>

            <td id="crdVisibility"><?php echo isset($crd_visibility) ? round($crd_visibility, 3): 'N/A' ?></td>
            <td id="crdCloudCov"><?php echo isset($crd_cloudCover) ? round($crd_cloudCover, 3) : 'N/A' ?></td>
            <td id="crdOzone"><?php echo isset($crd_ozone) ? round($crd_ozone, 3) : 'N/A' ?></td>
          </tr>
        </table>
      </div>
      <div id="tblDivDaily" align="center">
      <table id="tblDaily">
            <script>
              var searched = <?php echo isset($forecastApiResponse) ? 1 : 0; ?>;
              //alert(searched)
              if(searched)
              {
                document.getElementById("weatherCard").style.display = "block";
                document.getElementById("tblDivDaily").style.display = "block";
              }
              
              var ipCall = "<?php echo isset($_POST["cityIP"]) ? $_POST["cityIP"] : "no"; ?>";
              if(ipCall.length > 2 )
              {
                document.getElementById("street").disabled = true;
                document.getElementById("street").value = "";
                document.getElementById("street").style.cursor = "not-allowed";
                document.getElementById("city").disabled = true;
                document.getElementById("city").value = "";
                document.getElementById("city").style.cursor = "not-allowed";
                document.getElementById("state").disabled = true;
                document.getElementById("state").value = "";
                document.getElementById("state").style.cursor = "not-allowed"; 
                document.getElementById("currentLocation").checked = true;
              }
              else
              {
                document.getElementById("currentLocation").checked = false; 
              }
              var arrDailyTbl = <?php echo isset($array_fin) ? $array_fin : "0"; ?>;
              var latit = <?php echo isset($latitude) ? $latitude : "0"; ?>;
              var longit = <?php echo isset($longitude) ? $longitude : "0"; ?>;
              var city = "<?php echo isset($city) ? $city : "N/A"; ?>";
              var street = "<?php echo isset($street) ? $street : "N/A"; ?>" ;
              var state = "<?php echo isset($state) ? $state : "N/A"; ?>" ;
              var arrLen = arrDailyTbl.length;
              var tblHTML =""; 
              for(i=0; i<arrLen;i++)
              {
                tblHTML += "<tr>";
                //date
                date = new Date((arrDailyTbl[i].time) * 1000);
                month = (date.getMonth()+1).toString();
                month = month.length == 2 ? month : "0"+month;
                dd = date.getDate().toString();
                dd = dd.length == 2 ? dd : "0"+dd;
                yyyy = date.getFullYear().toString();
                date_fin =  yyyy + "-" + month + "-" + dd;
                tblHTML += "<td>" + date_fin + "</td>";
                //status
                icon = arrDailyTbl[i].icon;
                var iconURL;
                switch(icon){
                  case "clear-day":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-12-512.png";
                    break;
                  case "clear-night":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-12-512.png";
                    break;
                  case "rain":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-04-512.png";
                    break;
                  case "snow":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-19-512.png";
                    break;
                  case "sleet":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-07-512.png"
                    break;
                  case "wind":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png";
                    break;
                  case "fog": 
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png";
                    break;
                  case "cloudy":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-01-512.png"
                    break;
                  case "partly-cloudy-day":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-02-512.png";
                    break;
                  case "partly-cloudy-night":
                    iconURL = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-02-512.png";
                    break;
                  default:
                    iconURL = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
                    break;
                  }
                tblHTML += "<td><img src=\""+iconURL+"\" width=60px>" + "</td>";
                //summary
                tblHTML += "<td class=\"summaryCell\" onclick=\"getWeatherForDate(this)\">" + arrDailyTbl[i].summary + "<input type=\"hidden\" value=\"" +arrDailyTbl[i].time + "\">" + "</td>";
                //temp high
                tblHTML += "<td>" + arrDailyTbl[i].temperatureHigh + "</td>";
                //temp low
                tblHTML += "<td>" + arrDailyTbl[i].temperatureLow + "</td>";
                //wind speed
                tblHTML += "<td>" + arrDailyTbl[i].windSpeed + "</td>" + "</tr>";
                }
                document.getElementById("tblDaily").innerHTML =  "<thead><tr><td>Date</td><td>Status</td><td>Summary</td><td>TemperatureHigh</td><td>TemperatureLow</td>" +
                                                                  "<td>Wind Speed</td></tr></thead><tbody>" + tblHTML + " </tbody>";       
                                                                  
                function getWeatherForDate(calledFrom)
                {
                  
                  var inputEle = calledFrom.getElementsByTagName("input");
                  var timestamp = inputEle[0].value;
                  if(document.getElementById("currentLocation").checked)
                  {
                    document.getElementById("clickedTimeStamp").value = timestamp;
                    document.getElementById("php_lat2").value = latit;
                    document.getElementById("php_long2").value = longit;
                    document.getElementById("street2").value = "";
                    document.getElementById("city2").value = "";
                    document.getElementById("state2").value = ""; 
                    document.getElementById("ipFlag").value = "IP"; 
                  }
                  else{
                  document.getElementById("clickedTimeStamp").value = timestamp;
                  document.getElementById("php_lat2").value = latit;
                  document.getElementById("php_long2").value = longit;
                  document.getElementById("street2").value = street;
                  document.getElementById("city2").value = city;
                  document.getElementById("state2").value = state;
                  document.getElementById("ipFlag").value = "noIP";
                  }
                  document.forTimestamp.submit();             
               }
            </script>
        </table>
        </div>
        <div id="dailyWeatherDeets"style="width:fit-content">
          <h1 id="dailyTitle" style="text-align: center;margin-top: 30px;">Daily Weather Detail</h1>
        <div id="clickedCard">
             <form method="POST" name="forTimestamp" style="display:hidden">
                <input id="clickedTimeStamp"  name="clickedTimeStamp" type="hidden">
                <input type="hidden" id="php_lat2" name="php_lat2">
                <input type="hidden" id="php_long2" name="php_long2">
                <input type="hidden" id="street2" name="street2">
                <input type="hidden" id="city2" name="city2">
                <input type="hidden" id="state2" name="state2">
                <input type="hidden" id="ipFlag" name="ipFlag">
            </form>

            <img id="indv_icon" src="" width="200px" style="position:absolute;top:20;right:50;">
            <h2 id="summShort"></h2>
            <h1 id="indv_temp"></h1>
           <table id="clickedCardWeValues">
              <tr><td style="text-align:right">Percipitation:</td>   <td style="text-align:left" id="tdPercip"></td></tr>
              <tr><td style="text-align:right">Chance of rain:</td>  <td style="text-align:left" id="tdCoRain"></td></tr>
              <tr><td style="text-align:right">Wind Speed:</td>      <td style="text-align:left" id="tdWindSpeed"></td></tr>
              <tr><td style="text-align:right">Humidity:</td>   <td style="text-align:left" id="tdHumidity"></td></tr>
              <tr><td style="text-align:right">Visibility:</td>      <td style="text-align:left" id="tdVisi"></td></tr>
              <tr><td style="text-align:right">Sunrise / Sunset:</td>  <td style="text-align:left" id="tdSunTime"></td></tr>
           </table>
           <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
           <script>
             var imgSrc;
             var sunRise;
             var sunSet;
             var clicked = <?php echo isset($darskyApiResponse) ? 1 : 0; ?>;
             if(clicked)
             {
               document.getElementById("dailyWeatherDeets").style.display = "block";
             }
             var ipFlagg = "<?php echo $ipFlag?>";
             if(ipFlagg == "IP")
             {
                document.getElementById("street").disabled = true;
                document.getElementById("street").value = "";
                document.getElementById("street").style.cursor = "not-allowed";
                document.getElementById("city").disabled = true;
                document.getElementById("city").value = "";
                document.getElementById("city").style.cursor = "not-allowed";
                document.getElementById("state").disabled = true;
                document.getElementById("state").value = "";
                document.getElementById("state").style.cursor = "not-allowed"; 
                document.getElementById("currentLocation").checked = true; 
             }
             var isumm = "<?php echo isset($indv_summary) ? $indv_summary : "N/A"; ?>";
             var itemp = "<?php echo isset($indv_temp) ? round($indv_temp) : "N/A"; ?>";
             var iprecip = "<?php echo isset($indv_percip) ? round($indv_percip, 2) : "N/A"; ?>";
             var imgType = "<?php echo isset($indv_icon) ? $indv_icon : "null"; ?>";
             switch(imgType)
             {
                  case "clear-day":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
                    break;
                  case "clear-night":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
                    break;
                  case "rain":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/rain-512.png";
                    break;
                  case "snow":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/snow-512.png";
                    break;
                  case "sleet":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/lightning-512.png"
                    break;
                  case "wind":
                    imgSrc = "https://cdn4.iconfinder.com/data/icons/the-weather-is-nice-today/64/weather_10-512.png";
                    break;
                  case "fog": 
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/cloudy-512.png";
                    break;
                  case "cloudy":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/cloud-512.png"
                    break;
                  case "partly-cloudy-day":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sunny-512.png";
                    break;
                  case "partly-cloudy-night":
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sunny-512.png";
                    break;
                  default:
                    imgSrc = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
                    break;
                  }
                  var ichance = "<?php echo isset($indv_coRain) ? round($indv_coRain, 2) : "N/A"; ?>";
                  var iWind = "<?php echo isset($indv_windSpeed) ? round($indv_windSpeed, 2) : "N/A"; ?>";
                  var iHumid = "<?php echo isset($indv_humidity) ? round($indv_humidity,2) : "N/A"; ?>";
                  var iVisib = "<?php echo isset($indv_visibility) ? round($indv_visibility,2) : "N/A"; ?>";
                  var isunRise = "<?php echo isset($indv_sunRise) ? $indv_sunRise->format('g A') : "N/A"; ?>"; 
                  // if(isunRise != "N/A")
                  // {
                  //     sunRise = new Date(isunRise * 1000).getHours();
                  //     if(sunRise > 12)
                  //     {
                  //       sunRise = sunRise - 12;
                  //       sunRise += " PM";
                  //     }
                  //     else{
                  //       sunRise += " AM";
                  //     }
                  // }

                  var isunSet = "<?php echo isset($indv_sunSet) ? $indv_sunSet->format('g A') : "N/A"; ?>"; 
                  // if(isunSet != "N/A")
                  // {
                  //     sunSet = new Date(isunSet * 1000).getHours();
                  //     if(sunSet > 12)
                  //     {
                  //       sunSet = sunSet - 12;
                  //       sunSet += " PM";
                  //     }
                  //     else{
                  //       sunSet += " AM";
                  //     }
                  // }
                  
                  document.getElementById("indv_icon").src = imgSrc; 
                  document.getElementById("summShort").innerHTML = isumm;
                  document.getElementById("indv_temp").innerHTML = itemp + "<sup style=\"font-size:40px; color:black\">&deg</sup><small style=\"font-size:50px\">F</small>"; 
                  document.getElementById("tdPercip").innerHTML = iprecip;
                  document.getElementById("tdCoRain").innerHTML = ichance + "%";
                  document.getElementById("tdWindSpeed").innerHTML = iWind + " mph";
                  document.getElementById("tdHumidity").innerHTML = iHumid + "%";
                  document.getElementById("tdVisi").innerHTML = iVisib + " mi";
                  document.getElementById("tdSunTime").innerHTML = isunRise + " / " + isunSet;

                  function toggleGraph()
                  {
                    var graphDisplay = document.getElementById("graphDiv").style.display;
                    var getAttrib = document.getElementById("iconToggle").getAttribute("src");
                    if(getAttrib == "https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png")
                    {
                      //display the graph
                      document.getElementById("iconToggle").src = "https://cdn0.iconfinder.com/data/icons/navigation-set-arrows-part-one/32/ExpandLess-512.png"; 
                      google.charts.load('current', {packages: ['corechart']});
                      google.charts.setOnLoadCallback(drawBasic);
                    }
                    else
                    {
                      //display the graph
                      document.getElementById("iconToggle").src = "https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png";
                      document.getElementById("graphDiv").innerHTML="";
                    }

                   
                  }

                  function drawBasic() 
                  {
                  var hourlyData = <? echo isset($hourlyData) ? $hourlyData : "0" ; ?>;
                  var hourTemps = [];
                  for(i = 0; i<hourlyData.length; i++)
                  {
                    hourTemps[i] = new Array();
                    hourTemps[i].push(i, hourlyData[i].temperature);
                  }
                  console.log(hourTemps);
                  //alert("inFunction");
                    var data = new google.visualization.DataTable();
                    data.addColumn('number', 'Time');
                    data.addColumn('number', 'Temperature');
                    data.addRows(hourTemps);
                    var options = {
                    curveType: 'function',
                    tooltip: {isHtml: true},
                    series: {0: {color: '#93CBD8'}},
                    hAxis: {
                        title: 'Time'
                    },
                    vAxis: {
                      title: 'Temperature',
                      textPosition: 'none'
                    },
                    width: 600
                  };

                var chart = new google.visualization.LineChart(document.getElementById('graphDiv'));
                chart.draw(data, options);
              }
           </script>
           </div>
           <h1 style="margin-top:20px; text-align:center">Day's Hourly Weather</h1>
           <div style="margin-left:auto;margin-right:auto" align="center" onclick="toggleGraph()"><img width="50px" id="iconToggle" src="https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png"></div>
           <div id="graphDiv"></div>
      </div>
    </body>

    </html>