<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Group Cart Details
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'group-cart-created')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Group cart created successfully.
                </div>
            @endif

            @if (session('status') === 'contribution-saved')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Your contribution has been saved. The cart price and split bills were recalculated.
                </div>
            @endif

            @if (session('status') === 'contribution-removed')
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md">
                    Your contribution has been removed. The cart total was recalculated.
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $groupCart->title }}
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Item:
                            <strong>{{ $groupCart->groceryItem->name }}</strong>
                        </p>

                        <p class="mt-1 text-gray-600">
                            Building:
                            <strong>{{ $groupCart->apartment_building }}</strong>
                        </p>

                        <p class="mt-1 text-gray-600">
                            Created by:
                            <strong>{{ $groupCart->creator?->name ?? 'Unknown' }}</strong>
                        </p>
                    </div>

                    <a
                        href="{{ route('group-carts.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                    >
                        Back to Group Carts
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Current Weight</h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        {{ number_format($groupCart->current_weight_grams / 1000, 2) }} kg
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Target Weight</h4>

                    <p class="mt-3 text-3xl font-bold text-gray-900">
                        {{ number_format($groupCart->target_weight_grams / 1000, 2) }} kg
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Current Price / kg</h4>

                    <p class="mt-3 text-3xl font-bold text-indigo-700">
                        ৳{{ number_format($currentPricePerKgPaisa / 100, 2) }}
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Countdown</h4>

                    <p
                        id="countdown-timer"
                        data-deadline="{{ $groupCart->deadline_at->toIso8601String() }}"
                        class="mt-3 text-lg font-bold text-orange-600"
                    >
                        Loading...
                    </p>

                    <p class="mt-1 text-sm text-gray-600">
                        Deadline: {{ $groupCart->deadline_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>
                        Progress toward wholesale threshold
                    </span>

                    <span>
                        {{ $progressPercentage }}%
                    </span>
                </div>

                <div class="mt-2 w-full bg-gray-200 rounded-full h-4">
                    <div
                        class="bg-green-600 h-4 rounded-full"
                        style="width: {{ $progressPercentage }}%"
                    ></div>
                </div>

                @if($canCheckout)
                    <p class="mt-4 text-green-700 font-semibold">
                        Wholesale threshold reached. Checkout is now unlocked for the future vendor bidding and escrow step.
                    </p>
                @else
                    <p class="mt-4 text-orange-600 font-semibold">
                        Checkout locked. Need {{ number_format($remainingWeightGrams / 1000, 2) }} kg more to reach the threshold.
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Join This Group Cart
                    </h4>

                    @if($canContribute)
                        <p class="mt-2 text-gray-600">
                            Add your required quantity. If you already joined, submitting again will update your contribution.
                        </p>

                        <div class="mt-4 rounded-md bg-blue-50 p-4">
                            <p class="text-sm text-blue-800">
                                Minimum contribution:
                                <strong>{{ number_format($minimumContributionKg, 2) }} kg</strong>
                            </p>

                            <p class="mt-1 text-sm text-blue-700">
                                Current estimated price:
                                <strong>৳{{ number_format($currentPricePerKgPaisa / 100, 2) }}/kg</strong>
                            </p>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('group-carts.contributions.store', $groupCart) }}"
                            class="mt-6 space-y-4"
                        >
                            @csrf

                            <div>
                                <x-input-label for="quantity_kg" value="Your Quantity in KG" />

                                <x-text-input
                                    id="quantity_kg"
                                    name="quantity_kg"
                                    type="number"
                                    step="0.01"
                                    min="{{ $minimumContributionKg }}"
                                    class="mt-1 block w-full"
                                    value="{{ old('quantity_kg', $currentUserContribution ? $currentUserContribution->quantity_grams / 1000 : '') }}"
                                    placeholder="Example: 5"
                                    required
                                />

                                <x-input-error class="mt-2" :messages="$errors->get('quantity_kg')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ $currentUserContribution ? 'Update Contribution' : 'Join Group Cart' }}
                                </x-primary-button>
                            </div>
                        </form>

                        @if($currentUserContribution)
                            <form
                                method="POST"
                                action="{{ route('group-carts.contributions.destroy', $groupCart) }}"
                                class="mt-4"
                                onsubmit="return confirm('Are you sure you want to remove your contribution?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700"
                                >
                                    Remove My Contribution
                                </button>
                            </form>
                        @endif
                    @else
                        <p class="mt-2 text-gray-600">
                            You cannot contribute to this cart. It may be from another building, expired, or already ordered.
                        </p>
                    @endif
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Price Drop Information
                    </h4>

                    <div class="mt-4 grid grid-cols-1 gap-4">
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">
                                Market Price / kg
                            </p>

                            <p class="mt-1 text-xl font-bold text-red-600">
                                ৳{{ number_format($groupCart->groceryItem->market_price_per_kg_paisa / 100, 2) }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">
                                Current Price / kg
                            </p>

                            <p class="mt-1 text-xl font-bold text-indigo-700">
                                ৳{{ number_format($currentPricePerKgPaisa / 100, 2) }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">
                                Wholesale Price / kg
                            </p>

                            <p class="mt-1 text-xl font-bold text-green-700">
                                ৳{{ number_format($groupCart->groceryItem->wholesale_price_per_kg_paisa / 100, 2) }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-gray-600">
                        As more neighbors join, the price gradually drops from market price toward wholesale price.
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Automated Split Bill
                </h4>

                @if($groupCart->contributions->isEmpty())
                    <p class="mt-3 text-gray-600">
                        No neighbors have contributed yet.
                    </p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Neighbor
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Quantity
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Estimated Bill
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach($groupCart->contributions as $contribution)
                                    <tr>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $contribution->user?->name ?? 'Unknown' }}

                                            @if($contribution->user_id === auth()->id())
                                                <span class="ml-2 text-xs text-indigo-600 font-semibold">
                                                    You
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ number_format($contribution->quantity_grams / 1000, 2) }} kg
                                        </td>

                                        <td class="px-4 py-4 text-sm font-semibold text-gray-900">
                                            ৳{{ number_format($contribution->estimated_amount_paisa / 100, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-bold text-gray-900">
                                        Total
                                    </td>

                                    <td class="px-4 py-4 text-sm font-bold text-gray-900">
                                        {{ number_format($groupCart->current_weight_grams / 1000, 2) }} kg
                                    </td>

                                    <td class="px-4 py-4 text-sm font-bold text-gray-900">
                                        ৳{{ number_format($groupCart->contributions->sum('estimated_amount_paisa') / 100, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const countdownElement = document.getElementById('countdown-timer');

            if (!countdownElement) {
                return;
            }

            const deadline = new Date(countdownElement.dataset.deadline).getTime();

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = deadline - now;

                if (distance <= 0) {
                    countdownElement.innerText = 'Deadline passed';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerText = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
</x-app-layout>