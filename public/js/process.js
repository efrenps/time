var GlobalId
var GlobalSupervisor
var GlobalAction
var GlobarUserId
var GlobalEmployeeName

$(document).ready(function () {
    $('#datetimepicker').datetimepicker({
            format:'Y-m-d H:i',
            closeOnDateSelect:true
        });

    $('#datetimepicker2').datetimepicker({
         format:'Y-m-d H:i',
         closeOnDateSelect:true
    });

    $("#ModalSave").attr("disabled", "disabled");
   
      // IE fix modal bootstrap bug
    $(".modal").appendTo($("body"));

    $.fn.placeholder();
    $("body").queryLoader2({
        percentage: true,
        completeAnimation: 'fade',
        barColor: "#9F2222",
        barHeight: 0,
        deepSearch: true,
        onComplete: function () {
            $("#mainContent").show();
            $("#slide2").hide();
            $("#slide3").hide();
            $("#password").blur(function () {
                window.setInterval(function () {
                    $("#password").focus();
                    window.clearInterval(1)
                }, 200);
            });

            retrieveListEmployee('');
        }
    });
    
});

$('#searchEmployeList').keyup(function () {
        retrieveListEmployee($('#searchEmployeList').val());
});

function retrieveListEmployee(search) {
    var timezone = jstz.determine();
    var localTimezone = timezone.name();
    
    $.ajax({
        delay: 0,
        type: "GET",
        url: "employeeActions",
        data: {
            name: search,
            localTimezone: localTimezone
        },
        success: function (msg) {
            console.log(msg);
            $('#listEmployeeTable').html
            (
                msg
            );
        },
        failure: function (msg) {
            alert('Employee not found');
        }
    });
}


function animEffect(id, effect) {
    $(id).removeClass().addClass(effect + ' animated').one('webkitAnimationEnd oAnimationEnd', function () {
        $(this).removeClass();
    });
}

var employeeId = 0;
var currentTimeId = 0;

$("html,body").animate({ scrollTop: 0 }, "slow");

$('.fancySelect').fancySelect();

toastr.options = {
    "closeButton": false,
    "debug": false,
    "positionClass": "toast-top-left",
    "onclick": null,
    "showDuration": "5000",
    "hideDuration": "2000",
    "timeOut": "5000",
    "extendedTimeOut": "2000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

$('#name').val('').fancyInput()[0].focus();
function init(str) {
    var input = $('section input').val('')[0],
    s = 'type your name... ✌'.split('').reverse(),
    len = s.length - 1,
    e = $.Event('keypress');
    var initInterval = setInterval(function () {
        if (s.length) {
            var c = s.pop();
            fancyInput.writer(c, input, len - s.length).setCaret(input);
            input.value += c;
        }
        else clearInterval(initInterval);
    }, 10);
}

$(function () {
    $("#name").autocomplete(
    {
        source: 'getdata',
        zIndex: 9999,
        response: function (event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                toastr.error("Sorry, employee not found!", "ERROR");
                fancyInput.clear($('#name').val('')[0].nextElementSibling);
            }
        },
        select: function (event, ui) {
            $("#slide2").show();
                                   
            employeeId = ui.item.id;
            
            IEAgent = checkAgent();
            if (IEAgent == true) {
                setTimeout( $('#name').val(''), 3000);
            } else {
                fancyInput.clear($('#name').val('')[0].nextElementSibling);
            }
            
            var input = $('#name').val('')[0],
            s = ui.item.description.split('').reverse(),
            len = s.length - 1,
            e = $.Event('keypress');
            var initInterval = setInterval(function () {
                if (s.length) {
                    var c = s.pop();
                    fancyInput.writer(c, input, len - s.length).setCaret(input);
                    input.value += c;
                }
                else clearInterval(initInterval);
            }, 10);

            htmlbody.animate({
                scrollTop: $('.slide[data-slide="2"]').offset().top
            }, 1500, 'easeInOutQuint');

            setTimeout(function () {
                $('#password').val('').fancyInput()[0].focus();
                animEffect('#content2', 'fadeInRightBig');
            }, 500);

            setTimeout(function () {
                $("#slide1").hide();
                $('#content2').show();
                $('#password').val('').fancyInput()[0].focus();
            }, 1500);
        }
    })
});

