@extends('layouts.default')
@section('title', 'Pets')

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
                    <th scope="col" width="5%">Lvl</th>
                    <th scope="col" width="30%">Spell</th>
                    <th scope="col" width="10%">Race</th>
                    <th scope="col" width="20%">Class</th>
                    <th scope="col" width="10%">HP</th>
                    <th scope="col" width="10%">AC</th>
                    <th scope="col" width="15%">DMG</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pets as $pet)
                    @if ($pet->pets === null)
                        @continue
                    @endif

                    <tr>
                        <td scope="row">{{ $pet->npcs->level }}</td>
                        <td scope="row">
                            <x-spell-link
                                :spell_id="$pet->id"
                                :spell_name="$pet->name"
                                :spell_icon="$pet->new_icon"
                                spell_class="flex"
                            />
                        </td>
                        <td scope="row">
                            {{ config('everquest.db_races.' . $pet->npcs->race) ?? 'Unknown' }}
                        </td>
                        <td scope="row">{{ config('everquest.classes.' . $pet->npcs->class) }}</td>
                        <td scope="row">{{ $pet->npcs->hp }}</td>
                        <td scope="row">{{ $pet->npcs->AC }}</td>
                        <td scope="row">
                            {{ $pet->npcs->mindmg }}-{{ $pet->npcs->maxdmg }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection
