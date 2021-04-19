// Execute JavaScript on page load
$(function() {

  var date = moment(new Date());
  var hour = date.tz('America/New_York').format('h');
  var format = date.tz('America/New_York').format('A');
  var weekday = date.tz('America/New_York').format('dddd');
  console.log(date);
  console.log(hour);
  console.log(format);
  console.log(weekday);

    $("#contactForm").on("submit", function(e) {
        // Intercept form submission and submit the form with ajax
        e.preventDefault();
        Swal.fire({
            title: "Just in case",
            text: "an agent needs to contact you, is " + $("#phone").val() + ". the best phone number to reach you? If so, click yes, if not, click update phone number.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            allowOutsideClick: false,
            confirmButtonText: "Update Phone Number!",
            cancelButtonText: "Yes!"
        }).then(function(result) {

            if (result.value) {
                Swal.fire({
                    title: "Submit your Phone Number With Area Code!",
                    input: "text",
                    inputAttributes: {
                        autocapitalize: "off"
                    },

                    confirmButtonText: "Submit",
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: (function(login) {
                        if (login) {
                            console.log(login);
                            sendMailAndTwilioCalling(login);
                        } else {
                            Swal.showValidationMessage("Please Eneter Your Number");
                        }
                    }),
                })
            } else {
                let phoneNo = $("#phone").val();
                sendMailAndTwilioCalling(phoneNo);
            }
        });

        // Prevent submit event from bubbling and automatically submitting the
        function sendMailAndTwilioCalling(phonee) {

            var emaildata;
            var makeLead = 'yes';
            var check = "Reminder : Hi";
            var th = "thankyou2";
            var firstname = $("#first_name").val();
            var lastname = $("#last_name").val();
            var email = $("#email").val();
            var phone = phonee;
            var insurence = $("#insurance").val();
            var insurence1 = $("#insurance1").val();

            e.preventDefault();

            var date = moment(new Date());
            var hour = date.tz('America/New_York').format('h');
            var format = date.tz('America/New_York').format('A');
            var weekday = date.tz('America/New_York').format('dddd');

            if (weekday == "Sunday" || weekday == "sunday") {

                console.log("No time");
                sessionStorage.setItem("nocall", "yes");

            } else {
                if (weekday == "Saturday" || weekday == "saturday") {
                    //In following condition  we can change hours for Saturday.
                    if ((hour >= 9 && format == "AM") || (hour < 1 && format == "PM")) {
                        sessionStorage.setItem("nocall", "no");
                        makeLead = 'no';

                        check = "Hi";
                        th = "thankyou";
                        $.ajax({
                            url: "/call.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                userPhone: "+16788095100",
                                salesPhone: phone,
                                firstname: firstname,
                                lastname: lastname,
                                email: email,
                                insurence: insurence,
                                insurence_type: insurence1
                            }
                        }).done(function(data) {
                            console.log(data.message);
                        }).fail(function(error) {
                            console.log(JSON.stringify(error));
                        });

                    } else {
                        sessionStorage.setItem("nocall", "yes");
                    }
                } else {

                    if ((hour >= 9 && format == "AM") || (hour < 6 && format == "PM")) {
                        //In following condition  we can change hours for every week day.
                        sessionStorage.setItem("nocall", "no");
                        makeLead = 'no';

                        check = "Hi";
                        th = "thankyou";
                        $.ajax({
                            url: "/call.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                userPhone: "+16788095100",
                                salesPhone: phone,
                                firstname: firstname,
                                lastname: lastname,
                                email: email,
                                insurence: insurence,
                                insurence_type: insurence1

                            }
                        }).done(function(data) {
                            console.log(data.message);
                        }).fail(function(error) {
                            console.log(JSON.stringify(error));
                        });
                    } else {
                        console.log("No Time");
                        sessionStorage.setItem("nocall", "yes");
                    }
                }
            }


            emaildata = "<strong><h2>" + check + "</h2> you have a new client:<br/> Name: " + firstname + " " + lastname + " <br/> Phone: " + phone + "<br/> Email: "+ email +" <br/> Insurance : " + insurence + "<br/> Insurance Type : " + insurence1 + "";

            $.ajax({
                url: "/email.php",
                method: "POST",
                dataType: "json",
                data: {
                    maildata: emaildata
                }
            }).done(function(data) {

                window.location.href = "https://www.gaquoters.com/" + th + ".html";

            }).fail(function(error) {

                console.log(JSON.stringify(error));
                window.location.href = "https://www.gaquoters.com/" + th + ".html";

            });

            if( makeLead == 'yes' ) {

                $.ajax({
                    url: "/lead.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        userPhone: "+16788095100",
                        salesPhone: phone,
                        firstname: firstname,
                        lastname: lastname,
                        email: email,
                        insurence: insurence,
                        insurence_type: insurence1
                    }
                }).done(function(data) {
                    console.log(data.message);
                }).fail(function(error) {
                    console.log(JSON.stringify(error));
                });
            }

        }

        // Call our ajax endpoint on the server to initialize the phone call
    });

    $("#subscribe").on("submit", function(e) {

        e.preventDefault();
        $.ajax({
            url: "/email.php",
            method: "POST",
            dataType: "json",
            data: {
                maildata: $("#emailbox").val(),
                recaptcha_response: $("#recaptchaSubscribe").val(),
                subscribe_form: 'yes'
            }
        }).done(function(data) {
            Swal.fire(
                "Good job!",
                "Email submission successful!",
                "success"
            )
        }).fail(function(error) {
            Swal.fire(
                "Good job!",
                "Email submission successful!",
                "success"
            )
        });
    });

    $("#phoneform").on("submit", function(e) {
        // Intercept form submission and submit the form with ajax
        var firstname = "New";
        var lastname = "Client";
        e.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "An agent may need to contact you @ " + $("#phonenumber").val() + ". Is this the best phone number to reach you? If so, click yes, if not,  please click update phone number.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            allowOutsideClick: false,
            cancelButtonText: "Yes!",
            confirmButtonText: "Update Phone Number!"
        }).then(function(result) {
            if (result.value) {
                Swal.fire({
                    title: "Submit your Phone Number With Area Code!",
                    input: "text",
                    inputAttributes: {
                        autocapitalize: "off"
                    },

                    confirmButtonText: "Submit",
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: (function(login) {
                        if (login) {
                            console.log(login);
                            sendMailAndTwilioCalling2(login);

                        } else {
                            Swal.showValidationMessage("Please Eneter Your Number")
                        }

                    }),

                })

            } else {
                let phoneNo = $("#phonenumber").val();
                sendMailAndTwilioCalling2(phoneNo);
            }
        })

        function sendMailAndTwilioCalling2(phonee) {

          var date = moment(new Date());
          var hour = date.tz('America/New_York').format('h');
          var format = date.tz('America/New_York').format('A');
          var weekday = date.tz('America/New_York').format('dddd');

            var th = "thankyou";
            var makeLead = 'yes';

            if (weekday == "Sunday" || weekday == "sunday") {
                console.log("No time");
                sessionStorage.setItem("nocall", "yes");
                window.location.href = "https://www.gaquoters.com/thankyou2.html";
            } else {
                if (weekday == "Saturday" || weekday == "saturday") {
                    //In following condition  we can change hours for Saturday.
                    if ((hour >= 9 && format == "AM") || (hour < 1 && format == "PM")) {
                        sessionStorage.setItem("nocall", "no");
                        makeLead = 'no';

                        $.ajax({
                            url: "/call.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                userPhone: "+16788095100",
                                salesPhone: phonee,
                                firstname: "New",
                                lastname: "Client"

                            }
                        }).done(function(data) {

                            window.location.href = "https://www.gaquoters.com/thankyou.html";

                        }).fail(function(error) {

                            window.location.href = "https://www.gaquoters.com/thankyou.html";

                        });

                    } else {
                        console.log("No time");
                        sessionStorage.setItem("nocall", "yes");
                        window.location.href = "https://www.gaquoters.com/thankyou2.html";
                    }
                } else {
                    //In following condition  we can change hours for Every other week day.
                    if ((hour >= 9 && format == "AM") || (hour < 6 && format == "PM")) {
                        sessionStorage.setItem("nocall", "no");
                        makeLead = 'no';

                        $.ajax({
                            url: "/call.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                userPhone: "+16788095100",
                                salesPhone: phonee,
                                firstname: "New",
                                lastname: "Client"
                            }
                        }).done(function(data) {
                            window.location.href = "https://www.gaquoters.com/thankyou.html";
                        }).fail(function(error) {
                            window.location.href = "https://www.gaquoters.com/thankyou.html";
                        });

                    } else {
                        console.log("No Time");
                        sessionStorage.setItem("nocall", "yes");
                        window.location.href = "https://www.gaquoters.com/thankyou2.html";
                    }
                }
            }

            if ( makeLead == 'yes') {

                $.ajax({
                    url: "/lead.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        userPhone: "+16788095100",
                        salesPhone: phonee,
                        firstname: "New",
                        lastname: "Client"

                    }
                }).done(function(data) {
                    window.location.href = "https://www.gaquoters.com/thankyou.html";
                }).fail(function(error) {
                    window.location.href = "https://www.gaquoters.com/thankyou.html";
                });

            }

        }

    });

});
