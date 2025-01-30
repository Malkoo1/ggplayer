<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <!-- Page Title  -->
    <title>Login | Tutor Ai</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}?ver=3.0.3">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}?ver=3.0.3">
</head>

<body class="nk-body bg-white npc-default pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class=" nk-split-page nk-split-lg">
                        <div class="nk-split-content nk-block-area nk-block-area-column bg-white">

                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo text-center pb-5">
                                    <a href="html/index.html" class="logo-link">
                                         <img class="logo-light logo-img logo-img-lg" src="{{ asset('assets/images/logo-dark.png') }}?ver=3.0.3" srcset="{{ asset('assets/images/logo-dark.png') }}?ver=3.0.3" alt="logo">
                                        <img class="logo-dark logo-img logo-img-lg" src="{{ asset('assets/images/logo-dark.png') }}?ver=3.0.3" srcset="{{ asset('assets/images/logo-dark.png') }}?ver=3.0.3" alt="logo-dark">
                                    </a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Sign-In</h5>
                                        <div class="nk-block-des">
                                            <p>Access the Tutor Ai panel using your email and Password.</p>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                @if(Session::has('success'))
                                <div class="alert alert-success mt-4"><strong>{{Session::get('success')}}</strong></div>
                                @endif
                                @if(Session::has('fail'))
                                    <div class="alert alert-danger mt-4"><strong>{{Session::get('fail')}}</strong></div>
                                @endif
                                <form action="{{ url('/login/auth') }}" method="post" class="form-validate is-alter" autocomplete="off">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Email</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="email" class="form-control form-control-lg" name="email" required id="email-address" placeholder="Enter your email address">
                                        </div>
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Password</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input autocomplete="new-password" type="password" name="password" class="form-control form-control-lg" required id="password" placeholder="Enter your passcode">
                                        </div>
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
                                    </div>
                                </form><!-- form -->



                            </div><!-- .nk-block -->

                        </div>
                    </div><!-- .nk-split -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js') }}?ver=3.0.3"></script>
    <script src="{{ asset('assets/js/scripts.js') }}?ver=3.0.3"></script>
    <!-- select region modal -->


</html>
