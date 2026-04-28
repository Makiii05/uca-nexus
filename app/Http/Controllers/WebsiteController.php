<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Website;

class WebsiteController extends Controller
{
    public function showWebsite()
    {
        $websites = Website::orderBy("created_at", "asc")->get();
        return view('admin.website', [
            'websites' => $websites
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:carousel,event,mission,vision,goal,program,location,email,contact,social_link,office_hour',
        ];

        $type = $request->input('type');

        if (in_array($type, ['carousel', 'event', 'program'])) {
            $rules['image'] = 'nullable|image|max:5120';
        }
        if (in_array($type, ['carousel', 'event', 'mission', 'vision', 'goal', 'program'])) {
            $rules['title'] = 'nullable|string|max:255';
            $rules['description'] = 'nullable|string';
        }
        if ($type === 'event') {
            $rules['event_date'] = 'nullable|date';
        }
        if ($type === 'location') {
            $rules['embedded_url'] = 'nullable|string';
            $rules['location'] = 'nullable|string|max:500';
        }
        if ($type === 'email') {
            $rules['email'] = 'nullable|email|max:255';
        }
        if ($type === 'contact') {
            $rules['contact'] = 'nullable|string|max:255';
        }
        if ($type === 'social_link') {
            $rules['social_link'] = 'nullable|url|max:500';
        }
        if ($type === 'office_hour') {
            $rules['days'] = 'nullable|string|max:255';
            $rules['is_open'] = 'nullable';
            $rules['start_time'] = 'nullable|date_format:H:i';
            $rules['end_time'] = 'nullable|date_format:H:i';
        }

        $validated = $request->validate($rules);
        $validated['is_open'] = $request->boolean('is_open');

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_url'] = 'images/' . $filename;
        }
        unset($validated['image']);

        Website::create($validated);

        return redirect()->route('admin.website')->with('success', 'Content added successfully.');
    }

    public function update(Request $request, $id)
    {
        $website = Website::findOrFail($id);

        $rules = [
            'type' => 'required|in:carousel,event,mission,vision,goal,program,location,email,contact,social_link,office_hour',
        ];

        $type = $request->input('type');

        if (in_array($type, ['carousel', 'event', 'program'])) {
            $rules['image'] = 'nullable|image|max:5120';
        }
        if (in_array($type, ['carousel', 'event', 'mission', 'vision', 'goal', 'program'])) {
            $rules['title'] = 'nullable|string|max:255';
            $rules['description'] = 'nullable|string';
        }
        if ($type === 'event') {
            $rules['event_date'] = 'nullable|date';
        }
        if ($type === 'location') {
            $rules['embedded_url'] = 'nullable|string';
            $rules['location'] = 'nullable|string|max:500';
        }
        if ($type === 'email') {
            $rules['email'] = 'nullable|email|max:255';
        }
        if ($type === 'contact') {
            $rules['contact'] = 'nullable|string|max:255';
        }
        if ($type === 'social_link') {
            $rules['social_link'] = 'nullable|url|max:500';
        }
        if ($type === 'office_hour') {
            $rules['days'] = 'nullable|string|max:255';
            $rules['is_open'] = 'nullable';
            $rules['start_time'] = 'nullable|date_format:H:i';
            $rules['end_time'] = 'nullable|date_format:H:i';
        }

        $validated = $request->validate($rules);
        $validated['is_open'] = $request->boolean('is_open');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($website->image_url && File::exists(public_path($website->image_url))) {
                File::delete(public_path($website->image_url));
            }
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image_url'] = 'images/' . $filename;
        }
        unset($validated['image']);

        $website->update($validated);

        return redirect()->route('admin.website')->with('success', 'Content updated successfully.');
    }

    public function destroy($id)
    {
        $website = Website::findOrFail($id);

        // Delete image file from disk
        if ($website->image_url && File::exists(public_path($website->image_url))) {
            File::delete(public_path($website->image_url));
        }

        $website->delete();

        return redirect()->route('admin.website')->with('success', 'Content deleted successfully.');
    }
}