$(function () {
    $('#password').keypress(function (e) {
        if (e.keyCode == 13) {
            $.ajax({
                type: "GET",
                url: "authenticate",
                data: {
                    userid: employeeId,
                    password: document.getElementById('password').value
                },
                success: function (msg) {
                    $('#slide3').show();
                    
                    try {
                       var data = JSON.parse(msg);
                    } catch (err) {
                        toastr.error("Incorrect password!", "ERROR");
                        fancyInput.clear($('#password').val('')[0].nextElementSibling);
                        return;                      
                    }

                    if (data != null & data.length > 0 & data[0].error == '0') {

                        processBtn(data[0].Action, data[0].type);
                        toastr.info("Welcome " + data[0].FullName + "", "INFO");
                        GlobalSupervisor = data[0].FullName;
                        $('#result').html
                        (
                            '<h2>Welcome ' + data[0].FullName + '</h2>'
                        );

                        currentTimeId = data[0].CurrentTimeId;
                    }
                    else {
                        toastr.error("Incorrect password!", "ERROR");
                        fancyInput.clear($('#password').val('')[0].nextElementSibling);
                        return;
                    }

                    htmlbody.animate({
                        scrollTop: $('.slide[data-slide="3"]').offset().top
                    }, 1500, 'easeInOutQuint');

                    document.getElementById('name').value = '';
                    document.getElementById('password').value = '';
                    document.getElementById('reason').value = '1';

                    setTimeout(function () { $("#slide1").hide(); $("#slide2").hide(); }, 1500);
                },
                failure: function (msg) {
                    alert('Employee not found');
                }
            });
        }
    });
});

function processBtn(result, type) {
    //si es cero mostrar el start, si es mostrar el stop
    var element;
    if (result == 0) {
        element = document.getElementById("divstart");
        element.className = "show";
        element = document.getElementById("divstop");
        element.className = "hide";
        setTimeout(function () { animEffect('#divstart', 'fadeInUpBig'); }, 800);
    } else if (result == 1) {
        element = document.getElementById("divstart");
        element.className = "hide";
        element = document.getElementById("divstop");
        element.className = "show";
        setTimeout(function () { animEffect('#divstop', 'fadeInUpBig'); }, 800);
    }
     
    if (type==0){ //type 0 = User Normal
        element = document.getElementById("panelUser");
        element.className = "show";
        element = document.getElementById("panelSupervisor");
        element.className = "hide";
        setTimeout(function () { animEffect('#panelUser', 'fadeInRightBig'); }, 800); 
    } else if(type==1){ //type 1 = User Supervisor
        element = document.getElementById("panelSupervisor");
        element.className = "show";
        element = document.getElementById("panelUser");
        element.className = "hide";
        setTimeout(function () { animEffect('#panelSupervisor', 'fadeInRightBig'); }, 800); 
    }//end if
};

$(function (actionresult) {
    $("#startbtn").click(function () {
        $("#startbtn").disabled = true;
        processTimer("Start");
    })
});

$(function (actionresult) {
    $("#stopbtn").click(function () {
        $("#stopbtn").disabled = true;
        if (document.getElementById('reason').value == 1) {
            toastr.warning("You must select a reason", "WARNING");
            return;
        }

        processTimer("Stop");
        //setTimeout(function () { animEffect('#divstop', 'bounceOutRight'); }, 800);        
    })
});

function processTimer(btn) {
    $.ajax({
        type: "GET",
        url: "savework",
        data: {
            userid: employeeId,
            action: btn,
            reason: btn == "Start" ? "Work" : document.getElementById('reason').value
            // currenttimeid: currentTimeId
        },
        success: function (msg) {

            if (msg.indexOf("[PE]") != -1) {
                toastr.error(msg.substring(4), "INFO");
                return;
            }

            var data = JSON.parse(msg);

            if (data != null) {
                if (data[0].Action == 'Start') {
                    toastr.success("Your time tracking have " + data[0].Action + "ed", "SUCCESS");
                } else { 
                    toastr.success("Your time tracking was " + data[0].Action + "ped", "SUCCESS");
                }
            }

            setTimeout(function () { document.location.reload(); }, 3000);
        },
        failure: function (msg) {
            toastr.error("An error has ocurred while processing action", "ERROR");
            $("#stopbtn").disabled = false;
            $("#startbtn").disabled = false;
        }
    });
}

