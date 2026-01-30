<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Popup\StoreRequest;
use App\Http\Requests\Popup\UpdateRequest;
use App\Models\Language;
use App\Models\Popup;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // first get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['language'] = $language;

        $information['popups'] = $language->announcementPopup()->orderByDesc('id')->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        return view('admin.popup.index', $information);
    }

    /**
     * Show the popup type page to select one of them.
     */
    public function popupType(): View
    {
        return view('admin.popup.popup-type');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type): View
    {
        $information['popupType'] = $type;

        // get all the languages from db
        $information['languages'] = Language::all();

        return view('admin.popup.create', $information);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $imageName = UploadFile::store(public_path('assets/img/popups/'), $request->file('image'));

        Popup::query()->create($request->except('image', 'end_date', 'end_time') + [
            'image' => $imageName,
            'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
            'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null,
        ]);

        session()->flash('success', __('New popup added successfully!'));

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Update the status of specified resource.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $popup = Popup::query()->find($id);

        if ($request->status == 1) {
            $popup->update(['status' => 1]);

            session()->flash('success', __('Popup activated successfully!'));
        } else {
            $popup->update(['status' => 0]);

            session()->flash('success', __('Popup deactivated successfully!'));
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $popup = Popup::query()->findOrFail($id);

        return view('admin.popup.edit', compact('popup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $popup = Popup::query()->find($id);

        if ($request->hasFile('image')) {
            $imageName = UploadFile::update(public_path('assets/img/popups/'), $request->file('image'), $popup->image);
        }

        $popup->update($request->except('image', 'end_date', 'end_time') + [
            'image' => $request->hasFile('image') ? $imageName : $popup->image,
            'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
            'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null,
        ]);

        session()->flash('success', __('Popup updated successfully!'));

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $popup = Popup::query()->find($id);

        @unlink(public_path('assets/img/popups/').$popup->image);

        $popup->delete();

        return redirect()->back()->with('success', __('Popup deleted successfully!'));
    }

    /**
     * Remove the selected or all resources from storage.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $popup = Popup::query()->find($id);

            @unlink(public_path('assets/img/popups/').$popup->image);

            $popup->delete();
        }

        session()->flash('success', __('Popups deleted successfully!'));

        return response()->json(['status' => 'success'], 200);
    }
}
