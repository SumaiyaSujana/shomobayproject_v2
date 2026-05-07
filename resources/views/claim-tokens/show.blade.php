<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Digital Claim Token
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'item-claimed')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Item share marked as claimed successfully.
                </div>
            @endif

            @if (session('status') === 'already-claimed')
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded-md">
                    This item share was already claimed.
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ $cartContribution->groupCart?->title ?? 'Group Cart' }}
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Present this token during delivery pickup.
                    </p>

                    <div class="mt-8 mx-auto max-w-md border-4 border-gray-900 rounded-2xl p-8 bg-gray-50">
                        <p class="text-sm uppercase tracking-widest text-gray-500">
                            Shomobay Claim Token
                        </p>

                        <p class="mt-4 text-4xl font-black tracking-widest text-indigo-700 break-all">
                            {{ $cartContribution->qr_claim_token }}
                        </p>

                        <div class="mt-6 grid grid-cols-3 gap-2">
                            @for($i = 0; $i < 18; $i++)
                                <div class="{{ $i % 2 === 0 ? 'bg-gray-900' : 'bg-white' }} border border-gray-900 h-8 rounded-sm"></div>
                            @endfor
                        </div>

                        <p class="mt-4 text-xs text-gray-500">
                            Demo QR-style token block
                        </p>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Neighbor</p>
                            <p class="font-bold text-gray-900">
                                {{ $cartContribution->user?->name ?? 'Unknown Neighbor' }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Item</p>
                            <p class="font-bold text-gray-900">
                                {{ $cartContribution->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Quantity</p>
                            <p class="font-bold text-gray-900">
                                {{ number_format($cartContribution->quantity_grams / 1000, 2) }} kg
                            </p>
                        </div>

                        <div class="rounded-md bg-gray-50 p-4">
                            <p class="text-sm text-gray-500">Vendor</p>
                            <p class="font-bold text-gray-900">
                                {{ $cartContribution->groupCart?->order?->vendor?->vendorProfile?->business_name ?? $cartContribution->groupCart?->order?->vendor?->name ?? 'Not assigned' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        @if($cartContribution->claimed_at)
                            <p class="text-green-700 font-semibold">
                                Claimed at {{ $cartContribution->claimed_at->format('d M Y, h:i A') }}
                            </p>
                        @else
                            <form
                                method="POST"
                                action="{{ route('claim-tokens.claim', $cartContribution) }}"
                                onsubmit="return confirm('Mark this item share as claimed?');"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800"
                                >
                                    Mark as Claimed
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a
                            href="{{ route('claim-tokens.index') }}"
                            class="text-sm text-indigo-600 hover:text-indigo-900 underline"
                        >
                            Back to My Claim Tokens
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>