<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Snapchat Campaign') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Create a New Campaign</h3>
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-500 text-red-700 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636L5.636 18.364M5.636 5.636l12.728 12.728"></path>
                            </svg>
                            <strong class="text-lg" style="color:red">Whoops! Something went wrong.</strong>
                        </div>
                        <ul class="mt-2 ml-6 list-disc text-sm font-medium">
                            @foreach ($errors->all() as $error)
                                <li class="text-red-800" style="color:red">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form action="{{ route('snapchat-campaigns.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Campaign Name -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Campaign Name</label>
                                <input type="text" name="name"
                                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter campaign name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- Campaign Objective -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Campaign Objective</label>
                                <select name="objective"
                                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="AWARENESS">Awareness</option>
                                    <option value="TRAFFIC">Traffic</option>
                                    <option value="ENGAGEMENT">Engagement</option>
                                    <option value="LEAD_GENERATION">Lead Generation</option>
                                    <option value="SALES">Sales</option>
                                    <option value="APP_PROMOTION">App Promoted</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <!-- Start Date -->
                        <div class="col-sm-6">
                            <div class="mb-4">
                                <!-- <select name="ad_account_id" required>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                    @endforeach
                                </select> -->
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="btn btn-primary px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                                Save Campaign
                            </button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>