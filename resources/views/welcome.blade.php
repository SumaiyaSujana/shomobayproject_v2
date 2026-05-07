<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Shomobay - Neighborhood Bulk Buying</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="antialiased bg-slate-950 text-white">
        <div
            class="min-h-screen bg-cover bg-center bg-fixed bg-no-repeat"
            style="
                background-image:
                    linear-gradient(rgba(4, 18, 13, 0.82), rgba(4, 18, 13, 0.88)),
                    url('{{ asset('images/shomobay-bg.jpg') }}'),
                    url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1800&q=80');
            "
        >
            <div class="min-h-screen flex flex-col">
                {{-- Navigation --}}
                <header class="w-full">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6 flex items-center justify-between">
                        <a href="{{ url('/') }}" class="group">
                            <h1 class="text-3xl font-black tracking-tight text-white group-hover:text-green-300 transition">
                                Shomobay
                            </h1>

                            <p class="text-sm md:text-base text-green-100 mt-1">
                                Anti-Syndicate Neighborhood Bulk Buying
                            </p>
                        </a>

                        @if (Route::has('login'))
                            <nav class="flex items-center gap-3">
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-green-500 text-slate-950 font-bold shadow-lg shadow-green-900/30 hover:bg-green-300 hover:-translate-y-0.5 transition"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-white/40 bg-white/5 text-white font-bold backdrop-blur hover:bg-white hover:text-slate-950 hover:-translate-y-0.5 transition"
                                    >
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white text-slate-950 font-bold shadow-lg shadow-black/20 hover:bg-green-300 hover:-translate-y-0.5 transition"
                                        >
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </div>
                </header>

                {{-- Hero Section --}}
                <main class="flex-1">
                    <section class="min-h-[82vh] flex items-center">
                        <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
                            <div class="grid lg:grid-cols-2 gap-12 items-center">
                                <div>
                                    <div class="inline-flex items-center rounded-full bg-green-400/10 border border-green-300/20 px-5 py-2 text-sm font-semibold text-green-100 mb-6 backdrop-blur">
                                        Community buying made fair, transparent, and affordable
                                    </div>

                                    <h2 class="text-5xl md:text-7xl font-black text-white leading-[0.95] tracking-tight">
                                        Buy together,
                                        <span class="text-green-300">save more,</span>
                                        and fight local price syndicates
                                    </h2>

                                    <p class="mt-7 text-lg md:text-xl text-gray-100 max-w-2xl leading-8">
                                        Shomobay helps neighbors combine grocery demand, reach wholesale thresholds,
                                        receive vendor bids, use secure escrow payments, and build a fair community
                                        bulk-buying system.
                                    </p>

                                    <div class="mt-9 flex flex-wrap gap-4">
                                        @guest
                                            <a
                                                href="{{ route('register') }}"
                                                class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-green-500 text-slate-950 font-black shadow-xl shadow-green-950/40 hover:bg-green-300 hover:-translate-y-1 transition"
                                            >
                                                Get Started
                                            </a>

                                            <a
                                                href="{{ route('login') }}"
                                                class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-white/10 border border-white/30 text-white font-black backdrop-blur hover:bg-white hover:text-slate-950 hover:-translate-y-1 transition"
                                            >
                                                Already have an account?
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('dashboard') }}"
                                                class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-green-500 text-slate-950 font-black shadow-xl shadow-green-950/40 hover:bg-green-300 hover:-translate-y-1 transition"
                                            >
                                                Go to Dashboard
                                            </a>
                                        @endguest
                                    </div>
                                </div>

                                <div>
                                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-7 md:p-9 shadow-2xl">
                                        <h3 class="text-3xl font-black text-white">
                                            Key Features
                                        </h3>

                                        <div class="mt-7 space-y-4">
                                            <div class="bg-white/10 rounded-2xl p-5 border border-white/10 hover:bg-white/15 transition">
                                                <h4 class="text-lg font-black text-green-300">
                                                    Neighborhood Group Buying
                                                </h4>

                                                <p class="mt-2 text-gray-100 text-sm leading-6">
                                                    Neighbors create or join group carts to combine demand and unlock better wholesale pricing.
                                                </p>
                                            </div>

                                            <div class="bg-white/10 rounded-2xl p-5 border border-white/10 hover:bg-white/15 transition">
                                                <h4 class="text-lg font-black text-green-300">
                                                    Vendor Bidding and Escrow
                                                </h4>

                                                <p class="mt-2 text-gray-100 text-sm leading-6">
                                                    Verified vendors submit bids while payments stay protected through escrow logic.
                                                </p>
                                            </div>

                                            <div class="bg-white/10 rounded-2xl p-5 border border-white/10 hover:bg-white/15 transition">
                                                <h4 class="text-lg font-black text-green-300">
                                                    Smart Delivery and Quality Tracking
                                                </h4>

                                                <p class="mt-2 text-gray-100 text-sm leading-6">
                                                    Claim tokens, substitution voting, ratings, disputes, alerts, and route planning improve trust.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- About Section --}}
                    <section class="py-20">
                        <div class="max-w-7xl mx-auto px-6 lg:px-8">
                            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 md:p-12 shadow-2xl">
                                <div class="grid lg:grid-cols-2 gap-10 items-center">
                                    <div>
                                        <p class="text-green-300 font-black uppercase tracking-widest text-sm">
                                            About Shomobay
                                        </p>

                                        <h3 class="mt-4 text-4xl md:text-5xl font-black text-white leading-tight">
                                            A fair grocery marketplace for neighborhoods
                                        </h3>
                                    </div>

                                    <div>
                                        <p class="text-gray-100 text-lg leading-8">
                                            Shomobay is a community bulk-buying platform where neighbors work together
                                            to reduce grocery costs. Instead of buying separately at higher retail prices,
                                            users combine orders, invite vendor bids, split bills automatically, and use
                                            escrow-based payment flow for safer transactions.
                                        </p>

                                        <p class="mt-5 text-gray-200 leading-7">
                                            The platform supports the full journey from group cart creation to delivery,
                                            claim verification, quality rating, substitution approval, dispute handling,
                                            route planning, and admin monitoring.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- How It Works --}}
                    <section class="py-20">
                        <div class="max-w-7xl mx-auto px-6 lg:px-8">
                            <div class="text-center max-w-3xl mx-auto">
                                <p class="text-green-300 font-black uppercase tracking-widest text-sm">
                                    How It Works
                                </p>

                                <h3 class="mt-4 text-4xl md:text-5xl font-black text-white">
                                    From grocery demand to doorstep delivery
                                </h3>
                            </div>

                            <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl">
                                    <div class="h-12 w-12 rounded-2xl bg-green-400 text-slate-950 flex items-center justify-center text-xl font-black">
                                        1
                                    </div>

                                    <h4 class="mt-5 text-xl font-black text-white">
                                        Create Group Cart
                                    </h4>

                                    <p class="mt-3 text-gray-200 leading-7">
                                        A neighbor creates a bulk grocery request with target quantity, deadline, and location.
                                    </p>
                                </div>

                                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl">
                                    <div class="h-12 w-12 rounded-2xl bg-green-400 text-slate-950 flex items-center justify-center text-xl font-black">
                                        2
                                    </div>

                                    <h4 class="mt-5 text-xl font-black text-white">
                                        Join and Split Bill
                                    </h4>

                                    <p class="mt-3 text-gray-200 leading-7">
                                        Nearby neighbors join the cart, and the system calculates quantity, progress, and estimated cost.
                                    </p>
                                </div>

                                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl">
                                    <div class="h-12 w-12 rounded-2xl bg-green-400 text-slate-950 flex items-center justify-center text-xl font-black">
                                        3
                                    </div>

                                    <h4 class="mt-5 text-xl font-black text-white">
                                        Accept Vendor Bid
                                    </h4>

                                    <p class="mt-3 text-gray-200 leading-7">
                                        Verified vendors bid on threshold-met carts, and the cart creator accepts the best offer.
                                    </p>
                                </div>

                                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-xl">
                                    <div class="h-12 w-12 rounded-2xl bg-green-400 text-slate-950 flex items-center justify-center text-xl font-black">
                                        4
                                    </div>

                                    <h4 class="mt-5 text-xl font-black text-white">
                                        Deliver and Review
                                    </h4>

                                    <p class="mt-3 text-gray-200 leading-7">
                                        Escrow releases after delivery, neighbors claim items, rate quality, and submit disputes when needed.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Role Cards --}}
                    <section class="py-20">
                        <div class="max-w-7xl mx-auto px-6 lg:px-8">
                            <div class="text-center max-w-3xl mx-auto">
                                <p class="text-green-300 font-black uppercase tracking-widest text-sm">
                                    Platform Roles
                                </p>

                                <h3 class="mt-4 text-4xl md:text-5xl font-black text-white">
                                    Built for neighbors, vendors, and admins
                                </h3>
                            </div>

                            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-xl hover:bg-white/15 transition">
                                    <div class="h-14 w-14 rounded-2xl bg-green-400 text-slate-950 flex items-center justify-center text-2xl font-black">
                                        N
                                    </div>

                                    <h4 class="mt-6 text-2xl font-black text-white">
                                        Neighbor
                                    </h4>

                                    <p class="mt-4 text-gray-200 leading-7">
                                        Create group carts, join nearby bulk requests, contribute quantity, pay through escrow,
                                        claim delivery, rate quality, vote on substitutions, and submit disputes.
                                    </p>
                                </div>

                                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-xl hover:bg-white/15 transition">
                                    <div class="h-14 w-14 rounded-2xl bg-green-400 text-slate-950 flex items-center justify-center text-2xl font-black">
                                        V
                                    </div>

                                    <h4 class="mt-6 text-2xl font-black text-white">
                                        Vendor
                                    </h4>

                                    <p class="mt-4 text-gray-200 leading-7">
                                        Complete vendor profile, receive admin approval, browse bulk requests, place bids,
                                        manage accepted orders, propose substitutions, and plan optimized delivery routes.
                                    </p>
                                </div>

                                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-xl hover:bg-white/15 transition">
                                    <div class="h-14 w-14 rounded-2xl bg-green-400 text-slate-950 flex items-center justify-center text-2xl font-black">
                                        A
                                    </div>

                                    <h4 class="mt-6 text-2xl font-black text-white">
                                        Admin
                                    </h4>

                                    <p class="mt-4 text-gray-200 leading-7">
                                        Approve vendors, manage disputes, monitor system health, review carts, orders,
                                        escrow movement, ratings, substitutions, and platform activity.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Final CTA --}}
                    <section class="py-20">
                        <div class="max-w-5xl mx-auto px-6 lg:px-8">
                            <div class="text-center bg-green-400/15 backdrop-blur-xl border border-green-300/20 rounded-3xl p-10 md:p-14 shadow-2xl">
                                <h3 class="text-4xl md:text-5xl font-black text-white">
                                    Start buying smarter with your neighborhood
                                </h3>

                                <p class="mt-5 text-lg text-gray-100 max-w-2xl mx-auto leading-8">
                                    Join Shomobay and turn scattered grocery purchases into organized, transparent,
                                    and affordable community buying.
                                </p>

                                <div class="mt-8 flex flex-wrap justify-center gap-4">
                                    @guest
                                        <a
                                            href="{{ route('register') }}"
                                            class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-green-400 text-slate-950 font-black shadow-xl shadow-green-950/40 hover:bg-white hover:-translate-y-1 transition"
                                        >
                                            Create Account
                                        </a>

                                        <a
                                            href="{{ route('login') }}"
                                            class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-white/10 border border-white/30 text-white font-black hover:bg-white hover:text-slate-950 hover:-translate-y-1 transition"
                                        >
                                            Log in
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('dashboard') }}"
                                            class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-green-400 text-slate-950 font-black shadow-xl shadow-green-950/40 hover:bg-white hover:-translate-y-1 transition"
                                        >
                                            Open Dashboard
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </section>
                </main>

                {{-- Footer --}}
                <footer>
                    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
                        <p class="text-sm text-gray-300 text-center">
                            © {{ date('Y') }} Shomobay. Built for fair neighborhood bulk buying.
                        </p>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>