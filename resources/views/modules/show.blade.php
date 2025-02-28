<h1>{{ $module->title }}</h1>
<div>
    {!! $module->content !!}
</div>
<form action="{{ route('modules.complete', $module->id) }}" method="POST">
    @csrf
    <input type="text" name="user_id" value="{{ $userId->id }}">
    <button type="submit">Tandai sebagai Selesai</button>
</form>
