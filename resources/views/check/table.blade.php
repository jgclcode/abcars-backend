<table>
    <thead>
        <tr>
        <th>Vehiculo</th>
        <th>Vin</th>
        @foreach($items as $item)
        <td>{{ $item['description']}}</td>
        @endforeach
         </tr>
    </thead>
    <tbody>
        @foreach($reporte as $datos)
        <tr>
        <td>{{ $datos['vehiculo']}}</td>
        <td>{{ $datos['vin'] }}</td>

        @for($i=0; $i <=43;$i++) 
 
        <td> {{$datos['respuestas'][$i]}} </td>
   

        @endfor

        </tr>
        @endforeach
    </tbody>
</table>
