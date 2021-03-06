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
    <link href="{{asset('assets/jquery-confirm/dist/jquery-confirm.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css">
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{route('home')}}" class="site_title"> <span> {{ config('app.name') }} </span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="{{ URL::to('/') }}/images/empleados/{{Auth::user()->empleado->avatar}}" alt="..." class="img-circle profile_img">
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
                                @can('ordencompra.index')
                                      <li class="sub_menu"><a href="{{route('ordencompra.index')}}">Orden de Compra</a>
                                      </li>
                                @endcan
                                @can('compra.index')
                                    <li class="sub_menu"><a href="{{route('compra.index')}}">Facturas de Proveedores</a>
                                    </li>
                                @endcan
                                @can('notacreditocompras.index')
                                    <li class="sub_menu"><a href="{{route('notacreditocompra.index')}}">Devolución de Compra</a>
                                    </li>
                                @endcan
                                @can('ordenpago.index')
                                    <li class="sub_menu"><a href="{{route('ordenpago.index')}}">Ordenes de Pago</a>
                                    </li>
                                @endcan
                                <!-- Pablo - comento para usar en otras cosas, y porque esto ya está en Parámetros generales
                                @can('sucursales.index')
                                    <li class="sub_menu"><a href="{{route('sucursales.index')}}">Sucursales</a>
                                    </li>
                                @endcan
                                -->                                
                            </ul>
                          </li>
                          <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li class="sub_menu"><a href="{{route('cuentasporpagar.extractoproveedor')}}">Extracto de Proveedor</a>
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
                                @if(Auth::user()->empleado->esCajero())
                                  @can('gestionCajas.habilitarCaja')
                                    <li class="sub_menu"><a href="{{route('gestionCajas.habilitarCaja')}}">Habilitación de Caja</a></li>
                                  @endcan
                                  <li class="sub_menu"><a href="{{route('cobranza.create')}}">Cobranza</a></li>
                                  @can('gestionCajas.cerrarCaja')
                                    <li class="sub_menu"><a href="{{route('gestionCajas.cerrarCaja')}}">Cierre de Caja</a></li>
                                  @endcan
                                @endif
                            </ul>
                          </li>
                          <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                              <li class="sub_menu"><a href="{{route('cuentasporcobrar.extractocliente')}}">Extracto de Cliente</a>
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
                                    @can('tiposproveedores.index')
                                        <li class="sub_menu"><a href="{{route('tiposproveedores.index')}}">Tipos de Proveedores</a>
                                        </li>
                                    @endcan
                                    @can('proveedores.index')
                                      <li class="sub_menu">
                                        <a href="{{route('proveedores.index')}}">Proveedores</a>
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

                  <li><a><i class="fa fa-cogs"></i> Parámetros Generales <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <!--<li><a href="#level1_1">Level One</a>-->
                        <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            @can('empresas.index')
                              <li class="sub_menu"><a href="{{route('empresa.index')}}">Empresa</a>
                              </li>
                            @endcan
                            
                            @can('timbrados.index')
                              <li class="sub_menu"><a href="{{route('timbrados.index')}}">Timbrados</a>
                              </li>
                            @endcan
                            @can('series.index')
                              <li class="sub_menu"><a href="{{route('series.index')}}">Series</a>
                              </li>
                            @endcan
                            @can('monedas.index')
                              <li class="sub_menu"><a href="{{route('monedas.index')}}">Monedas</a>
                              </li>
                            @endcan
                            @can('cotizaciones.index')
                                <li class="sub_menu"><a href="{{route('cotizaciones.index')}}">Cotizaciones</a>
                                </li>
                            @endcan
                            @can('formasPagos.index')
                              <li class="sub_menu"><a href="{{route('formasPagos.index')}}">Formas de Pago</a>
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
                            @can('conceptocaja.index')
                              <li class="sub_menu"><a href="{{route('conceptocaja.index')}}">Conceptos de Caja</a>
                              </li>
                            @endcan
                            @can('motivoanulacion.index')
                              <li class="sub_menu"><a href="{{route('motivoAnulacion.index')}}">Motivos de Anulaciones</a></li>
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

                  <li><a><i class="fa fa-shield"></i> Gestión de Accesos <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <!--<li><a href="#level1_1">Level One</a>-->
                        <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            @can('tiposEmpleados.index')
                              <li class="sub_menu"><a href="{{route('tiposEmpleados.index')}}">Tipos de Empleados</a>
                              </li>
                            @endcan
                            @can('empleados.index')
                              <li class="sub_menu"><a href="{{route('empleados.index')}}">Empleados</a>
                              </li>
                            @endcan
                            @can('roles.index')
                              <li class="sub_menu"><a href="{{route('roles.index')}}">Roles</a>
                              </li>
                            @endcan
                            @can('users.index')
                              <li class="sub_menu"><a href="{{route('users.index')}}">Usuarios</a>
                              </li>
                            @endcan
                            <li class="sub_menu"><a href="{{route('gestionpermisos.index')}}">Permisos por Rol</a>
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

                  <li><a><i class="fa fa-archive"></i> Stock <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <!--<li><a href="#level1_1">Level One</a>-->
                              <li><a>Formularios<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                @can('depositos.index')
                                      <li class="sub_menu"><a href="{{route('depositos.index')}}">Depositos</a>
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
                                      <li class="sub_menu"><a href="{{route('conceptos.index')}}">Conceptos de Ajustes</a>
                                      </li>
                                    @endcan
                                    @can('ajustesInventarios.index')
                                        <li class="sub_menu"><a href="{{route('ajustesInventarios.index')}}">Ajustes de Inventario</a>
                                        </li>
                                    @endcan
                                    @can('inventarios.index')
                                        <li class="sub_menu"><a href="{{route('inventarios.index')}}">Inventario</a>
                                        </li>
                                    @endcan
                                </ul>
                              </li>
                              <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li class="sub_menu"><a href="{{route('stock.articuloexistencia')}}">Existencia de articulos</a>
                                  </li>
                                  <li class="sub_menu"><a href="{{route('stock.movimientos')}}">Reporte de Movimientos</a>
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
                                    @can('listaprecio.index')
                                        <li class="sub_menu"><a href="{{route('listaPrecios.index')}}">Lista de Precios</a>
                                        </li>
                                    @endcan
                                    <li class="sub_menu"><a href="{{route('listaPrecios.actualizar')}}">Actualizar Precios</a></li>
                                    @if(Auth::user()->empleado->esVendedor())
                                      @can('pedidosVentas.index')
                                        <li class="sub_menu"><a href="{{route('pedidosVentas.index')}}">Toma de Pedido</a>
                                        </li>
                                      @endcan
                                      @can('facturacionVentas.index')
                                        <li class="sub_menu"><a href="{{route('facturacionVentas.index')}}">Facturación</a>
                                        </li>
                                      @endcan
                                      @can('notaCreditoVentas.index')
                                        <li class="sub_menu"><a href="{{route('notaCreditoVentas.index')}}">Nota de Crédito</a>
                                        </li>
                                      @endcan
                                    @endif
                                    @can('anulacioncomprobantes.index')
                                        <li class="sub_menu"><a href="{{route('anulacionComprobantes.index')}}">Anulación de Comprobantes</a></li>
                                    @endcan
                                </ul>
                              </li>
                              <li><a>Reportes<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                  <li class="sub_menu"><a href="{{route('reporte.ver.ventas')}}">Reporte de Ventas</a>
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
              <a data-toggle="tooltip" data-placement="top" title="Cambiar Sucursal" onclick="elegirSucursal()">
                <span class="fa fa-home" aria-hidden="true"></span>
              </a>
              <a  href="{{route('articulos.index')}}" data-toggle="tooltip" data-placement="top" title="Consulta de artículo" onclick="elegirArticulo()">
                <span class="fa fa-search" aria-hidden="true"></span>
              </a>
              <a href="{{route('facturacionVentas.create')}}" data-toggle="tooltip" data-placement="top" title="Facturación">
                <span class="fa fa-shopping-cart" aria-hidden="true"></span>
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
              
              <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="tooltip" data-placement="top" title="Cerrar Sesión" onclick="event.preventDefault();
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
                    <img src="{{ URL::to('/') }}/images/empleados/{{Auth::user()->empleado->avatar}}" alt="">{{ Auth::user()->name }}
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
        @include('modalSeleccionSucursal')
        <!-- /top navigation -->

        <!-- extendemos con todo lo que tenemos -->
        

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <h2><strong>Sucursal:</strong> {{Auth::user()->empleado->sucursalDefault->getNombre()}}</h2>
          </div>
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

    <script src="{{ asset('assets/gentelella/smartresize.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/select2/dist/js/i18n/es.js') }}"></script>

    <script src="{{ asset('assets/jquery-confirm/dist/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>

    <script type="text/javascript">
      function elegirSucursal() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-sucursal').modal('show');
      }

      function elegirArtiiculo() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modal-articulo').modal('show');
      }


      function actualizaSucursal(empleado_id, sucursal_id){
          $.ajax({
              type: "GET",
              url: "{{ url('api/empleados') }}" + '/cambioSucursal/' + empleado_id + '/' + sucursal_id,
              data : $('#modal-sucursal form').serialize(),
              datatype: "json",
              success: function(data){
                $('#modal-sucursal').modal('hide');
                location.reload();
              }
            });
      }
    </script>

    <!-- Para los Script JavaScript necesarios para la utilización de AJAX con el DataTables-->
    @yield('ajax_datatables')

    @yield('otros_scripts')

  </body>
</html>