<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Support\Facades\Storage;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Models\Signature;

class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! bouncer()->hasPermission('settings.signatures')) {
            abort(403, 'This action is unauthorized.');
        }

        $signatures = Signature::all();

        return view('admin::settings.signatures.index', compact('signatures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        if (! bouncer()->hasPermission('settings.signatures')) {
            abort(403, 'This action is unauthorized.');
        }

        $this->validate(request(), [
            'name'       => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'image.*'    => 'required|mimes:bmp,jpeg,jpg,png,webp,svg', // Allow array input
        ]);

        $data = request()->all();

        if (request()->hasFile('image')) {
            // media.images sends an array of files, we take the first one
            $file = current(request()->file('image'));
            $path = $file->store('signatures');

            Signature::create([
                'name'       => $data['name'],
                'owner_name' => $data['owner_name'],
                'image_path' => $path,
            ]);

            session()->flash('success', 'Signature created successfully.');
        } else {
            session()->flash('error', 'Please upload an image.');
        }

        return redirect()->route('admin.settings.signatures.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signature $signature)
    {
        if (! bouncer()->hasPermission('settings.signatures')) {
            abort(403, 'This action is unauthorized.');
        }

        Storage::delete($signature->image_path);
        $signature->delete();

        session()->flash('success', 'Signature deleted successfully.');

        return redirect()->route('admin.settings.signatures.index');
    }

    /**
     * Set the specified signature as active.
     */
    public function setActive(Signature $signature)
    {
        if (! bouncer()->hasPermission('settings.signatures')) {
            abort(403, 'This action is unauthorized.');
        }

        // Deactivate all other signatures
        Signature::where('id', '!=', $signature->id)->update(['is_active' => false]);

        // Activate the selected one
        $signature->update(['is_active' => true]);

        session()->flash('success', 'Signature has been set as active.');

        return redirect()->route('admin.settings.signatures.index');
    }
}
