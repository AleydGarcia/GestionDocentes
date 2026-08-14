<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficio {{ $tramite->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; line-height: 1.4; }
        .page { width: 100%; margin: 0 auto; padding: 24px; }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0; font-size: 13px; color: #555; }
        .section { margin-bottom: 18px; }
        .section strong { display: inline-block; width: 170px; }
        .details { border: 1px solid #ccc; border-radius: 8px; padding: 16px; }
        .details p { margin: 8px 0; }
        .footer { margin-top: 32px; font-size: 13px; color: #555; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; }
        .table th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>Oficio de trámite</h1>
            <p>Documento generado para el trámite #{{ $tramite->id }}</p>
        </div>

        <div class="section details">
            <p><strong>Docente:</strong> {{ $tramite->expediente->docente->nombre }}</p>
            <p><strong>RFC:</strong> {{ $tramite->expediente->docente->rfc }}</p>
            <p><strong>CURP:</strong> {{ $tramite->expediente->docente->curp }}</p>
            <p><strong>Escuela:</strong> {{ $tramite->escuela->nombre }}</p>
            <p><strong>Clave escuela:</strong> {{ $tramite->escuela->clave }}</p>
            <p><strong>Director:</strong> {{ $tramite->escuela->director }}</p>
            <p><strong>Tipo de trámite:</strong> {{ $tramite->tipo_tramite }}</p>
            <p><strong>Fecha de inicio:</strong> {{ optional($tramite->fecha_inicio)->format('d/m/Y') }}</p>
            <p><strong>Fecha de fin:</strong> {{ optional($tramite->fecha_fin)->format('d/m/Y') ?? 'No aplica' }}</p>
            <p><strong>Fecha del documento:</strong> {{ optional($tramite->fecha_documento)->format('d/m/Y') }}</p>
        </div>

        @if($tramite->evidencias->isNotEmpty())
            <div class="section">
                <strong>Documentos de evidencia:</strong>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Fecha carga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tramite->evidencias as $evidencia)
                            <tr>
                                <td>{{ $evidencia->nombre_archivo }}</td>
                                <td>{{ optional($evidencia->fecha_carga)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="footer">
            <p>Este documento se generó automáticamente desde el sistema de gestión de trámites.</p>
        </div>
    </div>
</body>
</html>
