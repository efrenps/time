<!DOCTYPE html>
<html lang="en-US" prefix="og: http://ogp.me/ns#" class="color1 l-header-fixed js no-touch csstransforms csstransforms3d csstransitions svg js-ready" data-smooth-scroll="on" style="overflow: hidden;">
<head>
    <meta charset="utf-8">
    <title>Time Clock</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/reset-min.css"> 
    <link rel="stylesheet" href="packages/awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/StyleApp.css">
    <link rel="stylesheet" href="css/fancyInput.css">
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="css/toastr.min.css">
    <link rel="stylesheet" href="css/fancySelect.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <link href='http://fonts.googleapis.com/css?family=Duru+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="packages/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="packages/datetime/jquery.datetimepicker.css">     
    <style>
   .ui-autocomplete-input {
      border: none; 
      z-index: 1511;
      position: relative;
    }
   .ui-autocomplete {
      position: absolute;
      z-index: 1510 !important;
   }       
   .ui-state-hover, .ui-state-active {
      color: #ffffff;
      text-decoration: none;
      background-color: #0088cc;
      border-radius: 0px;
      -webkit-border-radius: 0px;
      -moz-border-radius: 0px;
      background-image: none;
    }
    #modalIns{
      width: 500px;
    }
    </style>
    
    {{-- FLIP CLOCK --}}
    {{ HTML::style('packages/clock/css/flipclock.css', array('media' => 'screen')) }}
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery.placeholder.js"></script>
    <script src="js/jquery.queryloader2.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="js/modernizr.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/js.js"></script>
    <script src="js/toastr.min.js"></script>
    <script src='js/fancyInput.js'></script>
    <script src="js/fancySelect.js"></script>
    <script src="js/niceScroll.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.0.0/moment.min.js"></script>
    <script src="packages/bootstrap/js/bootstrap.min.js"></script>
    <script src="packages/datetime/jquery.datetimepicker.js"></script>
    <script src="js/jstz.js"></script> 
    <script src="js/date.js"></script>
    <script>
       if (navigator.appName == 'Microsoft Internet Explorer'){
          var style = $('<style>.fancyInput input, textarea{color: #fff;}</style>');
          $('html > head').append(style);
       }
    </script>
