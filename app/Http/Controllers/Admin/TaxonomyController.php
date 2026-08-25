<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TechType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Technology;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class TaxonomyController extends Controller
{
    public function categories(): View
    {
        $categories = Category::withCount('projects')->latest()->paginate(15);
        return view('admin.taxonomies.categories', compact('categories'));
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $cat = Category::create($validated);

        AuditService::log('CATEGORY_CREATED', $cat);

        return back()->with('success', "Category '{$cat->name}' created.");
    }

    public function technologies(Request $request): View
    {
        $query = Technology::withCount('projects');
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        $technologies = $query->orderBy('name')->paginate(20)->withQueryString();
        $types = TechType::cases();

        return view('admin.taxonomies.technologies', compact('technologies', 'types'));
    }

    public function storeTechnology(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:technologies,name'],
            'type' => ['required', new Enum(TechType::class)],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $tech = Technology::create($validated);

        AuditService::log('TECHNOLOGY_CREATED', $tech);

        return back()->with('success', "Technology '{$tech->name}' added.");
    }
}
