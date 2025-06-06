<input type="radio" name="zone_details" class="tab" aria-label="Tasks ({{ count($tasks) ?? 0 }})" />
<div class="tab-content bg-base-100 border-base-300">
    <div class="border border-base-content/5 overflow-x-auto">
        <table class="table table-auto md:table-fixed w-full table-zebra">
            <thead class="text-xs uppercase bg-base-300">
                <tr>
                    <th scope="col" width="40%">Tasks</th>
                    <th scope="col" width="10%">Type</th>
                    <th scope="col" width="5%">Min Lvl</th>
                    <th scope="col" width="5%">Max Lvl</th>
                    <th scope="col" width="10%">Repeat</th>
                    <th scope="col" width="30%">Rewards</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td scope="row">
                            <div class="flex flex-col">
                                {{ $task->title }}
                                <span class="text-xs uppercase text-gray-500">
                                    Duration:
                                    <span class="text-accent">
                                        {{ $task->duration > 0 ? seconds_to_human($task->duration) : 'None' }}
                                    </span>
                                </span>
                            </div>
                        </td>
                        <td>{{ $task->task_type }}</td>
                        <td>{{ $task->min_level }}</td>
                        <td>{{ $task->max_level }}</td>
                        <td>
                            {!! $task->repeatable
                                ? '<span class="badge badge-soft badge-accent">Yes</span>'
                                : '<span class="badge badge-soft badge-warning">No</span>' !!}
                        </td>
                        <td>
                            @if ($task->reward_id_list)
                                @foreach ($task->rewards as $item)
                                    <x-item-link
                                        :item_id="$item->id"
                                        :item_name="$item->Name"
                                        :item_icon="$item->icon"
                                    />
                                @endforeach
                            @endif

                            @if ($task->cash_reward > 0)
                                <div class="text-sm text-success">Coin: {{ price($task->cash_reward) }}</div>
                            @endif

                            @if ($task->exp_reward > 0)
                                <div class="text-sm text-secondary">Exp: {{ number_format($task->exp_reward) }}</div>
                            @endif

                            @php
                                $currency = $altCurrency->firstWhere('id', $task->reward_point_type);
                            @endphp
                            @if ($task->reward_points > 0 && $task->reward_point_type)
                                <div class="text-sm flex items-center gap-1">
                                    <x-item-link
                                        :item_id="$currency->item->id"
                                        :item_name="$currency->item->Name"
                                        :item_icon="$currency->item->icon"
                                    />
                                    <span class="text-accent">x{{ number_format($task->reward_points) }}</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
