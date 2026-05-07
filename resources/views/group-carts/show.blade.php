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

            @if (session('status') === 'bid-accepted')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Vendor bid accepted successfully. Neighbor payments have been moved into escrow.
                </div>
            @endif

            @if (session('status') === 'order-refunded')
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md">
                    Escrow money has been refunded back to participant wallets.
                </div>
            @endif

            @if (session('status') === 'group-cart-expired')
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md">
                    This failed group cart has been marked as expired. No escrow money was held for this cart.
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

                        <p class="mt-1 text-gray-600">
                            Status:
                            <strong>{{ str_replace('_', ' ', ucfirst($groupCart->status)) }}</strong>
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

            @if($groupCart->order)
                <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-lg">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-semibold text-indigo-900">
                                Order Created and Escrow Held
                            </h4>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-indigo-700">Accepted Vendor</p>
                                    <p class="font-bold text-indigo-900">
                                        {{ $groupCart->order->vendor?->vendorProfile?->business_name ?? $groupCart->order->vendor?->name ?? 'Vendor' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-indigo-700">Bid Price</p>
                                    <p class="font-bold text-indigo-900">
                                        ৳{{ number_format($groupCart->order->bid->price_per_kg_paisa / 100, 2) }}/kg
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-indigo-700">Total Order Amount</p>
                                    <p class="font-bold text-indigo-900">
                                        ৳{{ number_format($groupCart->order->total_amount_paisa / 100, 2) }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-indigo-700">Order Status</p>
                                    <p class="font-bold text-indigo-900">
                                        {{ str_replace('_', ' ', ucfirst($groupCart->order->status)) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($canRefundOrder)
                            <form
                                method="POST"
                                action="{{ route('orders.refund', $groupCart->order) }}"
                                onsubmit="return confirm('Refund all escrow money back to participant wallets?');"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700"
                                >
                                    Refund Escrow
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            @if($canExpireFailedCart)
                <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-semibold text-yellow-900">
                                Failed Cart Refund Trigger
                            </h4>

                            <p class="mt-2 text-sm text-yellow-800">
                                This cart has not reached the wholesale threshold. For demo, the creator can trigger the failed-cart refund flow and mark it as expired.
                            </p>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('group-carts.expire-refund', $groupCart) }}"
                            onsubmit="return confirm('Mark this failed cart as expired?');"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700"
                            >
                                Trigger Failed Cart Refund
                            </button>
                        </form>
                    </div>
                </div>
            @endif

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

                @if($groupCart->status === \App\Models\GroupCart::STATUS_EXPIRED)
                    <p class="mt-4 text-red-700 font-semibold">
                        This cart is expired. Contributions, bidding, and checkout are closed.
                    </p>
                @elseif($groupCart->status === \App\Models\GroupCart::STATUS_ORDERED)
                    <p class="mt-4 text-indigo-700 font-semibold">
                        This cart has already been ordered. Contributions are locked.
                    </p>
                @elseif($canCheckout)
                    <p class="mt-4 text-green-700 font-semibold">
                        Wholesale threshold reached. Vendors can bid, and the cart creator can accept a bid.
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

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Wallet
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Escrow
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

                                        <td class="px-4 py-4 text-sm text-green-700 font-semibold">
                                            ৳{{ number_format(($contribution->user?->wallet?->balance_paisa ?? 0) / 100, 2) }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-orange-600 font-semibold">
                                            ৳{{ number_format(($contribution->user?->wallet?->escrow_paisa ?? 0) / 100, 2) }}
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

                                    <td class="px-4 py-4"></td>
                                    <td class="px-4 py-4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

            @if($groupCart->status === \App\Models\GroupCart::STATUS_THRESHOLD_MET || $groupCart->status === \App\Models\GroupCart::STATUS_ORDERED || $groupCart->status === \App\Models\GroupCart::STATUS_EXPIRED)
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Vendor Bids
                    </h4>

                    @if($groupCart->bids->isEmpty())
                        <p class="mt-3 text-gray-600">
                            No vendor bids have been submitted yet.
                        </p>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach($groupCart->bids as $bid)
                                <div class="rounded-md border border-gray-200 p-4">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $bid->vendor?->vendorProfile?->business_name ?? $bid->vendor?->name ?? 'Vendor' }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                Price: ৳{{ number_format($bid->price_per_kg_paisa / 100, 2) }}/kg
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                Delivery Fee: ৳{{ number_format($bid->delivery_fee_paisa / 100, 2) }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-600">
                                                Estimated Total:
                                                <strong>৳{{ number_format($bid->estimatedTotalPaisa($groupCart->current_weight_grams) / 100, 2) }}</strong>
                                            </p>

                                            @if($bid->estimated_delivery_at)
                                                <p class="mt-1 text-sm text-gray-600">
                                                    Delivery:
                                                    {{ $bid->estimated_delivery_at->format('d M Y, h:i A') }}
                                                </p>
                                            @endif

                                            @if($bid->note)
                                                <p class="mt-2 text-sm text-gray-500">
                                                    {{ $bid->note }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex flex-col items-start md:items-end gap-3">
                                            @if($bid->status === \App\Models\Bid::STATUS_ACCEPTED)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Accepted
                                                </span>
                                            @elseif($bid->status === \App\Models\Bid::STATUS_REJECTED)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif

                                            @if($canAcceptBid && $bid->status === \App\Models\Bid::STATUS_PENDING)
                                                <form
                                                    method="POST"
                                                    action="{{ route('group-carts.bids.accept', [$groupCart, $bid]) }}"
                                                    onsubmit="return confirm('Accept this vendor bid and move all neighbor payments into escrow?');"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800"
                                                    >
                                                        Accept Bid
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(!$canAcceptBid && $groupCart->status === \App\Models\GroupCart::STATUS_THRESHOLD_MET)
                            <p class="mt-4 text-sm text-gray-500">
                                Only the group cart creator can accept a vendor bid.
                            </p>
                        @endif
                    @endif
                </div>
            @endif

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