@foreach ($menus as $menu)
    @php
        $menuUrl = $menu->resolvedUrl();
        $path = trim((string) parse_url($menuUrl, PHP_URL_PATH), '/');
        $isClickable = $menuUrl !== 'javascript:void(0)';
        $isActive = filled($menu->route_name)
            ? request()->routeIs($menu->route_name)
            : $isClickable && $path !== '' && (request()->is($path) || request()->is($path . '/*'));
        $isOpen = $menu->children->contains(function ($child) {
            if (filled($child->route_name) && request()->routeIs($child->route_name)) {
                return true;
            }

            $childPath = trim((string) parse_url($child->resolvedUrl(), PHP_URL_PATH), '/');

            return $child->resolvedUrl() !== 'javascript:void(0)' &&
                $childPath !== '' &&
                (request()->is($childPath) || request()->is($childPath . '/*'));
        });
        $href = \Illuminate\Support\Str::startsWith($menuUrl, ['http://', 'https://', 'javascript:'])
            ? $menuUrl
            : url($menuUrl);
    @endphp

    <li class="nxl-item {{ $menu->children->isNotEmpty() ? 'nxl-hasmenu' : '' }}">
        <a href="{{ $menu->children->isNotEmpty() ? 'javascript:void(0);' : $href }}"
            class="nxl-link {{ $isActive || $isOpen ? 'active' : '' }}">
            <span class="nxl-micon"><i class="{{ $menu->ikon ?: 'feather-circle' }}"></i></span>
            <span class="nxl-mtext">{{ $menu->nama_menu }}</span>
            @if ($menu->children->isNotEmpty())
                <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
            @endif
        </a>

        @if ($menu->children->isNotEmpty())
            <ul class="nxl-submenu" @if ($isOpen) style="display: block;" @endif>
                @include('components.admin.menu-items', ['menus' => $menu->children])
            </ul>
        @endif
    </li>
@endforeach
