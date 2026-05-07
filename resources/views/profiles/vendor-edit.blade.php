<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Vendor Profile
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-2xl font-bold text-gray-900">
                        Vendor / Farmer / Wholesaler Details
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Add your business details and upload a trade license or NID document for admin verification.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('vendor.profile.update') }}"
                        enctype="multipart/form-data"
                        class="mt-6 space-y-6"
                    >
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="business_name" value="Business Name" />

                            <x-text-input
                                id="business_name"
                                name="business_name"
                                type="text"
                                class="mt-1 block w-full"
                                value="{{ old('business_name', $vendorProfile->business_name) }}"
                                required
                                autofocus
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                        </div>

                        <div>
                            <x-input-label for="trade_license_file" value="Trade License or NID File" />

                            <input
                                id="trade_license_file"
                                name="trade_license_file"
                                type="file"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                accept=".pdf,.jpg,.jpeg,.png"
                            />

                            <x-input-error class="mt-2" :messages="$errors->get('trade_license_file')" />

                            <p class="mt-2 text-sm text-gray-500">
                                Allowed file types: PDF, JPG, JPEG, PNG. Maximum size: 2MB.
                            </p>

                            @if($vendorProfile->trade_license_file)
                                <p class="mt-3 text-sm text-green-700">
                                    A verification file has already been uploaded.
                                </p>
                            @endif
                        </div>

                        <div class="rounded-md bg-yellow-50 p-4">
                            <p class="text-sm text-yellow-800">
                                Current verification status:
                                <strong>
                                    {{ $vendorProfile->is_verified ? 'Verified' : 'Pending Approval' }}
                                </strong>
                            </p>

                            <p class="mt-1 text-sm text-yellow-700">
                                If you upload a new file, your status will return to pending approval.
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                Save Vendor Profile
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