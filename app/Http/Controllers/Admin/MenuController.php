<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:menus.view', only: ['index']),
            new Middleware('permission:menus.create', only: ['create', 'store']),
            new Middleware('permission:menus.update', only: ['edit', 'update']),
            new Middleware('permission:menus.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $menus = Menu::query()
            ->with('parent')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('nama_menu', 'like', "%{$search}%")
                        ->orWhere('nama_fitur', 'like', "%{$search}%")
                        ->orWhere('route_name', 'like', "%{$search}%")
                        ->orWhere('tag', 'like', "%{$search}%");
                });
            })
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.menus.index', [
            'menus' => $menus,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.menus.create', [
            'menu' => new Menu(['tingkatan_menu' => 'parent', 'urutan' => 10, 'is_active' => true]),
            'parentMenus' => Menu::query()->whereNull('menu_induk_id')->ordered()->get(),
            'permissions' => Permission::query()->where('guard_name', $this->guardName())->orderBy('name')->get(),
            'routeNames' => $this->routeNames(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateMenu($request);

        Menu::create($this->payload($data));

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('admin.menus.edit', [
            'menu' => $menu,
            'parentMenus' => Menu::query()
                ->whereNull('menu_induk_id')
                ->whereKeyNot($menu->id)
                ->ordered()
                ->get(),
            'permissions' => Permission::query()->where('guard_name', $this->guardName())->orderBy('name')->get(),
            'routeNames' => $this->routeNames(),
        ]);
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $this->validateMenu($request);

        if (($data['menu_induk_id'] ?? null) === $menu->id) {
            return back()->withErrors([
                'menu_induk_id' => 'Menu induk tidak boleh menunjuk ke menu itu sendiri.',
            ])->withInput();
        }

        $menu->update($this->payload($data));

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->children()->exists()) {
            return back()->with('error', 'Menu yang masih memiliki submenu tidak dapat dihapus.');
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function validateMenu(Request $request): array
    {
        $guard = $this->guardName();

        return $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],
            'nama_fitur' => ['nullable', 'string', 'max:255'],
            'route_name' => ['nullable', 'string', Rule::in($this->routeNames())],
            'alamat_url' => ['nullable', 'string', 'max:255'],
            'ikon' => ['nullable', 'string', 'max:255'],
            'tingkatan_menu' => ['required', Rule::in(['parent', 'child'])],
            'urutan' => ['required', 'integer', 'min:0'],
            'menu_induk_id' => [
                Rule::requiredIf(fn () => $request->input('tingkatan_menu') === 'child'),
                'nullable',
                'integer',
                Rule::exists('menus', 'id'),
            ],
            'permission_name' => [
                'nullable',
                'string',
                Rule::exists('permissions', 'name')->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
            'tag' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function payload(array $data): array
    {
        if (($data['tingkatan_menu'] ?? 'parent') === 'parent') {
            $data['menu_induk_id'] = null;
        }

        return [
            'nama_menu' => $data['nama_menu'],
            'nama_fitur' => $data['nama_fitur'] ?? null,
            'route_name' => $data['route_name'] ?? null,
            'alamat_url' => filled($data['alamat_url'] ?? null) ? $data['alamat_url'] : null,
            'ikon' => $data['ikon'] ?? null,
            'tingkatan_menu' => $data['tingkatan_menu'],
            'urutan' => $data['urutan'],
            'menu_induk_id' => $data['menu_induk_id'] ?? null,
            'permission_name' => $data['permission_name'] ?? null,
            'tag' => $data['tag'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
    }

    private function guardName(): string
    {
        return (string) config('rbac.guard_name', config('auth.defaults.guard', 'web'));
    }

    private function routeNames(): array
    {
        return collect(RouteFacade::getRoutes()->getRoutesByName())
            ->keys()
            ->filter(fn (string $name) => str($name)->startsWith('admin.'))
            ->sort()
            ->values()
            ->all();
    }
}
