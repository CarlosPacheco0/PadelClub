<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Reservation;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    // Gestión de las canchas (Vista principal)
    public function __invoke()
    {

        $fields = Field::where('status', 1)->orderBy('name', 'desc')->get();
        return view('pages.admin.fields', compact('fields'));
    }

    // Creación de una nueva registro (Guardar)
    public function save(Request $request)
    {
        // 1. Validaciones robustas
        $validated = $request->validate([
            'name'   => 'required|string|max:100',
            'desc'   => 'string|max:250',
            'status' => 'required|in:0,1',
        ]);

        try {

            $field = Field::where('name', $validated['name'])->first();

            if ($field) {
                return back()
                    ->with('info', 'Actualmente ya existe una cancha con el nombre ')
                    ->withInput();
            }

            Field::create([
                'name'  => strtolower($validated['name']),
                'description' => strtolower($validated['desc']),
                'status' => $validated['status'],
            ]);

            return back()->with('success', 'Cancha registrada correctamente');

        } catch (\Exception $e) {
            // En caso de error, regresamos con los inputs para que no se borre el modal
            return back()
                ->with('error', 'Hubo un problema al registrar la cancha. Por favor, intente de nuevo.')
                ->withInput();
        }
    }

    // Eliminar registro
    public function delete(Request $request)
    {
        // 1. Validar que el ID llegue en la petición
        $fieldId = $request->input('field_id');

        if (!$fieldId) {
            return back()->with('error', 'No se proporcionó un identificador válido para la cancha.');
        }

        try {

            $field = Field::find($fieldId);

            if (!$field) {
                return back()->with('error', 'Error al eliminar: La cancha no fue encontrada o ya ha sido eliminada.');
            }

            // 3. Verificar si existen reservas activas (Pendientes o Confirmadas)
            $hasActiveReservations = Reservation::where('field_id', $fieldId)
                ->whereIn('status_reservation', ['pendiente', 'confirmada'])
                ->exists(); // exists() es más rápido que first() si solo queremos validar presencia

            if ($hasActiveReservations) {
                return back()
                    ->with('info', 'No es posible eliminar la cancha "' . $field->name . '" porque tiene reservas pendientes o confirmadas.')
                    ->withInput();
            }

            $field->delete();
            return back()->with('success', 'La cancha "' . $field->name . '" ha sido eliminada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado al intentar eliminar la cancha.');
        }
    }

    // Actualización de registros.
    public function update(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'name' => 'required|string|max:100|unique:fields,name,' . $request->field_id,
            'desc' => 'string|max:250',
            'status' => 'required|in:0,1',
        ]);

        try {

            $field = Field::findOrFail($validated['field_id']);

            $hasActiveReservations = Reservation::where('field_id', $validated['field_id'])
                ->whereIn('status_reservation', ['pendiente', 'confirmada'])
                ->exists();

            if ($hasActiveReservations && $validated['status'] == 0) {
                return back()
                    ->with('info', 'No se puede desactivar la cancha "' . $field->name . '" porque tiene reservas activas. Primero debes gestionarlas.')
                    ->withInput();
            }

            $field->update([
                'name' => $validated['name'],
                'description' => $validated['desc'], // mapear desc a description
                'status' => $validated['status'],
            ]);

            return back()->with('success', 'Cancha actualizada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error al intentar actualizar la información.' . $e);
        }
    }
}
