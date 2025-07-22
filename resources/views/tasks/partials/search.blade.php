<form method="get" action="{{ route('tasks.index') }}" class="mb-6">
    <div class="space-y-4">
        <div>
            <input type="text" id="name" name="name" value="{{ request('name') }}" class="w-full input"
                placeholder="Searches tasks by name" />
        </div>
        <div class="flex flex-wrap gap-4">
            <div class="flex flex-col">
                <label class="select">
                    <span class="label">Level</span>
                    <select name="level" id="level" class="select">
                        <option value="">-</option>
                        @for ($i = 1; $i <= config('eqemu.max_level', 70); $i++)
                            <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </label>
            </div>
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-soft">
                Search
            </button>
            <a href="{{ route('recipes.index') }}" class="btn btn-soft btn-error">
                Reset
            </a>
        </div>
    </div>
</form>
