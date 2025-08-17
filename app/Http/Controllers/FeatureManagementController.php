<?php

namespace App\Http\Controllers;

use App\Events\ManageFeatures;
use App\Models\Features;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeatureManagementController extends Controller
{
    public function FeaturesManagementView()
    {
        return view('managements.features-management');
    }

    public function FeaturesManagementStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_fitur' => 'required|unique:features,nama_fitur',
        ], [
            'nama_fitur.required' => 'Nama fitur tidak boleh kosong.',
            'nama_fitur.unique' => 'Nama fitur telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $features = Features::create([
            'nama_fitur' => $request->nama_fitur,
        ]);

        broadcast(new ManageFeatures($features))->toOthers();

        return response()->json([
            'status' => 'success',
            'data' => $features,
            'message' => 'Nama fitur berhasil ditambahkan.',
        ]);
    }

    public function FeaturesManagementUpdate(Request $request, $featureId)
    {
        $getFeature = Features::findOrFail($featureId);

        $validator = Validator::make($request->all(), [
            'nama_fitur' => 'required|unique:features,nama_fitur',
        ], [
            'nama_fitur.required' => 'Nama fitur tidak boleh kosong.',
            'nama_fitur.unique' => 'Nama fitur telah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $getFeature->update([
            'nama_fitur' => $request->nama_fitur,
        ]);

        broadcast(new ManageFeatures($getFeature))->toOthers();

        return response()->json([
            'status' => 'success',
            'data' => $getFeature,
            'message' => 'Nama fitur berhasil diperbarui.',
        ]);
    }

    public function paginateFeaturesList()
    {
        $getFeatures = Features::paginate(10);

        return response()->json([
            'data' => $getFeatures->items(),
            'links' => (string) $getFeatures->links(),
        ]);
    }
}