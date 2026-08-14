<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nombramiento {{ $tramite->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; line-height: 1.5; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 14px; }
        .signature { margin-top: 36px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>ASUNTO: NOMBRAMIENTO</h2>
        </div>

        <p>C. {{ $tramite->expediente->docente->nombre }}</p>
        <p>PRESENTE.</p>

        <p>Por medio del presente se hace de su conocimiento el nombramiento correspondiente al trámite de <strong>{{ $tramite->tipo_tramite }}</strong>, para los efectos administrativos correspondientes.</p>

        <div class="section">
            <h4>DATOS DEL DOCENTE</h4>
            <p><strong>Nombre:</strong> {{ $tramite->expediente->docente->nombre }}</p>
            <p><strong>Centro de trabajo:</strong> {{ $tramite->escuela->nombre }}</p>
            <p><strong>Cargo:</strong> {{ $tramite->expediente->docente->especialidad ?? '-' }}</p>
            <p><strong>Tipo de trámite:</strong> {{ $tramite->tipo_tramite }}</p>
            <p><strong>Fecha:</strong> {{ optional($tramite->fecha_documento)->format('d/m/Y') }}</p>
        </div>

        <p>Por lo anterior, se hace constar la situación correspondiente y se extiende el presente documento para los fines administrativos a que haya lugar.</p>

        <p>Sin otro particular, agradezco de antemano su atención.</p>

        <p style="text-align:center;">Victoria de Durango, Dgo., a {{ $tramite->fecha_documento ? $tramite->fecha_documento->locale('es')->isoFormat('D [de] MMMM [de] YYYY') : '' }}</p>

        <div class="signature" style="text-align:left;">
            <div style="text-align:center;">
                <p style="margin:0;">ATENTAMENTE</p>
                <p style="margin:0;">"POR LA EDUCACIÓN AL SERVICIO DEL PUEBLO"</p>
                <p style="margin:0 0 8px 0;">POR EL COMITÉ EJECUTIVO SECCIONAL</p>
            </div>

            @php
                $count = isset($firmantes) ? $firmantes->count() : 0;
                if($count == 1) {
                    $width = '60%';
                } elseif($count == 2) {
                    $width = '48%';
                } elseif($count > 2) {
                    $width = (int) (100 / $count) . '%';
                } else {
                    $width = '60%';
                }
                $lineMargin = $count == 1 ? '18px' : '8px';
                $signatureTopMargin = $count == 1 ? '48px' : '36px';
            @endphp

            <div style="margin-top:{{ $signatureTopMargin }}; text-align:left;">
                @if($count > 0)
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            @foreach($firmantes as $f)
                                <td style="width:{{ $width }}; vertical-align:top; text-align:center; padding:0 8px;">
                                    <div style="width:{{ $count == 1 ? '60%' : '80%' }}; border-top:1px solid #000; margin:0 auto {{ $lineMargin }} auto;"></div>
                                    <p style="margin:0; font-weight:700">{{ ($f->titulo ?? $f->honorifico ?? '') ? ($f->titulo ?? $f->honorifico ?? '').' ' : '' }}{{ $f->nombre }} {{ $f->apellido }}</p>
                                    <p style="margin:0">{{ $f->cargo }}</p>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                @else
                    <table style="width:100%;">
                        <tr>
                            <td style="width:60%; padding:0 8px;">
                                <div style="border-top:1px solid #000; height:18px; margin-bottom:8px;"></div>
                                <p style="margin:0; font-weight:700">[FIRMANTE]</p>
                                <p style="margin:0">[CARGO]</p>
                            </td>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
