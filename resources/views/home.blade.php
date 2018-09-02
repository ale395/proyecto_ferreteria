<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('llave.ico')}}" type="image/ico" />

    <title> {{ config('app.name') }} </title>

    <!-- Custom Theme Style -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{route('home')}}" class="site_title"><i class="fa fa-recycle"></i> <span> {{ config('app.name') }} </span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="{{ URL::to('/') }}/images/user.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Bienvenido/a</span>
                <h2>{{ Auth::user()->name }}</h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>Menú</h3>
                <ul class="nav side-menu">
                  
                  <li><a><i class="fa fa-shopping-basket"></i> Compras <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <!--<li><a href="#level1_1">Level One</a>-->
                              <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @can('tiposproveedores.index')
                                        <li class="sub_menu"><a href="{{route('tiposproveedores.index')}}">Tipos de Proveedores</a>
                                        </li>
                                    @endcan
                                    @can('monedas.index')
                                        <li class="sub_menu"><a href="{{route('monedas.index')}}">Moneda</a>
                                        </li>
                                    @endcan
                                    @can('sucursales.index')
                                        <li class="sub_menu"><a href="{{route('sucursales.index')}}">Sucursales</a>
                                        </li>
                                    @endcan
                                    
                                </ul>
                              </li>
                              <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li class="sub_menu"><a href="#">Reporte 1</a>
                                  </li>
                                </ul>
                              </li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-money"></i> Cuentas por Cobrar <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <!--<li><a href="#level1_1">Level One</a>-->
                              <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @can('clientes.index')
                                      <li class="sub_menu"><a href="{{route('clientes.index')}}">Clientes</a>
                                      </li>
                                    @endcan
                                    @can('clasificacioncliente.index')
                                      <li class="sub_menu"><a href="{{route('clasificacionclientes.index')}}">Tipos de Clientes</a>
                                      </li>
                                    @endcan
                                </ul>
                              </li>
                              <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li class="sub_menu"><a href="#">Reporte 1</a>
                                  </li>
                                </ul>
                              </li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-usd"></i> Cuentas por Pagar <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <!--<li><a href="#level1_1">Level One</a>-->
                              <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu"><a href="#">Formulario 1</a>
                                    </li>
                                </ul>
                              </li>
                              <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li class="sub_menu"><a href="#">Reporte 1</a>
                                  </li>
                                </ul>
                              </li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-cogs"></i> Parámetros Generales <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <!--<li><a href="#level1_1">Level One</a>-->
                        <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            @can('empresas.index')
                              <li class="sub_menu"><a href="{{route('empresa.index')}}">Empresa</a>
                              </li>
                            @endcan
                            @can('tiposEmpleados.index')
                              <li class="sub_menu"><a href="{{route('tiposEmpleados.index')}}">Tipos de Empleados</a>
                              </li>
                            @endcan
                            @can('monedas.index')
                              <li class="sub_menu"><a href="{{route('monedas.index')}}">Moneda</a>
                              </li>
                            @endcan
                            @can('timbrados.index')
                              <li class="sub_menu"><a href="{{route('timbrados.index')}}">Timbrados</a>
                              </li>
                            @endcan
                            @can('formasPagos.index')
                              <li class="sub_menu"><a href="{{route('formasPagos.index')}}">Formas de Pago</a>
                              </li>
                            @endcan
                            @can('series.index')
                              <li class="sub_menu"><a href="{{route('series.index')}}">Series</a>
                              </li>
                            @endcan
                            @can('bancos.index')
                              <li class="sub_menu"><a href="{{route('bancos.index')}}">Bancos</a>
                              </li>
                            @endcan
                            @can('impuestos.index')
                              <li class="sub_menu"><a href="{{route('impuestos.index')}}">Impuestos</a>
                              </li>
                            @endcan
                            @can('sucursales.index')
                              <li class="sub_menu"><a href="{{route('sucursales.index')}}">Sucursales</a>
                              </li>
                            @endcan
                            @can('familias.index')
                              <li class="sub_menu"><a href="{{route('familias.index')}}">Familias</a>
                              </li>
                            @endcan
                            @can('lineas.index')
                              <li class="sub_menu"><a href="{{route('lineas.index')}}">Lineas</a>
                              </li>
                            @endcan
                            @can('rubros.index')
                              <li class="sub_menu"><a href="{{route('rubros.index')}}">Rubros</a>
                            @endcan
                            @can('cajeros.index')
                              <li class="sub_menu"><a href="{{route('cajeros.index')}}">Cajeros</a>
                              </li>
                            @endcan
                          </ul>
                        </li>
                        <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            <li class="sub_menu"><a href="#">Reporte 1</a>
                            </li>
                          </ul>
                        </li>
                        </ul>
                        </li>

                  <li><a><i class="fa fa-shield"></i> Seguridad <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <!--<li><a href="#level1_1">Level One</a>-->
                        <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <li class="sub_menu"><a href="{{route('gestionpermisos.index')}}">Permisos por Rol</a>
                              </li>
                            @can('roles.index')
                              <li class="sub_menu"><a href="{{route('roles.index')}}">Roles</a>
                              </li>
                            @endcan
                            @can('users.index')
                              <li class="sub_menu"><a href="{{route('users.index')}}">Usuarios</a>
                              </li>
                            @endcan
                          </ul>
                        </li>
                        <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            <li class="sub_menu"><a href="#">Reporte 1</a>
                            </li>
                          </ul>
                        </li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-archive"></i> Stock <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <!--<li><a href="#level1_1">Level One</a>-->
                              <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                @can('depositos.index')
                                      <li class="sub_menu"><a href="{{route('depositos.index')}}">Depositos</a>
                                      </li>
                                    @endcan
                                    @can('cotizaciones.index')
                                      <li class="sub_menu"><a href="{{route('cotizaciones.index')}}">Cotizaciones</a>
                                      </li>
                                    @endcan
                                    @can('articulos.index')
                                      <li class="sub_menu"><a href="{{route('articulos.index')}}">Articulos</a>
                                      </li>
                                    @endcan
                                    @can('familias.index')
                                      <li class="sub_menu"><a href="{{route('familias.index')}}">Familias</a>
                                      </li>
                                    @endcan
                                    @can('lineas.index')
                                      <li class="sub_menu"><a href="{{route('lineas.index')}}">Lineas</a>
                                      </li>
                                    @endcan
                                    @can('rubros.index')
                                      <li class="sub_menu"><a href="{{route('rubros.index')}}">Rubros</a>
                                      </li>
                                    @endcan
                                    @can('unidadesMedidas.index')
                                      <li class="sub_menu"><a href="{{route('unidadesMedidas.index')}}">Unidades de Medidas</a>
                                      </li>
                                    @endcan
                                    @can('conceptoajuste.index')
                                      <li class="sub_menu"><a href="{{route('conceptos.index')}}">Conceptos de Ajuste</a>
                                      </li>
                                    @endcan
                                </ul>
                              </li>
                              <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li class="sub_menu"><a href="#">Reporte 1</a>
                                  </li>
                                </ul>
                              </li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-shopping-cart"></i> Ventas <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <!--<li><a href="#level1_1">Level One</a>-->
                              <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @can('vendedores.index')
                                        <li class="sub_menu"><a href="{{route('vendedores.index')}}">Vendedores</a>
                                        </li>
                                    @endcan
                                    @can('listaprecio.index')
                                        <li class="sub_menu"><a href="{{route('listaPrecios.index')}}">Lista de Precios</a>
                                        </li>
                                    @endcan
                                    @can('pedidosVentas.create')
                                        <li class="sub_menu"><a href="{{route('pedidosVentas.create')}}">Toma de Pedido</a>
                                        </li>
                                    @endcan
                                </ul>
                              </li>
                              <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li class="sub_menu"><a href="#">Reporte 1</a>
                                  </li>
                                </ul>
                              </li>
                    </ul>
                  </li>

                    </ul>
                  </li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="fa fa-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="fa fa-arrows-alt" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="fa fa-eye-slash" aria-hidden="true"></span>
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
              
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
                <span class="fa fa-power-off" aria-hidden="true"></span>
              </a>
              
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{ URL::to('/') }}/images/user.jpg" alt="">{{ Auth::user()->name }}
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-sign-out pull-right"></i>Cerrar Sesión</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- extendemos con todo lo que tenemos -->
        

        <!-- footer content -->
        <footer>
          <div class="line"></div>
        </footer>
        <!-- /footer content -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            @yield('content')
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <!--<footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>-->
        <!-- /footer content -->
      </div>
    </div>

    <main class="py-4">
        @yield('login')
    </main>

    <!-- Custom Theme Scripts -->
    <script src="{{asset('js/app.js')}}"></script>

    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/jquery/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/validator/validator.js') }}"></script>

    <script src="{{ asset('assets/bootstrap/js/ie10-viewport-bug-workaround.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/gentelella/smartresize.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.es.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/i18n/es.js"></script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.es.min.js"></script>

    <!-- Para los Script JavaScript necesarios para la utilización de AJAX con el DataTables-->
    @yield('ajax_datatables')

    @yield('otros_scripts')

  </body>
</html>
