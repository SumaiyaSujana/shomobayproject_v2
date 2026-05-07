<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Group Cart
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-2xl font-bold text-gray-900">
                        Start a Neighborhood Bulk Buy
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Create a shared grocery cart for your apartment building. Other neighbors will join by contributing quantities.
                    </p>

                    <div class="mt-4 rounded-md bg-green-50 p-4">
                        <p class="text-sm text-green-800">
                            Your building:
                            <strong>{{ $neighborProfile->apartment_building }}</strong>
                        </p>

                        <p class="mt-1 text-sm text-green-700">
                            This cart will be linked to your building automatically.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('group-carts.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="grocery_item_id" value="Grocery Item" />

                            <select
                                id="grocery_item_id"
                                name="grocery_item_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option value="">
                                    Select an item
                                </option>

                                @foreach($groceryItems as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        {{ old('grocery_item_id') == $item->id ? 'selected' : '' }}
                                    >
                                        {{ $item->name }}
                                        | Market ৳{{ number_format($item->market_price_per_kg_paisa / 100, 2) }}/kg
                                        | Wholesale ৳{{ number_format($item->wholesale_price_per_kg_paisa / 100, 2) }}/kg
                                        | Minimum bulk {{ number_format($item->minimum_bulk_weight_grams / 1000, 2) }} kg
                                    </option>
                                @endforeach
                            </select>

                            <x-input-error class="mt-2" :messages="$errors->get('grocery_item_id')" />
                        </div>

                        <div>
                            <x-input-label for="title" value="Cart Title" />

                            <x-text-input
                                id="title"
                                name="title"
                                type="text"
                                class="mt-1 block w-full"
                                value="{{ old('title') }}"
                                placeholder="Example: Block A potato bulk order"
                                required
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="target_weight_kg" value="Target Weight in KG" />

                            <x-text-input
                                id="target_weight_kg"
                                name="target_weight_kg"
                                type="number"
                                step="0.01"
                                min="1"
                                class="mt-1 block w-full"
                                value="{{ old('target_weight_kg') }}"
                                placeholder="Example: 50"
                                required
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('target_weight_kg')" />

                            <p class="mt-2 text-sm text-gray-500">
                                The system will not allow checkout until this target weight is reached.
                            </p>
                        </div>

                        <div>
                            <x-input-label for="deadline_at" value="Deadline" />

                            <x-text-input
                                id="deadline_at"
                                name="deadline_at"
                                type="datetime-local"
                                class="mt-1 block w-full"
                                value="{{ old('deadline_at') }}"
                                required
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('deadline_at')" />

                            <p class="mt-2 text-sm text-gray-500">
                                Neighbors must join before this time.
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                Create Group Cart
                            </x-primary-button>

                            <a
                                href="{{ route('group-carts.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 underline"
                            >
                                Back to Group Carts
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>