</head>
<body id="mainContent">
    <div id="clockPosition"> 
        <div class="clock"></div>
    </div>

    <div>
        <img style=" z-index: 1; position: fixed; opacity: 0.7; border-radius: 6px; bottom: 20px;" src=" images/logo.png" />
    </div>

    <div class="slide" id="slide1" data-slide="1">
        <div style="background-color: #0F1620; width: 550px; height: 88px; float: right; margin-top: 40px; margin-right: 20px; vertical-align: middle; opacity: 0.9; border-radius: 6px">
            <div style="color: #CACACA; padding-top: 20px; padding-right: 40px; text-align: right; ">
                <label style="font-family: 'Duru Sans', sans-serif; font-size: x-large;  font-weight: bolder; "> Search by Name </label>
            </div>

            <div style="text-align:left">
                <input id="searchEmployeList" type="text" name="searchEmployeList" class="searchForList" placeholder="Type a name and press enter." value="" />
            </div>

            <div id="listEmployeeTable" class="listEmployeeTable" style="height: 376px;width:100%; overflow:auto">
            </div>
        </div>

        <div id='content'>
            <section class='input'>
                <div class="fancyInput" style="height:80px;">
                    <input id="name" name="employeeName" type="text" placeholder='Enter your name'>
                </div>
            </section>
        </div>
    </div>

    <div class="slide" id="slide2" data-slide="2">
        <div style="background-color: #0F1620; width: 500px; height: 70px; float: right; margin-top: 40px; vertical-align: middle; opacity: 0.7; border-radius: 6px">
            <div style="color: #CACACA; padding-top: 20px; padding-right: 40px; text-align: right; ">
                <label style="font-family: 'Duru Sans', sans-serif; font-size: x-large;  font-weight: bolder; "> Type your Password </label>
            </div>
        </div>

        <div id='content2' class="hide">
            <section class='input'>
                <div class="fancyInput" style="height:80px">
                    <input type="password" name="password" id="password" />
                </div>
            </section>
        </div>

    </div>

    <!--End Slide 2-->

    <div class="slide" id="slide3" data-slide="3">
        <div style="background-color: #0F1620; width: 500px; height: 70px; float: right; margin-top: 40px; vertical-align: middle; opacity: 0.7; border-radius: 6px">
            <div style="color: #CACACA; padding-top: 20px; padding-right: 40px; text-align: right; ">
                <label style="font-family: 'Duru Sans', sans-serif; font-size: x-large;  font-weight: bolder; ">Start or stop tracking your time</label>
            </div>
        </div>

        <div>
            <div id="divstart" style="position: absolute; top: 55%; left: 10%; margin-top: -50px; margin-left: -40px; width: 300px; height: 80px;" class="hide">
                <input type="submit" id="startbtn" name="startbtn" value="Start Work!" class="myButton">
            </div>
        </div>

        <div>
            <div id="divstop" style="position: absolute; top: 55%; left: 10%; margin-top: -50px; margin-left: -40px; width: 300px; height: 80px;" class="hide">
                
                <div>
                    <!-- Starts modal manual entry -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Add manual entry</h4>
                          </div>
                          <div class="modal-body">
                                <div class="form-group">
                                  <input id="modalSearch" type="text" placeholder="&#61447; Enter employee name" class="form-control empty" style="width:60%;">  
                                </div>
                                <div class="form-group">
                                   <p id="notificationMessage" style="font-size:11px;"></p>
                                </div>
                                <div class="form-group">
                                  <label for="datetimepicker"><i class="fa fa-clock-o"></i> Started work on</label>
                                  <input id="datetimepicker" type="text" class="form-control" placeholder="Select start date and time" class="form-control" style="width:40%">
                                </div>
                                <div class="form-group">
                                  <label for="datetimepicker2"><i class="fa fa-clock-o"></i> Stopped work on</label>
                                  <input id="datetimepicker2" type="text"class="form-control" placeholder="Select stop date and time" class="form-control" style="width:40%">
                                </div>
                          </div>
                           
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" id="ModalSave" class="btn btn-primary">Save changes</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                <!-- End modal for manual entry --> 
                
                <div>
                    <input type="submit" id="stopbtn" name="stopbtn" class="myButton" value="Stop Work">
                </div>
                

                <div style="height:20px;"></div>
                <select id="reason" name="reason" style="width: 300px; background-color: silver; padding-top:20px;" class="fancySelect">
                    <option value="1">Choose a reason...</option>
                    <option value="Lunch">Lunch</option>
                    <option value="Leaving for the day">Leaving for the day</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        <span class="slideno"><label id="result" style="font-size:52px; font-family: 'Duru Sans', sans-serif; text-shadow: black 0.1em 0.1em 0.2em;"></label></span>
        <div id="panelSupervisor" style="position: absolute; top: 40%; left: 5%; margin-top: -50px; margin-left: -40px; width: 600px;">
          <button id="reportbtn"  class="myButton reportbtn" style="width:40%;">Reports</button>
            <!-- Button trigger modal -->
            <a id="link" data-toggle="modal" href="#myModal" class="myButton" style="width:40%;;margin-left:5%;text-decoration:none">Manual Entry</a>
  
        </div> 
         <div id="panelUser" style="position: absolute; top: 35%; left: 5%; margin-top: -50px; margin-left: -40px; width: 600px;" class="hide" >
          <button id="reportbtn2"  class="myButton reportbtn" style="width:45%;margin-left:5%;">Reports</button>
        </div>

    </div>
    <!--End Slide 3-->

    <div id="ascrail2000" class="nicescroll-rails" style="width: 7px; z-index: 9999; cursor: default; position: fixed; top: 0px; height: 100%; right: 0px; opacity: 0.30000000000000004;"><div style="position: relative; top: 0px; float: right; width: 7px; height: 277px; background-color: rgb(66, 66, 66); border: 0px; background-clip: padding-box; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;"></div></div><div id="ascrail2000-hr" class="nicescroll-rails" style="height: 7px; z-index: 9999; position: fixed; left: 0px; width: 100%; bottom: 0px; cursor: default; display: none; opacity: 0.30000000000000004;"><div style="position: relative; top: 0px; height: 7px; width: 1366px; background-color: rgb(66, 66, 66); border: 0px; background-clip: padding-box; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;"></div></div>
    <script type="text/javascript" src="js/process.js"></script>
    {{ HTML::script('packages/clock/js/base.js') }}
    {{ HTML::script('js/clock.js') }}
    {{ HTML::script('packages/clock/js/flipclock.js') }}
    {{ HTML::script('packages/clock/js/counter.js') }}
    {{ HTML::script('packages/clock/js/hourlycounter.js') }}
    {{ HTML::script('packages/clock/js/minutecounter.js') }}
    {{ HTML::script('packages/clock/js/twentyfourhourclock.js') }}
    {{ HTML::script('packages/clock/js/twelvehourclock.js') }} 
</body>
</html>
