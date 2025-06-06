@props([
    'id' => 'stat1',
    'label' => 'Stats',
    'stats' => [],
    'selected_stat' => '',
    'selected_stat_comp' => '>=',
    'stat_value' => '',
])

<div class="flex flex-nowrap items-end join w-full">
    <div class="flex flex-col grow">
        <label class="select w-full">
            <span class="label">Stats</span>
            <select id="{{ $id }}" name="{{ $id }}" class="select join-item w-full">
                <option value="">-</option>
                @foreach ($stats as $stat_k => $stat_v)
                    <option value="{{ $stat_k }}" {{ $selected_stat == $stat_k ? 'selected' : '' }}>
                        {{ $stat_v }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <div class="shrink-0">
        <label class="select">
            <select id="{{ $id }}comp" name="{{ $id }}comp" class="select join-item min-w-[60px]">
                <option value="1" {{ $selected_stat_comp == 1 ? 'selected' : '' }}>&gt;=</option>
                <option value="2" {{ $selected_stat_comp == 2 ? 'selected' : '' }}>&lt;=</option>
                <option value="5" {{ $selected_stat_comp == 5 ? 'selected' : '' }}>=</option>
            </select>
        </label>
    </div>

    <input type="text" name="{{ $id }}val" value="{{ $stat_value }}" class="input join-item w-[60px]" maxlength="3" />
</div>
