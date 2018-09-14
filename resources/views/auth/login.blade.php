<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('llave.ico')}}" type="image/ico" />

    <title>Iniciar Sesión</title>

    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <style>
    .login{
   
  background-image:linear-gradient(rgba(200,200,200,0.7),rgba(200,200,200,0.75)), url('/images/Home-common-tools_1920x1080.jpg');
padding-left: 11em;
padding-right: 20em;
font-family:
Georgia, "Times New Roman",
Times, serif; 
color:#14589D;
text-decoration:none;
-webkit-text-shadow: none;
}
    </style>

  </head>

  <body class="login">
    <div class="card">
      <div class="card-body">
        <div class="login_wrapper">
          <div class="animate form login_form">
            <section class="login_content">
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1 style='text-shadow:none'>Inicio de Sesión</h1>
                  <div class="form-group row">
                    <label for="email" style='text-shadow:none' class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>
                      <div class="col-md-8">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="usuario@email.com">

                        <!--@if ($errors->has('email'))
                          <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                          </span>
                        @endif-->
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="password" style='text-shadow:none'class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                      <div class="col-md-8">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        <!--@if ($errors->has('email'))
                          <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                          </span>
                        @endif

                        @if ($errors->has('password'))
                          <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                          </span>
                        @endif-->
                      </div>
                    </div>

                    <div class="form-group row mb-0">
                      <div class="col-md-12 offset-md-4">
                        <button type="submit" class="btn btn-primary">{{ __('Iniciar Sesión') }}
                        </button>

                        <!--<a class="btn btn-link" href="{{ route('password.request') }}">
                          {{ __('Olvidaste la contraseña?') }}
                        </a>-->
                      </div>
                    </div>

                    <div class="form-group row mb-2">
                      <div class="col-md-12 offset-md-4">
                        @if ($errors->has('email'))
                          <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                          </span>
                        @endif

                        @if ($errors->has('password'))
                          <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>