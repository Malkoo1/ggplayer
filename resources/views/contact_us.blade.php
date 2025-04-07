<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Contact Us</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:200,300,400,500,600,700,800,900" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        * {
            font-family: 'Poppins', sans-serif;

        }

        body {
            background: var(--bg, linear-gradient(180deg, #0378A5 0%, #002C3E 100%));
            height: 100vh;
        }

        .form_box_sec {
            background: #1d1d223d;
            padding: 2rem 3rem;
            width: 85%;
            margin-left: auto;
            margin-right: auto;
            border-radius: 20px;
            position: relative;
            margin-bottom: 3rem;
        }

        .form_box_sec .top_form_right {
            position: absolute;
            top: 0;
            right: 0;
        }

        .form_box_sec .bottom_form_left {
            position: absolute;
            bottom: 36%;
            left: 0;
        }

        .form_box_sec h4 {
            font-family: 'Poppins', sans-serif;
            ;
            font-size: 18px;
            font-weight: 600;
            line-height: 32px;
            text-align: left;
            color: #FFFFFF;

        }

        .form_box_sec label {
            font-family: 'Poppins', sans-serif;
            ;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            text-align: left;
            color: #FFFFFF;

        }

        .form_box_sec textarea {
            border: 1px solid #E8EBEF1F;
            background: #F3F4F71F;
            border-radius: 16px;
            font-family: 'Poppins', sans-serif;
            ;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            text-align: left;
            color: #fff;
            margin-bottom: .5rem;
        }

        .form_box_sec textarea:focus,
        .form_box_sec textarea:active,
        .form_box_sec input:focus,
        .form_box_sec input:active {
            outline: none !important;
            box-shadow: 0 0 0 0;
        }

        .form_box_sec input {
            border: 1px solid #E8EBEF1F;
            background: #F3F4F71F;

            border-radius: 16px;
            font-family: 'Poppins', sans-serif;
            ;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            text-align: left;
            height: 42px;
            color: #fff;
            margin-bottom: .5rem;
        }

        .form_box_sec textarea::placeholder,
        .form_box_sec input::placeholder {

            color: #FFFFFF66;

        }

        .learn_more {
            background: #4DC0FF;
            color: #fff;
            padding: 12px 20px 12px 20px;
            border-radius: 16px;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            line-height: 24px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: .4rem;
        }

        .returnmessage {
            color: #4DC0FF;
            margin: 7px 0;
            text-align: center;
            font-weight: 500;
            display: none;
        }

        .empty_notice {
            color: #F52225;
            margin: 7px 0;
            display: none;
            text-align: center;
            font-weight: 500;
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center pt-2">
            <div class="col-lg-6">
                <div class="form_box_sec">
                    <img src="{{ asset('wasset/img/top_form_right.svg') }}" class="top_form_right" alt="">
                    <img src="{{ asset('wasset/img/bottom_form_left.svg') }}" class="bottom_form_left" alt="">
                    <form method="post" class="contact_form" id="contact_form" autocomplete="off">
                        <h4>Drop us a line</h4>
                        <div class="form-group">
                            <label for="">Full name</label>
                            <input type="text" id="name" class="form-control" placeholder="John Doe"
                                aria-describedby="helpId">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" id="email" class="form-control"
                                placeholder="Enter your email address" aria-describedby="helpId">
                        </div>
                        <div class="form-group">
                            <label for="">Subject</label>
                            <input type="text" id="subject" class="form-control" placeholder="Enter Subject name"
                                aria-describedby="helpId">
                        </div>
                        <div class="form-group">
                            <label for="">Message</label>
                            <textarea id="message" cols="30" class="form-control" placeholder="Enter your message..." rows="5"></textarea>
                        </div>
                        <div class="button_box mt-3">
                            <a href="#" id="send_message" class="learn_more border-0 d-block w-100">Submit</a>
                        </div>
                        <div class="returnmessage"
                            data-success="Your message has been received, We will contact you soon."></div>
                        <div class="empty_notice" style="display: none"><span>Please Fill Required Fields</span></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/init.js') }}"></script>
</body>

</html>
