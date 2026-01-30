<?php

namespace App\Http\Controllers\Admin\AboutUs;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Features;
use Illuminate\Http\Request;
use Response;
use Validator;

class FeaturesController extends Controller
{
    public function storeFeatures(Request $request): JsonResponse
    {
        $rules = [
            'language_id' => 'required',
            'title' => 'required|max:255',
            'serial_number' => 'required|numeric',
            'icon' => 'required',
            'text' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        }

        Features::query()->create($request->except('language'));

        session()->flash('success', __('New features added successfully!'));

        return Response::json(['status' => 'success'], 200);
    }

    public function updateFeatures(Request $request): JsonResponse
    {
        $rules = [
            'title' => 'required|max:255',
            'text' => 'required',
            'serial_number' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag(),
            ], 400);
        }
        $features = Features::query()->find($request->id);

        $features->update($request->except('language'));

        session()->flash('success', __('Features updated successfully!'));

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id): RedirectResponse
    {
        $Features = Features::query()->find($id);
        $Features->delete();

        return redirect()->back()->with('success', __('Features deleted successfully!'));
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        $ids = $request['ids'];

        foreach ($ids as $id) {
            $Features = Features::query()->find($id);
            $Features->delete();
        }

        session()->flash('success', __('Features deleted successfully!'));

        return Response::json(['status' => 'success'], 200);
    }
}
