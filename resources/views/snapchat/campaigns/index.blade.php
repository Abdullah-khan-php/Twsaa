<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Snapchat Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Existing Campaigns</h3>
                    <a href="{{ route('snapchat-campaigns.create') }}">
                        <button type="button"
                            class="px-4 btn btn-primary py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                            + Add Campaign
                        </button>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table
                        class="w-full max-w-full border border-gray-300 divide-y divide-gray-200 shadow-md rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Campaign Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Objective</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($campaigns as $campaign)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $campaign->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $campaign->campaign_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $campaign->objective }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <!-- Action buttons like edit, delete can be added here -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No campaigns found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
