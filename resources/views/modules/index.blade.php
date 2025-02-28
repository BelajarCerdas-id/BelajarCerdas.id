<h1>Daftar Module</h1>
<ul>
    @foreach ($modules as $module)
        <li>
            @if ($module->is_locked)
                {{ $module->title }} - <span style="color: red;">Terkunci 🔒</span>
            @else
                <a href="{{ route('modules.show', $module->id) }}">{{ $module->title }}</a>
            @endif
        </li>
    @endforeach
</ul>
