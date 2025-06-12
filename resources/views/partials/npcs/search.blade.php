<form method="get" action="{{ route('npcs.index') }}" class="mb-6">
    <div class="space-y-4">
        <div>
            <input type="text" id="name" name="name" value="{{ request('name') }}"
                class="w-full input validator"
                pattern="[A-Za-z]*" minlength="3"
                title="Only letters and at least 3 characters"
                placeholder="Search NPCs by name" />
        </div>
        <div class="flex flex-row gap-4 w-full sm:w-auto">
            <div class="flex flex-col w-full sm:w-auto">
                <label class="input">
                    <span class="label">Min Lvl</span>
                    <input type="number" class="input validator" min="0" max="150"
                        title="Must be between 0 and 150"
                        id="min_lvl" name="min_lvl" value="{{ request('min_lvl') }}" maxlength="3" />
                  </label>
            </div>
            <div class="flex flex-col w-full sm:w-auto">
                <div class="flex flex-col w-full sm:w-auto">
                    <label class="input">
                        <span class="label">Max Lvl</span>
                        <input type="number" class="input validator" min="0" max="150"
                            title="Must be between 0 and 150"
                            id="max_lvl" name="max_lvl" value="{{ request('max_lvl') }}" maxlength="3" />
                      </label>
                </div>
            </div>
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-soft">
                Search
            </button>
            <a href="{{ route('npcs.index') }}" class="btn btn-soft btn-error">
                Reset
            </a>
        </div>
    </div>
</form>
