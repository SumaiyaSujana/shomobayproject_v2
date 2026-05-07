<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit Vendor Bid
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'bid-saved')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Your bid has been saved successfully.
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
                            Required Quantity:
                            <strong>{{ number_format($groupCart->current_weight_grams / 1000, 2) }} kg</strong>
                        </p>
                    </div>

                    <a
                        href="{{ route('vendor.bulk-requests.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300"
                    >
                        Back to Requests
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Market Price</h4>

                    <p class="mt-3 text-3xl font-bold text-red-600">
                        ৳{{ number_format($groupCart->groceryItem->market_price_per_kg_paisa / 100, 2) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        per kg
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Wholesale Reference</h4>

                    <p class="mt-3 text-3xl font-bold text-green-700">
                        ৳{{ number_format($groupCart->groceryItem->wholesale_price_per_kg_paisa / 100, 2) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        per kg
                    </p>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="font-semibold text-gray-900">Cart Status</h4>

                    <p class="mt-3 text-lg font-bold text-indigo-700">
                        Threshold Met
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Ready for vendor bidding
                    </p>
                </div>
            </div>

            @if($currentVendorBid)
                <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-lg">
                    <h4 class="text-lg font-semibold text-indigo-900">
                        Your Current Bid
                    </h4>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-indigo-700">Price / kg</p>
                            <p class="font-bold text-indigo-900">
                                ৳{{ number_format($currentVendorBid->price_per_kg_paisa / 100, 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-indigo-700">Delivery Fee</p>
                            <p class="font-bold text-indigo-900">
                                ৳{{ number_format($currentVendorBid->delivery_fee_paisa / 100, 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-indigo-700">Estimated Total</p>
                            <p class="font-bold text-indigo-900">
                                ৳{{ number_format($currentVendorBid->estimatedTotalPaisa($groupCart->current_weight_grams) / 100, 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-indigo-700">Status</p>
                            <p class="font-bold text-indigo-900">
                                {{ ucfirst($currentVendorBid->status) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Bid Form
                    </h4>

                    <p class="mt-2 text-gray-600">
                        Submit your price per kg and delivery fee. Submitting again will update your existing bid.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('vendor.bulk-requests.bids.store', $groupCart) }}"
                        class="mt-6 space-y-6"
                    >
                        @csrf

                        <div>
                            <x-input-label for="price_per_kg_taka" value="Your Price per KG in Taka" />

                            <x-text-input
                                id="price_per_kg_taka"
                                name="price_per_kg_taka"
                                type="number"
                                step="0.01"
                                min="1"
                                class="mt-1 block w-full"
                                value="{{ old('price_per_kg_taka', $currentVendorBid ? $currentVendorBid->price_per_kg_paisa / 100 : '') }}"
                                placeholder="Example: 40"
                                required
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('price_per_kg_taka')" />
                        </div>

                        <div>
                            <x-input-label for="delivery_fee_taka" value="Delivery Fee in Taka" />

                            <x-text-input
                                id="delivery_fee_taka"
                                name="delivery_fee_taka"
                                type="number"
                                step="0.01"
                                min="0"
                                class="mt-1 block w-full"
                                value="{{ old('delivery_fee_taka', $currentVendorBid ? $currentVendorBid->delivery_fee_paisa / 100 : 0) }}"
                                placeholder="Example: 300"
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('delivery_fee_taka')" />
                        </div>

                        <div>
                            <x-input-label for="estimated_delivery_at" value="Estimated Delivery Time" />

                            <x-text-input
                                id="estimated_delivery_at"
                                name="estimated_delivery_at"
                                type="datetime-local"
                                class="mt-1 block w-full"
                                value="{{ old('estimated_delivery_at', $currentVendorBid?->estimated_delivery_at?->format('Y-m-d\TH:i')) }}"
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('estimated_delivery_at')" />
                        </div>

                        <div>
                            <x-input-label for="note" value="Vendor Note" />

                            <textarea
                                id="note"
                                name="note"
                                rows="4"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Example: Fresh stock available from farm, delivery possible tomorrow morning."
                            >{{ old('note', $currentVendorBid?->note) }}</textarea>

                            <x-input-error class="mt-2" :messages="$errors->get('note')" />
                        </div>

                        <div>
                            <x-primary-button>
                                {{ $currentVendorBid ? 'Update Bid' : 'Submit Bid' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900">
                        Existing Bids
                    </h4>

                    @if($groupCart->bids->isEmpty())
                        <p class="mt-3 text-gray-600">
                            No vendors have bid yet.
                        </p>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach($groupCart->bids as $bid)
                                <div class="rounded-md border border-gray-200 p-4">
                                    <div class="flex justify-between gap-4">
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
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                Estimated Total
                                            </p>

                                            <p class="font-bold text-green-700">
                                                ৳{{ number_format($bid->estimatedTotalPaisa($groupCart->current_weight_grams) / 100, 2) }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($bid->estimated_delivery_at)
                                        <p class="mt-2 text-sm text-gray-600">
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
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>