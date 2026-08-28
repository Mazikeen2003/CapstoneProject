<x-app-layout>
    <div class="min-h-screen bg-slate-100 px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex min-h-[calc(100vh-8rem)] items-center justify-center">
            <div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-gradient-to-r from-[#0f1e3d] to-[#162347] px-6 py-5 text-white sm:px-8">
                    <h1 class="text-xl font-bold">Update Password</h1>
                    <p class="mt-1 text-sm text-slate-300">Keep your account protected with a strong password.</p>
                </div>
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
