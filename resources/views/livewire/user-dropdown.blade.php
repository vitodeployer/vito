<div class="flex items-center text-gray-600 dark:text-gray-300">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex items-center">
                <x-heroicon-o-cog-6-tooth class="h-7 w-7" />
            </button>
        </x-slot>
        <x-slot name="content">
            <x-dropdown-link :href="route('profile')">
                {{ __("Profile") }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('projects')">
                {{ __("Projects") }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('server-providers')">
                {{ __("Server Providers") }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('source-controls')">
                {{ __("Source Controls") }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('storage-providers')">
                {{ __("Storage Providers") }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('notification-channels')">
                {{ __("Notification Channels") }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('ssh-keys')">
                {{ __("SSH Keys") }}
            </x-dropdown-link>
            <!-- Authentication -->
            <form method="POST" action="{{ route("logout") }}">
                @csrf
                <x-dropdown-link
                    :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                >
                    {{ __("Log Out") }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</div>