function checkAgent(){
     if (navigator.appName == 'Microsoft Internet Explorer'){
         return true;
     } else {
         return false;
     }
}

$('#modalSearch').on('keyup', function() {
    var input = $(this);
    if(input.val().length === 0) {
        input.addClass('empty');
    } else {
        input.removeClass('empty');
    }
});

$(function () {
    $("#modalSearch").autocomplete(
    {
        source: 'getdata',
        zIndex: 1500,
        appendTo: '.eventInsForm',
        select: function (event, ui) {
            GlobarUserId  = ui.item.id;
            GlobalEmployeeName = ui.item.value;
            AttendanceData(GlobarUserId);
        }
    })
});


function AttendanceData(id) {
     var timezone = jstz.determine();
     var localTimezone = timezone.name();
      
     $.ajax({
        type: "GET",
        url: "AttendanceData",
        data: {
            userid: id,
            localTimezone: localTimezone
        },
        success: function (msg) {

            if (msg.indexOf("[PE]") != -1) {
                toastr.error(msg.substring(4), "INFO");
                return;  
            }
            
            var data = JSON.parse(msg);
            //var date = new Date(data[0].Time);
            displayDate = data[0].Time.toString("dddd, dd, MMMM, yyyy");
            displayDate = displayDate.substr(0, 10);
            //displayDate = dateFormat(date, "dddd, mmmm, dS, yyyy");
            GlobalId = data[0].Id;
            
            if (data[0].Action == '0') {
                GlobalAction = 0;
                if (data[0].Time == null) {

                       $('p#notificationMessage').html("<i class='fa fa-info-circle' style='color:#369cba;'></i> Employee " 
                                                     + GlobalEmployeeName + " didn't stop work on " + displayDate);

                } else {

                       $('p#notificationMessage').html("<i class='fa fa-info-circle' style='color:#369cba;'></i> Employee " 
                                                     + GlobalEmployeeName + " didn't stop work on " + displayDate);

                   $('#datetimepicker').datetimepicker({
                        format:'Y-m-d H:i',
                        value: data[0].Time,
                        closeOnDateSelect:true 
                    });
                    $('#datetimepicker').attr("disabled", "disabled");
                };             
            } else {
                GlobalAction = 1;
                $('p#notificationMessage').html("<i class='fa fa-info-circle' style='color:#369cba;'></i> Employee " 
                                                              + GlobalEmployeeName + " doesn't have any opened session.");
             };

             $("#ModalSave").removeAttr("disabled");           
        },
        failure: function (msg) {
            toastr.error("An error has ocurred while processing action", "ERROR");
            
        }
    });
 }


$("#link").click(function () {
    $('#modalSearch').val('');
    $('#datetimepicker').val('');
    $('#datetimepicker2').val('');
    $('#notificationMessage').html('');  
    $("#ModalSave").attr("disabled", "disabled");
})

$("#ModalSave").click(function () {
            Startime = $('#datetimepicker').val();
            Stoptime = $('#datetimepicker2').val();

            if (Startime == '' || Stoptime == '' ) {
                toastr.error("Please enter Start Time and Stop Time", "ERROR");
                return;
            };
 
            SaveManualWork(GlobalId,Startime,Stoptime,GlobalAction,GlobarUserId)          

 })

 function SaveManualWork (id,Timein,Timeout,Action,UserID) {
           var timezone = jstz.determine();
           var localTimezone = timezone.name();
            
           $.ajax({
            type: "GET",
            url: "SaveManualWork",
            data: {
                id: id,
                Timein: Timein,
                Timeout: Timeout,
                Supervisor: GlobalSupervisor,
                Action: GlobalAction,
                UserID: UserID,
                localTimezone: localTimezone,
                employeeName: GlobalEmployeeName 
            },
            success: function (msg) {
               msg = msg.replace(/["']/g, "");
               toastr.success(msg, "SUCCESS");
               setTimeout(function () { document.location.reload(); }, 3000);
            },
            failure: function (msg) {
                toastr.error("An error has ocurred while processing action", "ERROR");
               
            }
        });
}
