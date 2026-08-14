@extends('layouts.app')

@section('title', 'Crear Oficio')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-plus-lg"></i> Crear Nuevo Oficio
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="wizard-card p-4 mb-4" style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px;">
            <div class="d-flex flex-wrap gap-3 mb-4">
            <div class="wizard-step active" data-step="1">Docente</div>
            <div class="wizard-step" data-step="2">Escuela</div>
            <div class="wizard-step" data-step="3">Datos del Trámite</div>
            <div class="wizard-step" data-step="4">Resumen / Últimos detalles</div>
        </div>

        <form method="POST" action="{{ route('crear-oficio.submit') }}" id="crearOficioForm" enctype="multipart/form-data">
            @csrf
            @if($tramite)
                <input type="hidden" name="tramite_id" value="{{ $tramite->id }}">
            @endif
            <input type="hidden" name="sign" value="{{ isset($signMode) && $signMode ? 1 : 0 }}">

            <div class="wizard-step-content" data-step="1">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <div class="w-100">
                        <label for="buscar_docente" class="form-label">Buscar docente</label>
                        <input type="search" class="form-control" id="buscar_docente" placeholder="Buscar por nombre, CURP o RFC..." autocomplete="off">
                    </div>
                    <div class="text-end">
                        <a href="{{ route('docentes.create') }}" class="btn btn-outline-primary mt-4">Registrar nuevo docente</a>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>RFC</th>
                                <th>CURP</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="docenteTable">
                            @forelse($docentes as $docente)
                                <tr class="clickable-row docente-row" style="cursor: pointer;" data-search="{{ strtolower($docente->nombre . ' ' . $docente->curp . ' ' . $docente->rfc) }}" data-id="{{ $docente->id }}" data-label="{{ $docente->nombre }} — CURP: {{ $docente->curp }} — RFC: {{ $docente->rfc }}">
                                    <td>{{ $docente->nombre }}</td>
                                    <td>{{ $docente->rfc }}</td>
                                    <td>{{ $docente->curp }}</td>
                                    <td>
                                        <a href="{{ route('docentes.expediente', $docente) }}" class="btn btn-sm btn-outline-info" title="Ver expediente">Ver expediente</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        No hay docentes registrados aún.
                                        <a href="{{ route('docentes.create') }}">Registrar nuevo docente</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="docente_id" id="docente_id" value="{{ old('docente_id', $tramite?->expediente?->docente_id) }}" required>
                <input type="hidden" id="selectedDocenteText" value="{{ old('docente_id') ? 'Docente seleccionado: ' . $docentes->firstWhere('id', old('docente_id'))->nombre : ($tramite?->expediente?->docente?->nombre ? 'Docente seleccionado: ' . $tramite->expediente->docente->nombre : '') }}">
                @error('docente_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="wizard-step-content d-none" data-step="2">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                    <div class="w-100">
                        <label for="buscar_escuela" class="form-label">Buscar escuela</label>
                        <input type="search" class="form-control" id="buscar_escuela" placeholder="Buscar por nombre o clave..." autocomplete="off">
                    </div>
                    <div class="text-end">
                        <a href="{{ route('escuelas.create') }}" class="btn btn-outline-primary mt-4">Registrar nueva escuela</a>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Clave</th>
                                <th>Localidad</th>
                                <th>Director</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="escuelaTable">
                            @forelse($escuelas as $escuela)
                                <tr class="clickable-row escuela-row" style="cursor: pointer;" data-search="{{ strtolower($escuela->nombre . ' ' . $escuela->clave . ' ' . $escuela->director . ' ' . $escuela->localidad) }}" data-id="{{ $escuela->id }}" data-label="{{ $escuela->nombre }} — Clave: {{ $escuela->clave }}">
                                    <td>{{ $escuela->nombre }}</td>
                                    <td>{{ $escuela->clave }}</td>
                                    <td>{{ $escuela->localidad }}</td>
                                    <td>{{ $escuela->director }}</td>
                                    <td>
                                        <a href="{{ route('escuelas.edit', $escuela) }}" class="btn btn-sm btn-outline-primary" title="Editar escuela">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        No hay escuelas registradas aún.
                                        <a href="{{ route('escuelas.create') }}">Registrar nueva escuela</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="escuela_id" id="escuela_id" value="{{ old('escuela_id', $tramite?->escuela_id) }}" required>
                <input type="hidden" id="selectedEscuelaText" value="{{ old('escuela_id') ? 'Escuela seleccionada: ' . $escuelas->firstWhere('id', old('escuela_id'))->nombre : ($tramite?->escuela?->nombre ? 'Escuela seleccionada: ' . $tramite->escuela->nombre : '') }}">
                @error('escuela_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="wizard-step-content d-none" data-step="3">
                <div class="mb-3">
                    <label for="tipo_tramite" class="form-label">Asunto</label>
                    <select class="form-select @error('tipo_tramite') is-invalid @enderror" id="tipo_tramite" name="tipo_tramite" required>
                        <option value="" disabled {{ old('tipo_tramite', $tramite?->tipo_tramite) ? '' : 'selected' }}>Elige un tipo de trámite...</option>
                        @foreach($tiposTramite as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_tramite', $tramite?->tipo_tramite) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_tramite')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $tramite?->fecha_inicio?->format('Y-m-d')) }}" required>
                        @error('fecha_inicio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_fin" class="form-label">Fecha de fin (opcional)</label>
                        <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $tramite?->fecha_fin?->format('Y-m-d')) }}">
                        @error('fecha_fin')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="fecha_documento" class="form-label">Fecha de creación del documento</label>
                    <input type="date" class="form-control @error('fecha_documento') is-invalid @enderror" id="fecha_documento" name="fecha_documento" value="{{ old('fecha_documento', $tramite?->fecha_documento?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    <div class="form-text">Puede ser anterior o posterior a las fechas del permiso.</div>
                    @error('fecha_documento')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                {{-- Firmantes moved to confirmation step (4) --}}
                <div class="mb-3" id="evidenciaContainer">
                    @php $existingE = isset($tramite) && $tramite ? $tramite->evidencias->first() : null; @endphp
                    <label for="evidencia" class="form-label">Documento anterior (evidencia)</label>
                    <div id="evidenciaDisplay" class="d-flex align-items-center gap-2">
                        @if($existingE)
                            <a href="{{ route('tramites.evidencia', $existingE) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Abrir evidencia</a>
                            <span class="text-muted">Archivo subido: {{ $existingE->nombre_archivo }}</span>
                            <button type="button" id="changeEvidenciaBtn" class="btn btn-sm btn-outline-warning ms-2">Cambiar</button>
                        @else
                            <input type="file" class="form-control @error('evidencia') is-invalid @enderror" id="evidencia" name="evidencia" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Requerido para asuntos de pensionado, pre pensionado, jubilación y prejubilatorio. PDF, JPG o PNG, máximo 10 MB.</div>
                        @endif
                    </div>
                    <div id="evidenciaInputWrapper" class="mt-2 d-none">
                        <input type="file" class="form-control @error('evidencia') is-invalid @enderror" id="evidenciaInput" name="evidencia" accept=".pdf,.jpg,.jpeg,.png">
                        <input type="hidden" id="evidence_path" name="evidence_path" value="{{ old('evidence_path') }}">
                        <input type="hidden" id="evidence_name" name="evidence_name" value="{{ old('evidence_name') }}">
                        <div id="uploadStatus" class="form-text text-info"> </div>
                        <div class="form-text">Si subes un nuevo archivo, reemplazará o añadirá la evidencia.</div>
                        <button type="button" id="cancelChangeEvidenciaBtn" class="btn btn-sm btn-outline-secondary mt-2">Cancelar</button>
                    </div>
                    @error('evidencia')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="wizard-step-content d-none" data-step="4">
                <div class="mb-3">
                    <h5>Resumen del Oficio</h5>
                    <p class="text-muted">Confirma los datos antes de generar el oficio.</p>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Docente:</strong> <span id="summaryDocente">-</span></li>
                        <li class="list-group-item"><strong>Escuela:</strong> <span id="summaryEscuela">-</span></li>
                        <li class="list-group-item"><strong>Tipo de trámite:</strong> <span id="summaryTipo">-</span></li>
                        <li class="list-group-item"><strong>Fecha inicio:</strong> <span id="summaryInicio">-</span></li>
                        <li class="list-group-item"><strong>Fecha fin:</strong> <span id="summaryFin">-</span></li>
                        <li class="list-group-item"><strong>Fecha del documento:</strong> <span id="summaryDocumento">-</span></li>
                    </ul>
                </div>
                <div class="mb-3">
                    <h5>Firmantes que aparecerán en el documento</h5>
                    <p class="text-muted">Selecciona los firmantes que deben aparecer en el oficio. El primer firmante es obligatorio.</p>
                    <!-- Preview removed to avoid duplicate signature render issues in step 4 -->
                    <div id="firmantesContainer" class="mt-3 p-3 border rounded" style="background:#fcfcfc;">
                        <div class="mb-2">
                            <select name="firmantes[]" class="form-select firmante-select" required>
                                <option value="" disabled selected>Selecciona primer firmante (obligatorio)</option>
                                    @foreach($firmantes as $f)
                                        <option value="{{ $f->id }}" data-display="{{ ($f->honorifico ? $f->honorifico . ' ' : '') . $f->nombre . ' ' . $f->apellido }}" data-nombre="{{ $f->nombre }}" data-apellido="{{ $f->apellido }}" data-cargo="{{ $f->cargo }}">{{ ($f->honorifico ? $f->honorifico . ' ' : '') }}{{ $f->nombre }} {{ $f->apellido }}{{ $f->cargo ? ' — '.$f->cargo : '' }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="mb-2 d-none optional-firmante">
                            <select name="firmantes[]" class="form-select firmante-select">
                                <option value="" disabled selected>Selecciona firmante (opcional)</option>
                                @foreach($firmantes as $f)
                                    <option value="{{ $f->id }}" data-display="{{ ($f->honorifico ? $f->honorifico . ' ' : '') . $f->nombre . ' ' . $f->apellido }}" data-nombre="{{ $f->nombre }}" data-apellido="{{ $f->apellido }}" data-cargo="{{ $f->cargo }}">{{ ($f->honorifico ? $f->honorifico . ' ' : '') }}{{ $f->nombre }} {{ $f->apellido }}{{ $f->cargo ? ' — '.$f->cargo : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 d-none optional-firmante">
                            <select name="firmantes[]" class="form-select firmante-select">
                                <option value="" disabled selected>Selecciona firmante (opcional)</option>
                                @foreach($firmantes as $f)
                                    <option value="{{ $f->id }}" data-display="{{ ($f->honorifico ? $f->honorifico . ' ' : '') . $f->nombre . ' ' . $f->apellido }}" data-nombre="{{ $f->nombre }}" data-apellido="{{ $f->apellido }}" data-cargo="{{ $f->cargo }}">{{ ($f->honorifico ? $f->honorifico . ' ' : '') }}{{ $f->nombre }} {{ $f->apellido }}{{ $f->cargo ? ' — '.$f->cargo : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="addFirmanteBtn">+ Añadir firma</button>
                            <button type="button" class="btn btn-sm btn-outline-danger d-none" id="removeFirmanteBtn">- Quitar firma</button>
                        </div>
                        @error('firmantes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-secondary" id="wizardPrev">Anterior</button>
                <div>
                    <button type="button" class="btn btn-primary" id="wizardNext">Siguiente</button>
                    <button type="submit" class="btn btn-naranja d-none" id="wizardSubmit">
                        {{ isset($signMode) && $signMode ? 'Firmar y generar oficio' : 'Finalizar trámite' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="successOverlay" class="success-overlay {{ isset($successModal) && $successModal ? '' : 'd-none' }}">
        <div class="success-card">
            <div class="success-icon mb-3">
                <i class="bi bi-check-circle-fill text-success"></i>
            </div>
            <h3>Trámite generado exitosamente</h3>
            <p class="text-muted">El oficio ha sido generado y guardado correctamente.</p>
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Regresar al menú principal</a>
                @if(isset($tramite) && $tramite)
                    <a href="#" id="overlayPrintBtn" class="btn btn-primary">Imprimir / Exportar</a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="section-title">Información</h5>
                    <p style="font-size: 14px; color: #555; line-height: 1.6;">
                        Sigue el flujo de pasos para crear un oficio con datos reales de docentes y escuelas.
                    </p>
                    <ul style="font-size: 13px; color: #666; padding-left: 20px;">
                        <li>Selecciona al docente primero</li>
                        <li>Luego indica la escuela del trámite</li>
                        <li>Completa los datos del trámite</li>
                        <li>Revisa la confirmación antes de enviar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-footer">
        <p>&copy; 2026 Sistema de Gestión de Permisos e Incidencias Docentes.</p>
    </div>
@endsection

@section('extra-js')
    <script>
    (function(){
        // Do not disable file inputs globally — we'll control `required` only.

        const steps = Array.from(document.querySelectorAll('.wizard-step'));
        const contents = Array.from(document.querySelectorAll('.wizard-step-content'));
        const btnNext = document.getElementById('wizardNext');
        const btnPrev = document.getElementById('wizardPrev');
        const btnSubmit = document.getElementById('wizardSubmit');
        let currentStep = {{ $initialStep ?? 1 }};
        // Global references for evidence inputs used across functions
        const evidencia = document.getElementById('evidencia');
        const evidenciaInput = document.getElementById('evidenciaInput');
        const uploadStatus = document.getElementById('uploadStatus');
        // If URL provides a `step` query param, respect it on the client to guarantee correct UI step
        try {
            const params = new URLSearchParams(window.location.search);
            const stepParam = parseInt(params.get('step'));
            if (!Number.isNaN(stepParam) && stepParam >= 1 && stepParam <= 4) {
                currentStep = stepParam;
            }
        } catch (e) {}

        function showStep(step) {
            steps.forEach(stepItem => {
                stepItem.classList.toggle('active', Number(stepItem.dataset.step) === step);
            });
            contents.forEach(content => {
                content.classList.toggle('d-none', Number(content.dataset.step) !== step);
            });
            btnPrev.style.display = step === 1 ? 'none' : 'inline-block';
            btnNext.classList.toggle('d-none', step === 4);
            btnSubmit.classList.toggle('d-none', step !== 4);
            if (step === 4) {
                updateSummary();
            }
        }

        function updateSummary() {
            const selectedDocenteText = document.getElementById('selectedDocenteText').value;
            const selectedEscuelaText = document.getElementById('selectedEscuelaText').value;
            const tipo = document.getElementById('tipo_tramite');
            const inicio = document.getElementById('fecha_inicio');
            const fin = document.getElementById('fecha_fin');
            const fechaDocumento = document.getElementById('fecha_documento');

            document.getElementById('summaryDocente').textContent = selectedDocenteText || '-';
            document.getElementById('summaryEscuela').textContent = selectedEscuelaText || '-';
            document.getElementById('summaryTipo').textContent = tipo.value || '-';
            document.getElementById('summaryInicio').textContent = inicio.value || '-';
            document.getElementById('summaryFin').textContent = fin.value || 'No aplica';
            document.getElementById('summaryDocumento').textContent = fechaDocumento.value || '-';
        }

        function updateEvidenceVisibility() {
            const tipoEl = document.getElementById('tipo_tramite');
            const asunto = tipoEl ? tipoEl.value : '';
            function normalize(s) { return (s || '').normalize('NFD').replace(/\p{Diacritic}/gu, '').toLowerCase().trim().replace(/\s+/g,' '); }
            const asuntoNorm = normalize(asunto);

            // Types that require evidence upload (include dictamen and resumen clinico)
            const evidenciaNeededList = ['pensionado', 'pre pensionado', 'jubilacion', 'prejubilatorio', 'prepensionado', 'dictamen', 'resumen clinico'];
            const requiereEvidencia = evidenciaNeededList.includes(asuntoNorm);

            const container = document.getElementById('evidenciaContainer');
            const evidenciaLocal = document.getElementById('evidencia');
            const evidenciaInputLocal = document.getElementById('evidenciaInput');
            if (container) container.classList.toggle('d-none', !requiereEvidencia);
            if (evidenciaLocal) {
                const evidenciaVisible = !(evidenciaLocal.closest('.d-none') || evidenciaLocal.offsetParent === null);
                evidenciaLocal.required = requiereEvidencia && evidenciaVisible;
            }
            if (evidenciaInputLocal) {
                const evidenciaInputVisible = !(evidenciaInputLocal.closest('.d-none') || evidenciaInputLocal.offsetParent === null);
                evidenciaInputLocal.required = requiereEvidencia && evidenciaInputVisible;
            }

            // firmantes: only required/visible when the trámite produces a document
            // Types that do NOT produce a document / do not require firmantes
            const firmaTypesExcludedNorm = ['dictamen', 'resumen clinico'];
            const requiereDocumento = !firmaTypesExcludedNorm.includes(asuntoNorm);
            const firstFirmante = document.querySelector('#firmantesContainer select[name="firmantes[]"]');
            if (firstFirmante) {
                firstFirmante.required = requiereDocumento;
            }

            // Toggle firmantes UI and print option based on document requirement
            const firmantesContainerEl = document.getElementById('firmantesContainer');
            const addFirmanteEl = document.getElementById('addFirmanteBtn');
            const removeFirmanteEl = document.getElementById('removeFirmanteBtn');
            const overlayPrintBtnEl = document.getElementById('overlayPrintBtn');

            if (firmantesContainerEl) firmantesContainerEl.classList.toggle('d-none', !requiereDocumento);
            if (addFirmanteEl) addFirmanteEl.classList.toggle('d-none', !requiereDocumento);
            if (removeFirmanteEl) removeFirmanteEl.classList.toggle('d-none', !requiereDocumento);
            if (overlayPrintBtnEl) overlayPrintBtnEl.classList.toggle('d-none', !requiereDocumento);

            // Disable selects when not required
            document.querySelectorAll('#firmantesContainer select[name="firmantes[]"]').forEach(s => s.disabled = !requiereDocumento);
        }

        function validateCurrentStep() {
            const currentContent = contents.find(content => Number(content.dataset.step) === currentStep);
            const inputs = Array.from(currentContent.querySelectorAll('select, input'));
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');

            if (currentStep === 1) {
                const docenteId = document.getElementById('docente_id').value;
                if (!docenteId) {
                    alert('Selecciona un docente antes de continuar.');
                    return false;
                }
            }

            if (currentStep === 2) {
                const escuelaId = document.getElementById('escuela_id').value;
                if (!escuelaId) {
                    alert('Selecciona una escuela antes de continuar.');
                    return false;
                }
            }

            for (const element of inputs) {
                // skip firmantes validation when the trámite does not produce a document
                if (element.name === 'firmantes[]') {
                    const asunto = document.getElementById('tipo_tramite').value;
                    function normalize(s) { return (s || '').normalize('NFD').replace(/\p{Diacritic}/gu, '').toLowerCase().trim().replace(/\s+/g,' '); }
                    const asuntoNorm = normalize(asunto);
                    const firmaTypesExcludedNorm = []; // no longer exclude dictamen or resumen clinico
                    if (firmaTypesExcludedNorm.includes(asuntoNorm)) continue;
                }
                if (!element.checkValidity()) {
                    element.reportValidity();
                    alert('Por favor completa correctamente el campo: ' + (element.previousElementSibling?.textContent || element.name));
                    element.focus();
                    return false;
                }
            }

            if (currentStep === 3 && fechaFin.value && fechaInicio.value && fechaFin.value < fechaInicio.value) {
                alert('La fecha de fin debe ser igual o posterior a la fecha de inicio.');
                fechaFin.focus();
                return false;
            }

            return true;
        }

        btnNext.addEventListener('click', () => {
            if (!validateCurrentStep()) {
                return;
            }

            if (currentStep < 4) {
                currentStep += 1;
                if (currentStep === 4) {
                    updateSummary();
                }
                showStep(currentStep);
            }
        });

        btnPrev.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep -= 1;
                showStep(currentStep);
            }
        });

        document.getElementById('crearOficioForm').addEventListener('input', () => {
            updateEvidenceVisibility();
            if (currentStep === 4) {
                updateSummary();
            }
        });

        function filterTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const table = document.getElementById(tableId);
            input.addEventListener('input', () => {
                const search = input.value.trim().toLowerCase();
                Array.from(table.querySelectorAll('tr[data-search]')).forEach(row => {
                    row.style.display = row.dataset.search.includes(search) ? '' : 'none';
                });
            });
        }

        function bindRowSelection(rowSelector, hiddenInputId, selectedTextId) {
            document.querySelectorAll(rowSelector).forEach(row => {
                row.addEventListener('click', event => {
                    if (event.target.closest('a')) {
                        return;
                    }

                    const id = row.dataset.id;
                    const label = row.dataset.label;
                    const hidden = document.getElementById(hiddenInputId);
                    const selectedText = document.getElementById(selectedTextId);

                    // toggle deselection when clicking the already-selected row
                    if (hidden && hidden.value && hidden.value.toString() === id.toString()) {
                        hidden.value = '';
                        if (selectedText) selectedText.value = '';
                        row.classList.remove('table-warning');
                        return;
                    }

                    if (hidden) hidden.value = id;
                    if (selectedText) selectedText.value = label;

                    const table = row.closest('tbody');
                    if (table) {
                        table.querySelectorAll('tr.clickable-row').forEach(r => r.classList.remove('table-warning'));
                    }
                    row.classList.add('table-warning');
                });
            });
        }

        filterTable('buscar_docente', 'docenteTable');
        filterTable('buscar_escuela', 'escuelaTable');
        bindRowSelection('tr.docente-row', 'docente_id', 'selectedDocenteText');
        bindRowSelection('tr.escuela-row', 'escuela_id', 'selectedEscuelaText');
        document.getElementById('tipo_tramite').addEventListener('change', updateEvidenceVisibility);
        updateEvidenceVisibility();
        showStep(currentStep);

        // If the server flashed a success modal (after saving), ensure overlay is visible
        const serverSuccess = {!! json_encode($successModal ?? false) !!};
        if (serverSuccess) {
            const overlay = document.getElementById('successOverlay');
            if (overlay) {
                overlay.classList.remove('d-none');
                try { overlay.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) {}
            }
        }

        // Firmantes UI logic
        const addFirmanteBtn = document.getElementById('addFirmanteBtn');
        const removeFirmanteBtn = document.getElementById('removeFirmanteBtn');
        const optionalFirmantes = Array.from(document.querySelectorAll('.optional-firmante'));

        function refreshFirmanteButtons() {
            const visible = optionalFirmantes.filter(el => !el.classList.contains('d-none')).length;
            addFirmanteBtn.classList.toggle('d-none', visible >= optionalFirmantes.length);
            removeFirmanteBtn.classList.toggle('d-none', visible === 0);
        }

        addFirmanteBtn.addEventListener('click', () => {
            const el = optionalFirmantes.find(e => e.classList.contains('d-none'));
            if (el) {
                el.classList.remove('d-none');
            }
            refreshFirmanteButtons();
        });

        removeFirmanteBtn.addEventListener('click', () => {
            const el = [...optionalFirmantes].reverse().find(e => !e.classList.contains('d-none'));
            if (el) {
                el.querySelector('select').value = '';
                el.classList.add('d-none');
            }
            refreshFirmanteButtons();
        });

        refreshFirmanteButtons();

        // If the controller flashed selected firmantes (after saving), preselect them so preview shows inside the success overlay
        const preselectedFirmantes = {!! json_encode($selectedFirmantes ?? []) !!};
        if (preselectedFirmantes && preselectedFirmantes.length) {
            const selects = Array.from(document.querySelectorAll('select[name="firmantes[]"]'));
            for (let i = 0; i < preselectedFirmantes.length && i < selects.length; i++) {
                const s = selects[i];
                // if select is inside an optional container that is hidden, unhide it
                const optParent = s.closest('.optional-firmante');
                if (optParent && optParent.classList.contains('d-none')) {
                    optParent.classList.remove('d-none');
                }
                s.value = preselectedFirmantes[i];
            }
            refreshFirmanteButtons();
        }

        function collectSelectedFirmantes() {
            const selects = Array.from(document.querySelectorAll('select[name="firmantes[]"]'));
            return selects.map(s => s.value).filter(v => v && v !== '');
        }

        // Evidence change controls: keep evidence locked (display only) unless user clicks 'Cambiar'
        const changeEvidenciaBtn = document.getElementById('changeEvidenciaBtn');
        const evidenciaInputWrapper = document.getElementById('evidenciaInputWrapper');
        const evidenciaDisplay = document.getElementById('evidenciaDisplay');
        const cancelChangeEvidenciaBtn = document.getElementById('cancelChangeEvidenciaBtn');
        // evidenciaInput is defined in the outer scope; reuse it here
        if (changeEvidenciaBtn) {
            changeEvidenciaBtn.addEventListener('click', () => {
                evidenciaInputWrapper.classList.remove('d-none');
                evidenciaDisplay.classList.add('d-none');
                if (evidenciaInput) { evidenciaInput.required = true; }
            });
        }
        if (cancelChangeEvidenciaBtn) {
            cancelChangeEvidenciaBtn.addEventListener('click', () => {
                evidenciaInputWrapper.classList.add('d-none');
                evidenciaDisplay.classList.remove('d-none');
                if (evidenciaInput) { evidenciaInput.required = false; evidenciaInput.value = ''; }
            });
        }

        // AJAX upload when the user selects a file so final submit doesn't rely on multipart
        async function uploadEvidence(file, statusEl) {
            if (!file) return null;
            const form = new FormData();
            form.append('file', file);
            form.append('_token', '{{ csrf_token() }}');
            try {
                statusEl.textContent = 'Subiendo...';
                const res = await fetch('{{ route('crear-oficio.upload_evidencia') }}', { method: 'POST', body: form, credentials: 'same-origin' });
                const json = await res.json();
                if (json && json.ok) {
                    document.getElementById('evidence_path').value = json.path;
                    document.getElementById('evidence_name').value = json.originalName;
                    statusEl.textContent = 'Subida completada: ' + json.originalName;
                    return json;
                } else {
                    statusEl.textContent = 'Error en la subida';
                    return null;
                }
            } catch (e) {
                statusEl.textContent = 'Error en la subida';
                return null;
            }
        }

        if (evidenciaInput) {
            evidenciaInput.addEventListener('change', (ev) => {
                const f = ev.target.files && ev.target.files[0];
                const statusEl = document.getElementById('uploadStatus');
                if (f) uploadEvidence(f, statusEl);
            });
        }
        if (evidencia) {
            evidencia.addEventListener('change', (ev) => {
                const f = ev.target.files && ev.target.files[0];
                // ensure the hidden wrapper is visible so validation understands there's an upload
                const statusEl = document.getElementById('uploadStatus');
                if (f) uploadEvidence(f, statusEl);
            });
        }

        // Signature preview intentionally removed from step 4 to avoid duplication

        function openPrintWithFirmantes(tramiteId) {
            const asunto = document.getElementById('tipo_tramite')?.value || '';
            function normalize(s) { return (s || '').normalize('NFD').replace(/\p{Diacritic}/gu, '').toLowerCase().trim().replace(/\s+/g,' '); }
            const asuntoNorm = normalize(asunto);

            // Only these require evidence before printing
            const requiereEvidenciaNorm = ['pensionado','pre pensionado','prepensionado','jubilacion','prejubilatorio'];
            const asuntoSimple = asuntoNorm.replace(/\s+/g,' ');
            // server-side flag indicating whether the tramite (as rendered) has evidencia saved
            const hasEvidencia = {!! json_encode(isset($tramite) && $tramite && $tramite->evidencias && $tramite->evidencias->isNotEmpty() ? true : false) !!};

            // If this tipo requires evidencia and the saved tramite lacks it, open the editor so user can upload
            if (requiereEvidenciaNorm.includes(asuntoSimple) && !hasEvidencia) {
                if (confirm('Este trámite requiere evidencia subida antes de poder imprimir. ¿Abrir el editor en una nueva pestaña para subir la evidencia?')) {
                    const wizardEditUrl = `{{ route('crear-oficio') }}?tramite=${tramiteId}&step=3`;
                    window.open(wizardEditUrl, '_blank');
                }
                return;
            }

            // Otherwise open the wizard at step 4 so user can confirm firmantes before printing
            const wizardUrl = `{{ route('crear-oficio') }}?tramite=${tramiteId}&step=4`;

            // Navigate to the wizard's summary (step 4) in the same tab so the user can review and then print
            window.location.href = wizardUrl;
        }

        
        // Print directly from the success overlay: persist selected firmantes then redirect to print preview
        function printOverlayDirect(tramiteId) {
            const selected = collectSelectedFirmantes();
            const prepareUrl = `{{ url('/dashboard/tramites') }}/${tramiteId}/prepare-print`;
            const printUrl = `{{ url('/dashboard/tramites') }}/${tramiteId}/imprimir`;
            const formData = new FormData();
            selected.forEach(id => formData.append('firmantes[]', id));
            formData.append('_token', '{{ csrf_token() }}');

            fetch(prepareUrl, { method: 'POST', body: formData, credentials: 'same-origin' })
                .then(res => res.json())
                .then(data => {
                    if (data && data.ok) {
                        window.location.href = printUrl;
                    } else {
                        const params = selected.map(id => `firmantes[]=${encodeURIComponent(id)}`).join('&');
                        window.location.href = printUrl + (params ? ('?' + params) : '');
                    }
                })
                .catch(() => {
                    const params = selected.map(id => `firmantes[]=${encodeURIComponent(id)}`).join('&');
                    window.location.href = printUrl + (params ? ('?' + params) : '');
                });
        }

        const overlayPrintBtn = document.getElementById('overlayPrintBtn');
        if (overlayPrintBtn) {
            overlayPrintBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const tramiteId = {{ $tramite->id ?? 'null' }};
                if (!tramiteId) return alert('No hay trámite seleccionado para imprimir.');
                printOverlayDirect(tramiteId);
            });
        }

        // Prevent form submit when evidence is required but no file was attached.
        document.getElementById('crearOficioForm').addEventListener('submit', function (ev) {
            const tipo = document.getElementById('tipo_tramite')?.value || '';
            function normalize(s) { return (s || '').normalize('NFD').replace(/\p{Diacritic}/gu, '').toLowerCase().trim().replace(/\s+/g,' '); }
            const tipoNorm = normalize(tipo);
            const requiereEvidenciaNorm = ['pensionado','pre pensionado','prepensionado','jubilacion','prejubilatorio'];

            // If evidence is required for this type, ensure a file is present either in the visible input or the hidden replacement input
            if (requiereEvidenciaNorm.includes(tipoNorm)) {
                const evidenciaEl = document.getElementById('evidencia');
                const evidenciaInputEl = document.getElementById('evidenciaInput');
                const hasFile = (evidenciaEl && evidenciaEl.files && evidenciaEl.files.length > 0) || (evidenciaInputEl && evidenciaInputEl.files && evidenciaInputEl.files.length > 0);
                // Also allow if server-side tramite already has evidencia (the page renders link in that case)
                const serverHasEvidencia = {!! json_encode(isset($tramite) && $tramite && $tramite->evidencias && $tramite->evidencias->isNotEmpty() ? true : false) !!};
                if (!hasFile && !serverHasEvidencia) {
                    ev.preventDefault();
                    // Reveal the input so user can attach file
                    const evidenciaInputWrapper = document.getElementById('evidenciaInputWrapper');
                    const evidenciaDisplay = document.getElementById('evidenciaDisplay');
                    if (evidenciaInputWrapper) evidenciaInputWrapper.classList.remove('d-none');
                    if (evidenciaDisplay) evidenciaDisplay.classList.add('d-none');
                    if (evidenciaInputEl) { evidenciaInputEl.required = true; evidenciaInputEl.focus(); }
                    alert('Este tipo de trámite requiere que subas una evidencia antes de finalizar. Por favor selecciona un archivo y vuelve a enviar.');
                    return false;
                }
            }
        });
    })();
    </script>
@endsection
