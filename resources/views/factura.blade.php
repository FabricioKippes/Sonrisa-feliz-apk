<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Factura</title>
  <style>
    html,
    body {
      font-family: "Arial", sans-serif;
      font-weight: bold;
    }

    h1,
    h3 {
      text-align: center;
      text-transform: uppercase;
    }

    h3 {
      margin: 0;
      padding: 0;
    }

    table {
      margin: 0;
      padding: 0;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    span,
    p {
      font-size: 11px;
      font-family: "Arial", sans-serif;
      font-weight: bold;
    }

    .contenido {
      font-size: 20px;
    }

    .row-detail td {
      vertical-align: top;
      border-bottom: solid;
      height: 220pt;
    }
  </style>
</head>

<body>
  <div class="contenido">
    <table cellspacing=0 cellpadding="10px" width="100%">
      <tr>
        <td colspan=9 style='border:solid'>
          <h3>ORIGINAL</h3>
        </td>
      </tr>
      <tr>
        <td colspan=3 style='border:solid'>
          <img src="{{asset('')}}image002.jpg">
          <p>Razón Social: Sonrisa Feliz</p>
          <p>Domicilio comercial: French 414</p>
          <p>Condición frente al IVA: Responsable Monotributo</p>
        </td>
        <td colspan=1 valign="top" style='border-bottom:solid;border-right:solid'>
          <h1>C</h1>
          <p class="text-center">COD. 911</p>
        </td>
        <td colspan=5 style='border-bottom:solid;border-right:solid'>
          <h2>FACTURA</h2>
          <p>Punto de Venta: 00001 Comp. Nro: {{ $data['nro_factura'] }}</p>
          <p>Fecha de Emisión: {{ $data['fecha'] }}</p>
          <br>
          <p>CUIT: 27-22333444-0</p>
          <p>Ingresos Brutos: 27-22333444-0</p>
          <p>Fecha Inicio de Actividades: 01/08/2020</p>
        </td>
      </tr>
      <tr>
        <td colspan=3 style='border-left:solid;border-bottom:solid;'>
          <p>Período Facturado Desde: {{ $data['fecha_turno'] }}</p>
        </td>
        <td colspan=2 style='border:none;border-bottom:solid;'>
          <p>Hasta: {{ $data['fecha_turno'] }}</p>
        </td>
        <td colspan=4 style='border-bottom:solid;border-right:solid;'>
          <p>Fecha de Vto. Para el pago: {{ $data['fecha_turno'] }}</p>
        </td>
      </tr>
      <tr style='height:20.5pt'>
        <td colspan=1 style='border:solid;height:20.5pt;background:#BFBFBF;'>
          <p>Código</p>
        </td>
        <td colspan=2 style='border-bottom:solid;border-right:solid;background:#BFBFBF;height:20.5pt'>
          <p>Producto/Servicio</p>
        </td>
        <td colspan=1 style='border-bottom:solid;border-right:solid;background:#BFBFBF;height:20.5pt'>
          <p>Cantidad</p>
        </td>
        <td colspan=1 style='border-bottom:solid;border-right:solid;background:#BFBFBF;height:20.5pt'>
          <p>U. Medida</p>
        </td>
        <td colspan=1 style='border-bottom:solid;border-right:solid;background:#BFBFBF;height:20.5pt'>
          <p>Precio Unit.</p>
        </td>
        <td colspan=1 style='border-bottom:solid;border-right:solid;background:#BFBFBF;height:20.5pt'>
          <p>% Bonif</p>
        </td>
        <td colspan=1 style='border-bottom:solid;border-right:solid;background:#BFBFBF;height:20.5pt'>
          <p>Imp. Bonif</p>
        </td>
        <td colspan=1 style='border-bottom:solid;border-right:solid;background:#BFBFBF;height:20.5pt'>
          <p>Subtotal</p>
        </td>
      </tr>
      <tr class="row-detail">
        <td colspan=1 style='border-left:solid'>
          <p>{{ $data['id'] }}</p>
        </td>
        <td colspan=2>
          <p>{{ $data['concepto'] }}</p>
        </td>
        <td colspan=1>
          <p>1</p>
        </td>
        <td colspan=1>
          <p>unidades</p>
        </td>
        <td colspan=1>
          <p>{{ $data['monto'] }}</p>
        </td>
        <td colspan=1>
          <p>0,00</p>
        </td>
        <td colspan=1>
          <p>0,00</p>
        </td>
        <td colspan=1 style='border-right:solid'>
          <p>{{ $data['monto'] }}</p>
        </td>
      </tr>
      <tr>
        <td colspan=7 style='border-left:solid;border-bottom:solid;'>
          <p class="text-right">Subtotal: $</p>
          <p class="text-right">Importe Otros Tributos: $</p>
          <p class="text-right">Importe Total: $</p>
        </td>
        <td colspan=2 style='border-bottom:solid;border-right:solid;'>
          <p><b>{{ $data['monto'] }}</b></p>
          <p><b>-</b></p>
          <p><b>{{ $data['monto'] }}</b></p>
        </td>
      </tr>
      <tr>
        <td colspan=9 style='border:solid;'>
          <p>&nbsp;</p>
        </td>
      </tr>
      <tr style='height:45.05pt'>
        <td colspan=2 style='height:45.05pt'>
          <img src="{{asset('')}}image004.jpg">
        </td>
        <td colspan=2 style='height:45.05pt'>
          <p>Comprobante Autorizado</p>
        </td>
        <td colspan=1 style='height:45.05pt'>
          <p>Pág 1/1</p>
        </td>
        <td colspan=4 style='height:45.05pt'>
          <p>CAE N*: 000001123334</p>
          <p>Fecha de Vto. De CAE: 22/10/2021</p>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
