<table>
    <tr>
        <td>Fecha</td>
        <td>Tipo de Medio</td>
        <td>Medio</td>
        <td>Sección/Programa</td>
        <td>Titular</td>
        <td>Texto</td>
        <td>Equivalencia</td>
        <td>Link</td>
    </tr>
    @foreach($arrayResultados as $resultado )
        <tr>
            <td>{{ date_format(date_create($resultado['fecha']),"Y/m/d") }}</td>
            <td>{{ $resultado['tipoPauta'] }}</td>
            <td>{{ $resultado['nombreMedio'] }}</td>
            <td>{{ $resultado['nombrePrograma'] }}</td>
            <td>{{ $resultado['titular'] }}</td>
            <td>{{ $resultado['texto'] }}</td>
            <td>${{ number_format($resultado['equivalencia'], 2) }}</td>
            <td>{{ $resultado['link'] }}</td>
        </tr>
    @endforeach
</table>