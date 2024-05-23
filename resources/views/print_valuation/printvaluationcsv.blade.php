<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Vin</th>
            <th>Status</th>
            <th>Nombre Valuador</th>
            <th>Apellidos Valuador</th>
            <th>Email Valuador</th>
            <th>Nombre Técnico</th>
            <th>Apellidos Técnico</th>
            <th>Fecha de cita</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dateNowPrintValuation as $datos)
        <tr>
            <td>{{ $datos['id'] }}</td>
            <td>{{ $datos['vin'] }}</td>
            <td>{{ $datos['status'] }}</td>
            <td>{{ $datos['valuator_name'] }}</td>
            <td>{{ $datos['valuator_lastname'] }}</td>
            <td>{{ $datos['email'] }}</td>
            <td>{{ $datos['technician_name'] }}</td>
            <td>{{ $datos['technician_lastname'] }}</td>
            <td>{{ $datos['created_at'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>