<form method="GET" class="mb-6">
    <div class="flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-56">
            <label class="label">
                <span class="label-text">Character</span>
            </label>
            <input
                type="text"
                name="character"
                value="{{ request('character') }}"
                placeholder="Character name or ID"
                class="w-full input validator"
            />
        </div>
        <div class="flex-1 min-w-56">
            <label class="label">
                <span class="label-text">Item</span>
            </label>
            <input
                type="text"
                name="item"
                value="{{ request('item') }}"
                placeholder="Item name or ID"
                class="w-full input validator"
            />
        </div>
        <div class="min-w-40">
            <label class="label">
                <span class="label-text">From</span>
            </label>
            <input
                type="date"
                name="from"
                value="{{ request('from') }}"
                class="w-full input validator"
            />
        </div>
        <div class="min-w-40">
            <label class="label">
                <span class="label-text">To</span>
            </label>
            <input
                type="date"
                name="to"
                value="{{ request('to') }}"
                class="w-full input validator"
            />
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-soft">
                Search
            </button>
            <a href="{{ route('discovery.index') }}" class="btn btn-soft btn-error">
                Reset
            </a>
        </div>
    </div>
</form>
