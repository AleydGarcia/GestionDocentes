<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Tramite;

class StoreTramiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Determine whether evidence is required: only for pension/jubilación types
        $evidenceTypes = ['Pensionado', 'Pre pensionado', 'Jubilación', 'Prejubilatorio'];
        $tipo = $this->input('tipo_tramite');
        $requiresEvidence = in_array($tipo, $evidenceTypes, true);

        // If editing an existing tramite that already has evidencia, do not require upload
        $tramiteId = $this->input('tramite_id');
        if ($tramiteId) {
            $existing = Tramite::with('evidencias')->find($tramiteId);
            if ($existing && $existing->evidencias->isNotEmpty()) {
                $requiresEvidence = false;
            }
        }

        // Allow evidence to be provided either as a file upload or as a previously uploaded path (AJAX upload)
        $evidenceRules = $requiresEvidence
            ? ['required_without:evidence_path', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240']
            : ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];

        return [
            'docente_id' => ['required', 'exists:docentes,id'],
            'escuela_id' => ['required', 'exists:escuelas,id'],
            'tramite_id' => ['nullable', 'exists:tramites,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'fecha_documento' => ['required', 'date'],
            'tipo_tramite' => ['required', 'string', 'in:Pensionado,Pre pensionado,Jubilación,Prejubilatorio,Permisos económicos,Permiso sin goce de sueldo,Constancias,Justificantes,Comisión,Dictamen,Resumen clínico'],
            'evidencia' => $evidenceRules,
        ];
    }

    protected function prepareForValidation(): void
    {
        try {
            Log::info('prepareForValidation', [
                'allFiles' => array_map(function($f){
                    return is_object($f) ? (method_exists($f,'getClientOriginalName') ? $f->getClientOriginalName() : get_class($f)) : $f;
                }, $this->allFiles()),
                'inputs' => array_keys($this->all()),
            ]);
        } catch (\Throwable $e) {
            Log::error('prepareForValidation error', ['msg' => $e->getMessage()]);
        }
    }

    public function messages(): array
    {
        return [
            'tipo_tramite.in' => 'Selecciona un tipo de trámite válido.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
