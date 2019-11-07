<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        {{-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> --}}
        <link  href="{{asset('assets/mainTemplate/fonts-googleapis.css')}}" rel="stylesheet" />


        <!-- Styles -->

        {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/login_v2/css/main.css')}}"> --}}
        
        <style>
            .wrap-login100 {
                width: 900px;
                height: 100px;
                border-radius: 10px;
                overflow: hidden;
                padding: 55px 55px 37px 55px;
                
                background-color: rgba(10,10,10,.68);
                //background: #9152f8;/
                background: -webkit-linear-gradient(top, #7579ff, #b224ef);
                background: -o-linear-gradient(top, #7579ff, #b224ef);
                background: -moz-linear-gradient(top, #7579ff, #b224ef);
                background: linear-gradient(top, #7579ff, #b224ef);
            }
            /* @media (max-width: 576px) {
                .wrap-login100 {
                    padding: 55px 15px 31px 15px;
                }
            } */

            html, body {
                background-image: url('{{ asset('assets/images/home2.jpg')}}');
                /* background-color:darkslateblue; */
                color: white;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                /* background-repeat:no-repeat; */
                background-size:cover;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: white;
                padding: 0 25px;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body >
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Continue as {{ Auth::user()->name }}</a>
                    @else
                        {{-- <a href="{{ route('login') }}">Login</a> --}}
                            <a class="txt1" href="{{route('admin.login')}}">
                                Admin Login
                            </a>

                        @if (Route::has('register'))
                            {{-- <a href="{{ route('register') }}">Register</a> --}}
                        @endif
                    @endauth
                </div>
            @endif

            {{-- <div class="content"> --}}
            <div class="wrap-login100">
                <span style="font-size:60px;color:springgreen">
                    Alfredo's Restaurant Project
                </span>

                {{-- <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div> --}}
            </div>
        </div>
    </body>
</html>
