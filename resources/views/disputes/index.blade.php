<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Disputes
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'dispute-submitted')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Your dispute has been submitted successfully.
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
                            Delivered Order Disputes
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Submit a dispute if delivered produce quality, quantity, or delivery handling was not acceptable.
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

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    Submit New Dispute
                </h4>

                @if($orders->isEmpty())
                    <p class="mt-3 text-gray-600">
                        You do not have any delivered orders available for dispute.
                    </p>
                @else
                    <div class="mt-6 space-y-6">
                        @foreach($orders as $order)
                            @php
                                $existingDispute = $order->disputes->first();
                            @endphp

                            <div class="rounded-md border border-gray-200 p-4">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <h5 class="font-semibold text-gray-900">
                                            Order #{{ $order->id }}
                                        </h5>

                                        <p class="mt-1 text-sm text-gray-600">
                                            Cart: {{ $order->groupCart?->title ?? 'Unknown Cart' }}
                                        </p>

                                        <p class="mt-1 text-sm text-gray-600">
                                            Item: {{ $order->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                        </p>

                                        <p class="mt-1 text-sm text-gray-600">
                                            Vendor:
                                            {{ $order->vendor?->vendorProfile?->business_name ?? $order->vendor?->name ?? 'Unknown Vendor' }}
                                        </p>
                                    </div>

                                    <div class="rounded-md bg-gray-50 p-4 min-w-[180px]">
                                        <p class="text-sm text-gray-500">
                                            Order Total
                                        </p>

                                        <p class="mt-1 text-xl font-bold text-green-700">
                                            ৳{{ number_format($order->total_amount_paisa / 100, 2) }}
                                        </p>
                                    </div>
                                </div>

                                @if($existingDispute)
                                    <div class="mt-4 rounded-md bg-yellow-50 p-4">
                                        <p class="text-yellow-800 font-semibold">
                                            You already submitted a dispute for this order.
                                        </p>

                                        <p class="mt-1 text-sm text-yellow-700">
                                            Status: {{ ucfirst($existingDispute->status) }}
                                        </p>
                                    </div>
                                @else
                                    <form
                                        method="POST"
                                        action="{{ route('orders.disputes.store', $order) }}"
                                        class="mt-6 space-y-4"
                                    >
                                        @csrf

                                        <div>
                                            <x-input-label for="refund_requested_taka_{{ $order->id }}" value="Requested Refund Amount in Taka" />

                                            <x-text-input
                                                id="refund_requested_taka_{{ $order->id }}"
                                                name="refund_requested_taka"
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                class="mt-1 block w-full"
                                                placeholder="Example: 200"
                                                required
                                            />

                                            <x-input-error class="mt-2" :messages="$errors->get('refund_requested_taka')" />
                                        </div>

                                        <div>
                                            <x-input-label for="reason_{{ $order->id }}" value="Dispute Reason" />

                                            <textarea
                                                id="reason_{{ $order->id }}"
                                                name="reason"
                                                rows="4"
                                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                placeholder="Example: Some of the produce was damaged and quantity was lower than expected."
                                                required
                                            ></textarea>

                                            <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                                        </div>

                                        <button
                                            type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800"
                                        >
                                            Submit Dispute
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900">
                    My Submitted Disputes
                </h4>

                @if($disputes->isEmpty())
                    <p class="mt-3 text-gray-600">
                        You have not submitted any disputes yet.
                    </p>
                @else
                    <div class="mt-6 space-y-4">
                        @foreach($disputes as $dispute)
                            <div class="rounded-md border border-gray-200 p-4">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <h5 class="font-semibold text-gray-900">
                                            Order #{{ $dispute->order_id }}
                                        </h5>

                                        <p class="mt-1 text-sm text-gray-600">
                                            Item:
                                            {{ $dispute->order?->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                        </p>

                                        <p class="mt-1 text-sm text-gray-600">
                                            Vendor:
                                            {{ $dispute->vendor?->vendorProfile?->business_name ?? $dispute->vendor?->name ?? 'Unknown Vendor' }}
                                        </p>

                                        <p class="mt-2 text-gray-700">
                                            {{ $dispute->reason }}
                                        </p>

                                        @if($dispute->admin_note)
                                            <p class="mt-2 text-sm text-indigo-700">
                                                Admin note: {{ $dispute->admin_note }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">
                                            Status
                                        </p>

                                        <p class="font-bold text-gray-900">
                                            {{ ucfirst($dispute->status) }}
                                        </p>

                                        <p class="mt-2 text-sm text-gray-500">
                                            Requested Refund
                                        </p>

                                        <p class="font-bold text-red-700">
                                            ৳{{ number_format($dispute->refund_requested_paisa / 100, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>