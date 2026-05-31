@extends('layouts.default')
@section('title', 'Discovered Items Leaderboard')

@section('content')
    @if ($leaders->isNotEmpty())
        <div class="flex w-full flex-col">
            <div class="divider uppercase text-xl font-bold text-sky-400">Discovered Items by Top 25 Characters</div>
        </div>
        <div class="border border-base-content/5 overflow-x-auto">
            <table class="table table-auto md:table-fixed w-full table-zebra">
                <thead class="text-xs uppercase bg-base-300">
                    <tr>
                        <th scope="col" class="w-[5%]">#</th>
                        <th scope="col">Character</th>
                        <th scope="col" class="w-[10%]">Total Discovered</th>
                        <th scope="col">Latest Item</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($leaders as $index => $leader)
                <tr>
                    <td>#{{ $index + 1 }}</td>

                    <td>
                        @if(config('everquest.discovered_items.link_character_to_magelo'))
                            <a href="{{ config('everquest.magelo_base_url') . urlencode($leader->char_name) }}">
                                {{ $leader->char_name }}
                            </a>
                        @else
                            {{ $leader->char_name }}
                        @endif
                    </td>

                    <td>{{ $leader->total_discovered }}</td>

                    <td>
                        @if($leader->latest_item)
                            <div class="flex flex-col">
                                <x-item-link
                                    :item_id="$leader->latest_item->id"
                                    :item_name="$leader->latest_item->Name"
                                    :item_icon="$leader->latest_item->icon"
                                    item_class="flex"
                                />

                                <span class="text-xs text-gray-400">
                                    Discovered on {{ $leader->latest_discovery_at?->format('M d, Y H:i') }}
                                </span>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot class="text-xs uppercase bg-base-300">
                    <tr>
                        <td colspan="4" class="text-center text-sm text-base-content/70">
                            Updated every 15 minutes
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
@endsection
