<?php

namespace App\Modules\CheckFieldChanges\Controllers;

use Illuminate\Http\Request;
use App\Modules\CheckFieldChanges\Jobs\CheckFieldChanges;
use App\Http\Controllers\Controller;

class CheckFieldChangesController extends Controller
{
    public function update(Request $request)
    {
        // Ambil model class dari request (misalnya: Dokumen::class)
        $modelClass = $request->input('model_class'); // Contoh: 'App\Models\Dokumen'
        $id = $request->input('id'); // ID record yang ingin dipantau

        // Validasi input
        if (!class_exists($modelClass)) {
            return redirect()->back()->with('error', 'Model tidak valid.');
        }

        // Cari record berdasarkan ID dan model
        $record = $modelClass::findOrFail($id);

        // Daftar field yang akan dipantau (diambil dari request atau ditentukan secara statis)
        $fieldsToCheck = $request->input('fields_to_check', []); // Default: []

        // Simpan nilai awal field
        $originalValues = [];
        foreach ($fieldsToCheck as $field) {
            $originalValues[$field] = $record->$field;
        }

        // Update data
        $record->update($request->only($fieldsToCheck));

        // Dispatch job untuk memantau perubahan
        dispatch(new CheckFieldChanges(
            $modelClass,
            $record->id,
            $fieldsToCheck,
            $originalValues
        ));

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data diperbarui.');
    }
}