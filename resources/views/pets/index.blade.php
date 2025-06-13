@extends('layouts.default')
@section('title', 'Pets' . ' - ' . $selClassName ?? 'Unknown')

@section('content')
    <label class="select">
        <span class="label">Classes</span>
        <select id="select-pet-class">
            @foreach (config('everquest.pet_classes') as $k => $v)
                <option value="{{ $k }}" {{ request()->route('id') == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </label>
    @if ($pets->isNotEmpty())
    <div class="border border-base-content/5 overflow-x-auto mt-6">
        <table class="table table-auto md:table-fixed table-sm w-full table-zebra">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" class="w-[5%]">Lvl</th>
                    <th scope="col" class="w-[30%]">Spell</th>
                    <th scope="col" class="w-[10%] hidden lg:table-cell">Race</th>
                    <th scope="col" class="w-[20%]">Class</th>
                    <th scope="col" class="w-[10%] hidden md:table-cell">HP</th>
                    <th scope="col" class="w-[10%] hidden lg:table-cell">AC</th>
                    <th scope="col" class="w-[15%] hidden lg:table-cell">DMG</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pets as $pet)
                    @if ($pet->pets === null)
                        @continue
                    @endif

                    <tr>
                        <td scope="row">{{ $pet->npcs->level }}</td>
                        <td>
                            <x-spell-link
                                :spell_id="$pet->id"
                                :spell_name="$pet->name"
                                :spell_icon="$pet->new_icon"
                                spell_class="flex"
                            />
                        </td>
                        <td class="hidden lg:table-cell">
                            {{ config('everquest.db_races.' . $pet->npcs->race) ?? 'Unknown' }}
                        </td>
                        <td>{{ config('everquest.classes.' . $pet->npcs->class) }}</td>
                        <td class="hidden md:table-cell">{{ $pet->npcs->hp }}</td>
                        <td class="hidden lg:table-cell">{{ $pet->npcs->AC }}</td>
                        <td class="hidden lg:table-cell">
                            {{ $pet->npcs->mindmg }}-{{ $pet->npcs->maxdmg }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection
