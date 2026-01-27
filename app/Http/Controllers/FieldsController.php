<?php

namespace App\Http\Controllers;

use App\Models\Field;
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

        $field = Field::where('name', $request->name)->first();

        if (!$field) {

            Field::create([
                'name'  => strtolower($request->name),
                'description' => strtolower($request->desc),
                'status' => $request->status,
            ]);
        }

        return redirect()
            ->route('fields')
            ->with('success', 'Cancha registrada correctamente');
    }

    // Eliminar registro
    public function delete(Request $request)
    {
        $field = Field::where('id', $request->field_id)->first();

        if ($field) {
            $field->delete();
        }

        return redirect()
            ->route('fields')
            ->with('success', 'Cancha eliminada correctamente');
    }

    // Actualización de registros.
    public function update(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'name' => 'required|string|unique:fields,name,' . $request->field_id,
            'desc' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        $field = Field::findOrFail($request->field_id);

        $field->update([
            'name' => $request->name,
            'description' => $request->desc, // mapear desc a description
            'status' => $request->status,
        ]);

        return redirect()
            ->route('fields')
            ->with('success', 'Cancha actualizada correctamente');
    }
}
