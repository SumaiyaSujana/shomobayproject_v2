<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Accepted Orders
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'substitution-created')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Substitution request created successfully. Neighbor contributors can now vote.
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
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Accepted Vendor Orders
                        </h3>

                        <p class="mt-2 text-gray-600">
                            View your accepted orders and propose substitutions when the original item is unavailable.
                        </p>
                    </div>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                    >
                        Back to Dashboard
                    </a>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-gray-600">
                        You do not have accepted orders yet.
                    </p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">
                                        Order #{{ $order->id }}
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Cart:
                                        {{ $order->groupCart?->title ?? 'Unknown Cart' }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Original Item:
                                        {{ $order->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Quantity:
                                        {{ number_format(($order->groupCart?->current_weight_grams ?? 0) / 1000, 2) }} kg
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Status:
                                        <strong>{{ str_replace('_', ' ', ucfirst($order->status)) }}</strong>
                                    </p>
                                </div>

                                <div class="rounded-md bg-gray-50 p-4 min-w-[220px]">
                                    <p class="text-sm text-gray-500">
                                        Total Order Amount
                                    </p>

                                    <p class="mt-1 text-2xl font-bold text-green-700">
                                        ৳{{ number_format($order->total_amount_paisa / 100, 2) }}
                                    </p>
                                </div>
                            </div>

                            @if($order->substitutionRequest)
                                <div class="mt-6 rounded-md bg-purple-50 p-4">
                                    <h5 class="font-semibold text-purple-900">
                                        Substitution Request
                                    </h5>

                                    <p class="mt-2 text-purple-800">
                                        Replace
                                        <strong>{{ $order->substitutionRequest->original_item_name }}</strong>
                                        with
                                        <strong>{{ $order->substitutionRequest->substitute_item_name }}</strong>
                                    </p>

                                    @if($order->substitutionRequest->reason)
                                        <p class="mt-2 text-sm text-purple-700">
                                            Reason: {{ $order->substitutionRequest->reason }}
                                        </p>
                                    @endif

                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-sm text-purple-700">Status</p>
                                            <p class="font-bold text-purple-900">
                                                {{ ucfirst($order->substitutionRequest->status) }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-sm text-purple-700">Approve Votes</p>
                                            <p class="font-bold text-green-700">
                                                {{ $order->substitutionRequest->approveVotesCount() }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-sm text-purple-700">Reject Votes</p>
                                            <p class="font-bold text-red-600">
                                                {{ $order->substitutionRequest->rejectVotesCount() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($order->status === \App\Models\Order::STATUS_ESCROW_HELD)
                                <div class="mt-6 border-t border-gray-200 pt-6">
                                    <h5 class="font-semibold text-gray-900">
                                        Propose Substitution
                                    </h5>

                                    <p class="mt-2 text-gray-600">
                                        Use this if the original item is unavailable or needs replacement before delivery.
                                    </p>

                                    <form
                                        method="POST"
                                        action="{{ route('vendor.orders.substitution.store', $order) }}"
                                        class="mt-6 space-y-4"
                                    >
                                        @csrf

                                        <div>
                                            <x-input-label for="substitute_item_name_{{ $order->id }}" value="Substitute Item Name" />

                                            <x-text-input
                                                id="substitute_item_name_{{ $order->id }}"
                                                name="substitute_item_name"
                                                type="text"
                                                class="mt-1 block w-full"
                                                placeholder="Example: Red onion instead of onion"
                                                required
                                            />

                                            <x-input-error class="mt-2" :messages="$errors->get('substitute_item_name')" />
                                        </div>

                                        <div>
                                            <x-input-label for="reason_{{ $order->id }}" value="Reason" />

                                            <textarea
                                                id="reason_{{ $order->id }}"
                                                name="reason"
                                                rows="3"
                                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                placeholder="Example: Original item is unavailable today, but substitute is fresh and similar quality."
                                            ></textarea>

                                            <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                                        </div>

                                        <button
                                            type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-800"
                                        >
                                            Create Substitution Request
                                        </button>
                                    </form>
                                </div>
                            @else
                                <p class="mt-6 text-sm text-gray-500">
                                    Substitution can only be proposed while the order is still in escrow.
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>