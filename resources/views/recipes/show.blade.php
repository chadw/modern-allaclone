@extends('layouts.default')
@section('title', 'Recipe - ' . ucRomanNumeral($recipe->name))

@section('content')
    @include('partials.recipes.search')

    <div class="flex w-full flex-col">
        <div class="divider uppercase text-xl font-bold text-sky-400">Recipe</div>
    </div>

    <div class="card bg-base-300 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ ucRomanNumeral($recipe->name) }}</h2>
            <p>
                {{ config('everquest.skills.tradeskill')[$recipe->tradeskill] ?? 'Non-Tradeskill' }} -
                <span class="{{ $recipe->trivial >= 300 ? 'text-error font-semibold' : 'text-accent font-semibold' }}">
                    {{ $recipe->trivial }}
                </span> trivial
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Ingredients</h2>
                <div class="space-y-2">
                    @foreach ($components as $val)
                        @if (!$val->item)
                            @continue
                        @endif

                        @php
                            $sources = [];
                            if ($val->custom_is_merchant) {
                                $sources[] = 'Bought';
                            }
                            if ($val->custom_is_drop) {
                                $sources[] = 'Dropped';
                            }
                            if ($val->custom_is_foraged) {
                                $sources[] = 'Foraged';
                            }
                            if (!empty($failCount[$val->item->id])) {
                                $sources[] = $failCount[$val->item->id] . 'x Returned on Fail';
                            }
                            $source = implode(', ', $sources);
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                @if ($val->item?->icon)
                                    <img src="{{ asset('img/items/' . $val->item->icon . '.png') }}"
                                        alt="{{ $val->item->Name }} Icon" loading="lazy" class="w-6 h-6 rounded">
                                @endif
                                <div class="badge badge-sm badge-info">
                                    <strong>{{ $val->componentcount }}x</strong>
                                </div>
                                <span class="font-medium">
                                    <x-item-link
                                        :item_id="$val->item->id"
                                        :item_name="$val->item->Name"
                                    />
                                </span>
                            </div>
                            <div class="text-sm text-right text-gray-500">
                                {{ $source ?: 'Unknown' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Creates</h2>
                @foreach ($success as $val)
                    @if (!$val->item)
                        @continue
                    @endif
                    <div class="flex items-center space-x-3">
                        <x-item-link
                            :item_id="$val->item->id"
                            :item_name="$val->item->Name"
                            :item_icon="$val->item->icon"
                        />
                        <div class="badge badge-sm badge-soft badge-success">
                            Yields <strong>{{ $val->successcount }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="card-title">Container</h2>
                @foreach ($container as $val)
                    @php
                        $isValidContainer = !$val->item && isset($val->custom_container_name);
                    @endphp

                    @if (!$val->item && !$isValidContainer)
                        @continue
                    @endif
                    <div class="flex items-center space-x-3">
                        @if ($val->item?->icon)
                            <img src="{{ asset('img/items/' . $val->item->icon . '.png') }}"
                                alt="{{ $val->item->Name }} Icon" loading="lazy" class="w-6 h-6 rounded" width="24"
                                height="24">
                        @elseif ($isValidContainer && $val->custom_container_icon)
                            <img src="{{ asset('img/items/' . $val->custom_container_icon . '.png') }}"
                                alt="{{ $val->custom_container_name }} Icon" loading="lazy" class="w-6 h-6 rounded"
                                width="24" height="24">
                        @endif
                        <span class="font-medium">
                            @if ($val->item)
                                <x-item-link
                                    :item_id="$val->item->id"
                                    :item_name="$val->item->Name"
                                />
                            @elseif ($isValidContainer)
                                {{ $val->custom_container_name }}
                            @else
                                <span class="text-error">Unknown Container</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
