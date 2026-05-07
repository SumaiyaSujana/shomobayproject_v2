<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Neighbor Profile
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-2xl font-bold text-gray-900">
                        Neighbor Details
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Add your apartment building and location details. These details will later help the system show nearby group carts.
                    </p>

                    <form method="POST" action="{{ route('neighbor.profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="apartment_building" value="Apartment Building" />

                            <x-text-input
                                id="apartment_building"
                                name="apartment_building"
                                type="text"
                                class="mt-1 block w-full"
                                value="{{ old('apartment_building', $neighborProfile->apartment_building) }}"
                                required
                                autofocus
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('apartment_building')" />
                        </div>

                        <div>
                            <x-input-label for="location_coordinates" value="Location Coordinates" />

                            <x-text-input
                                id="location_coordinates"
                                name="location_coordinates"
                                type="text"
                                class="mt-1 block w-full"
                                value="{{ old('location_coordinates', $neighborProfile->location_coordinates) }}"
                                placeholder="Example: 23.8103,90.4125"
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('location_coordinates')" />

                            <p class="mt-2 text-sm text-gray-500">
                                For now, you can type coordinates manually. Later we can improve this with map support.
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                Save Neighbor Profile
                            </x-primary-button>

                            <a
                                href="{{ route('dashboard') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 underline"
                            >
                                Back to Dashboard
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>