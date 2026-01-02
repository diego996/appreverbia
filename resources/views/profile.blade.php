<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="vstack gap-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="col-lg-8 mx-auto">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="col-lg-8 mx-auto">
                        <livewire:profile.update-password-form />
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="col-lg-8 mx-auto">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
