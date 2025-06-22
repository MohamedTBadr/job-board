<x-main-layout title="Shaghlni - Find Your Dream Job">
    <div class="min-h-screen flex items-center justify-center bg-black text-white px-4">
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
            <div x-cloak x-show="show"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="text-center">

                <!-- Top Badge -->
                <div class="text-xs bg-white/10 text-white/80 px-3 py-1 rounded-full inline-block mb-4">
                    Shaghlni
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-6xl md:text-8xl font-bold leading-tight tracking-tight">
                    <span class="text-white">Find your</span><br>
                    <span class="text-white/60 font-serif italic">Dream Job</span>
                </h1>

                <!-- Subtitle -->
                <p class="mt-6 text-sm text-white/70">
                    connect with top employers, and find exciting opportunities
                </p>

                <!-- Buttons -->
                <div class="mt-8 flex justify-center space-x-4">
                    <a href="{{ route('register') }}"
                       class="bg-white/10 hover:bg-white/20 text-white text-sm font-semibold py-2 px-5 rounded transition duration-300">
                        Create an Account
                    </a>
                    <a href="{{ route('login') }}"
                       class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white text-sm font-semibold py-2 px-5 rounded transition duration-300">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
