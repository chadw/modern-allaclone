@extends('layouts.default')
@section('title', 'Zones')

@section('content')
    @if ($zones->isNotEmpty())
        @foreach ($zones as $k => $zone)
            <div class="card bg-base-300 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title text-info/70 text-2xl border-b border-base-content/10">
                        {{ $expansions[$k] ?? 'Expansion' . $k }}</h2>
                    <ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @foreach ($zone as $val)
                            <li>
                                <a href="{{ route('zones.show', $val->id) }}{{ $val->version > 0 ? '?v=' . $val->version : '' }}"
                                    class="block hover:bg-base-200 rounded p-2 transition">
                                    <div class="text-base text-base-content">
                                        {{ $val->long_name }}
                                    </div>
                                    <div class="text-xs text-base-content/50 text-muted uppercase">
                                        {{ $val->short_name }}
                                        @if ($val->version > 0)
                                            <span class="text-accent">(v{{ $val->version }})</span>
                                        @endif
                                        @if ($val->zone_exp_multiplier)
                                            - <span class="text-primary">{{ $val->zone_exp_multiplier * 100 }}% exp</span>
                                        @endif
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    @endif
@endsection
