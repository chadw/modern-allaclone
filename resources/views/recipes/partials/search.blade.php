<form method="get" action="{{ route('recipes.index') }}" class="mb-6">
    <div class="space-y-4">
        <div>
            <input type="text" id="name" name="name" value="{{ request('name') }}" class="w-full input"
                placeholder="Searches recipe by name" />
        </div>
        <div class="flex flex-wrap gap-4">
            <div class="flex flex-col">
                <label class="select">
                    <span class="label">Tradeskill</span>
                    <select id="ts" name="ts" class="select">
                        <option value="">-</option>
                        @foreach ($tradeskills as $k => $v)
                            <option value="{{ $k }}" {{ request('ts') == $k ? 'selected' : '' }}>
                                {{ $v }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="flex flex-col">
                <label class="input">
                    <span class="label">Min trivial</span>
                    <input type="text" name="min" value="{{ request('min') }}" class="max-w-10" maxlength="3" />
                </label>
            </div>
            <div class="flex flex-col">
                <label class="input">
                    <span class="label">Max trivial</span>
                    <input type="text" name="max" value="{{ request('max') }}" class="max-w-10" maxlength="3" />
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
