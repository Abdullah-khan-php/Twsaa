<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Google Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Existing Campaigns</h3>
                    <a href="{{ route('google-campaigns.create') }}">
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
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Status
                                </th>
                                 <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($googleCampaigns as $_eachCampaign)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $_eachCampaign->id }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $_eachCampaign->name }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($_eachCampaign->status === 'active')
                                            <span
                                                class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                        @elseif($_eachCampaign->status === 'paused')
                                            <span
                                                class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Paused</span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                                        @endif
                                    </td>
                                     <td class="px-6 py-4 text-sm text-gray-900">
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No campaigns found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>