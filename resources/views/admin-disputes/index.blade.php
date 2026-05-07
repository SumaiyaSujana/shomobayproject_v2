<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dispute Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'dispute-updated')
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    Dispute decision saved successfully.
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
                            Dispute Resolution Dashboard
                        </h3>

                        <p class="mt-2 text-gray-600">
                            Review neighbor complaints, requested refund amounts, and resolve or reject disputes.
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

            @if($disputes->isEmpty())
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-gray-600">
                        No disputes have been submitted yet.
                    </p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($disputes as $dispute)
                        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">
                                        Dispute #{{ $dispute->id }}
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Order #{{ $dispute->order_id }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Item:
                                        {{ $dispute->order?->groupCart?->groceryItem?->name ?? 'Unknown Item' }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Neighbor:
                                        {{ $dispute->user?->name ?? 'Unknown Neighbor' }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Vendor:
                                        {{ $dispute->vendor?->vendorProfile?->business_name ?? $dispute->vendor?->name ?? 'Unknown Vendor' }}
                                    </p>

                                    <p class="mt-4 text-gray-700">
                                        {{ $dispute->reason }}
                                    </p>
                                </div>

                                <div class="rounded-md bg-gray-50 p-4 min-w-[220px]">
                                    <p class="text-sm text-gray-500">
                                        Requested Refund
                                    </p>

                                    <p class="mt-1 text-2xl font-bold text-red-700">
                                        ৳{{ number_format($dispute->refund_requested_paisa / 100, 2) }}
                                    </p>

                                    <p class="mt-3 text-sm text-gray-500">
                                        Status
                                    </p>

                                    <p class="font-bold text-gray-900">
                                        {{ ucfirst($dispute->status) }}
                                    </p>
                                </div>
                            </div>

                            @if($dispute->status === \App\Models\Dispute::STATUS_OPEN)
                                <form
                                    method="POST"
                                    action="{{ route('admin.disputes.update', $dispute) }}"
                                    class="mt-6 space-y-4 border-t border-gray-200 pt-6"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <x-input-label for="status_{{ $dispute->id }}" value="Decision" />

                                        <select
                                            id="status_{{ $dispute->id }}"
                                            name="status"
                                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            required
                                        >
                                            <option value="">
                                                Select decision
                                            </option>
                                            <option value="resolved">
                                                Resolve dispute
                                            </option>
                                            <option value="rejected">
                                                Reject dispute
                                            </option>
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                    </div>

                                    <div>
                                        <x-input-label for="admin_note_{{ $dispute->id }}" value="Admin Note" />

                                        <textarea
                                            id="admin_note_{{ $dispute->id }}"
                                            name="admin_note"
                                            rows="4"
                                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            placeholder="Example: Vendor contacted and issue verified. Refund will be handled manually."
                                            required
                                        ></textarea>

                                        <x-input-error class="mt-2" :messages="$errors->get('admin_note')" />
                                    </div>

                                    <button
                                        type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-800"
                                    >
                                        Save Decision
                                    </button>
                                </form>
                            @else
                                <div class="mt-6 rounded-md bg-indigo-50 p-4">
                                    <p class="text-indigo-900 font-semibold">
                                        Reviewed by:
                                        {{ $dispute->resolvedBy?->name ?? 'Admin' }}
                                    </p>

                                    <p class="mt-1 text-sm text-indigo-700">
                                        Reviewed at:
                                        {{ $dispute->resolved_at?->format('d M Y, h:i A') }}
                                    </p>

                                    <p class="mt-2 text-indigo-800">
                                        {{ $dispute->admin_note }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>