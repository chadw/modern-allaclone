<div class="border border-base-content/5 overflow-x-auto mt-6">
    <table class="table table-auto md:table-fixed w-full table-zebra">
        <thead class="text-xs uppercase bg-base-300">
            <tr>
                <th scope="col" class="w-[5%]">Character Race</th>
                <th scope="col" class="w-[20%]">Pet Race</th>
                <th scope="col" class="w-[10%]">Size Mod</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bl_pet_data as $bl)
                <tr>
                    <td scope="row">{{ config('everquest.races.' . $bl->player_race) }}</td>
                    <td>{{ config('everquest.db_races.' . $bl->pet_race) }}</td>
                    <td>{{ $bl->size_modifier }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
