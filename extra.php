<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <link rel="stylesheet" href="login_style.css">
    <style>
        /* Style for password recovery popup */
        .popup-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            z-index: 1000;
        }
    
        .popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 300px;
        }
    
        .popup-content h2 {
            margin-bottom: 15px;
        }
    
        .popup-content input[type="text"],
        .popup-content input[type="password"],
        .popup-content button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
    
        .popup-content button {
            background-color: #4CAF50; /* Green background color */
            color: white;
            border: none;
            cursor: pointer;
        }
    
        .popup-content button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    
        .close {
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
        }

        /* Hide the security question by default */
        #security-question-container {
            display: none;
        }
    </style>
    
</head>
<body>
    <div class="login-div">
        <div class="logo"><img class="img" src="logo.png" alt="logo"></div>
        <div class="title">Password Recovery</div>
        <form id="recovery-form" class="fields">
            <div class="username">
                <svg class="svg-icon" viewBox="0 0 20 20">
                    <path d="M12.075,10.812c1.358-0.853,2.242-2.507,2.242-4.037c0-2.181-1.795-4.618-4.198-4.618S5.921,4.594,5.921,6.775c0,1.53,0.884,3.185,2.242,4.037c-3.222,0.865-5.6,3.807-5.6,7.298c0,0.23,0.189,0.42,0.42,0.42h14.273c0.23,0,0.42-0.189,0.42-0.42C17.676,14.619,15.297,11.677,12.075,10.812 M6.761,6.775c0-2.162,1.773-3.778,3.358-3.778s3.359,1.616,3.359,3.778c0,2.162-1.774,3.778-3.359,3.778S6.761,8.937,6.761,6.775 M3.415,17.69c0.218-3.51,3.142-6.297,6.704-6.297c3.562,0,6.486,2.787,6.705,6.297H3.415z"></path>
                </svg>
                <input type="email" id="email" name="email" class="user-input" placeholder="Email" required>
            </div>
            <button type="submit" class="signin-button">Recover Password</button>
        </form>
        <div class="link">
            <a href="login.html">Login</a> or <a href="signup.html">Sign Up</a>
        </div>
    </div>

    <!-- Password Recovery Popup -->
    <div id="password-recovery-popup" class="popup-container">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Password Recovery</h2>
            <div id="security-question-container">
                <p id="security-question"></p>
                <input type="text" id="security-answer" placeholder="Enter Security Answer" required>
                <input type="hidden" id="security-question-id" value="">
            </div>
            <button id="verify-security-answer-btn">Verify Answer</button>
        </div>
    </div>

    <!-- OTP Verification Popup -->
    <div id="otp-verification-popup" class="popup-container">
        <div class="popup-content">
            <span class="close" onclick="closeOtpPopup()">&times;</span>
            <h2>OTP Verification</h2>
            <input type="text" id="otp" placeholder="Enter OTP">
            <button id="verify-otp-btn">Verify OTP</button>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to execute Python script after OTP verification
        function executeVerifyPythonScript(email) {
            $.ajax({
                type: 'POST',
                url: 'verify.py',
                data: { email: email },
                success: function (pythonResponse) {
                    // Send the output password to passmail.php
                    $.ajax({
                        type: 'POST',
                        url: 'passmail.php',
                        data: { email: email, password: pythonResponse },
                        success: function (mailResponse) {
                            alert("Password sent through email, use it to login"); // Display email send status
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('Error occurred while sending email.');
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error occurred while executing Python script.');
                }
            });
        }

        $(document).ready(function () {
            // Show password recovery popup when form is submitted
            $('#recovery-form').submit(function (e) {
                e.preventDefault();
                var email = $('#email').val();
                fetchSecurityQuestion(email); // Fetch security question when popup is displayed
            });

            // Function to close the password recovery popup
            window.closePopup = function() {
                $('#password-recovery-popup').css('display', 'none');
            }

            // Function to close the OTP verification popup
            window.closeOtpPopup = function() {
                $('#otp-verification-popup').css('display', 'none');
            }

            // Function to fetch security question via AJAX
            function fetchSecurityQuestion(email) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_security_question.php',
                    data: { email: email },
                    success: function (response) {
                        if (response.trim() !== 'error') {
                            var questionData = JSON.parse(response);
                            $('#security-question').text(questionData.question);
                            $('#security-question-id').val(questionData.id);
                            $('#security-question-container').show();
                            $('#password-recovery-popup').css('display', 'block');
                        } else {
                            alert('Error fetching security question. Please try again.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error occurred. Please try again.');
                    }
                });
            }

            // Verify security answer when Verify Answer button is clicked
            $('#verify-security-answer-btn').click(function (e) {
                e.preventDefault();
                var email = $('#email').val();
                var securityQuestionId = $('#security-question-id').val();
                var securityAnswer = $('#security-answer').val();

                $.ajax({
                    type: 'POST',
                    url: 'verify_security_answer.php',
                    data: { email: email, securityQuestionId: securityQuestionId, securityAnswer: securityAnswer },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            $('#password-recovery-popup').css('display', 'none');
                            sendOTP(email); // Send OTP when popup is displayed
                            //$('#otp-verification-popup').css('display', 'block');
                        } else {
                            alert('Invalid security answer. Please try again.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error occurred. Please try again.');
                    }
                });
            });

            // Function to send OTP via AJAX
            function sendOTP(email) {
                $.ajax({
                    type: 'POST',
                    url: 'recover_password.php',
                    data: { email: email },
                    success: function (response) {
                        //alert(response);
                        if (response.trim() === 'success') {
                            $('#otp-verification-popup').css('display', 'block');
                            //alert('OTP sent successfully to ' + email);
                            //$('#password-recovery-popup').css('display', 'block');
                        } else if (response.trim() === 'error') {
                            alert('Error sending OTP. Please try again.');
                        } else if (response.trim() === 'Not_found') {
                            alert('Email not found. Please check your email address.');
                        } else {
                            alert('Unknown response from the server.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error occurred. Please try again.');
                    }
                });
            }

            // Verify OTP when Verify OTP button is clicked
            $('#verify-otp-btn').click(function (e) {
                e.preventDefault();
                var otp = $('#otp').val();

                $.ajax({
                    type: 'POST',
                    url: 'verify_otp.php',
                    data: { otp: otp },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            $('#otp-verification-popup').css('display', 'none');
                            alert('OTP verification successful. We will send you the password shortly.');
                            var verifiedEmail = $('#email').val();
                            executeVerifyPythonScript(verifiedEmail); // Call Python script after OTP verification
                            // Proceed with OTP generation and email sending here
                            // You can call a function or redirect to another page
                        } else {
                            alert('Invalid OTP. Please try again.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error occurred. Please try again.');
                    }
                });
            });

        });
    </script>
</body>
</html>
