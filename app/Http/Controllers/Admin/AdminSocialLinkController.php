<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class AdminSocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::orderBy('position')->get();
        return view('admin.social_links.index', compact('socialLinks'));
    }

    public function create()
    {
        return view('admin.social_links.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'icon_class' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'position' => 'nullable|integer',
            'open_in_new_tab' => 'boolean',
            'active' => 'boolean',
        ]);

        SocialLink::create($validated);

        return redirect()->route('admin.social-links.index')->with('success', 'Ссылка добавлена');
    }

    public function show(SocialLink $socialLink)
    {
        return view('admin.social_links.show', compact('socialLink'));
    }

    public function edit(SocialLink $socialLink)
    {
        return view('admin.social_links.edit', compact('socialLink'));
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'icon_class' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'position' => 'nullable|integer',
            'open_in_new_tab' => 'boolean',
            'active' => 'boolean',
        ]);

        $socialLink->update($validated);

        return redirect()->route('admin.social-links.index')->with('success', 'Ссылка обновлена');
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();
        return redirect()->route('admin.social-links.index')->with('success', 'Ссылка удалена');
    }
}
