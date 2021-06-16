<div class="mt-12">
    <form wire:submit.prevent="importChannel">
        @error('channel') <span class="error">{{ $message }}</span> @enderror

        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Import YouTube Channel</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Import a channel and all its upcoming live streams.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="#" method="POST">
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            @if (session()->has('channel-message'))
                                <div class="mb-4 px-4 py-2 text-sm text-green-800 bg-green-200">
                                    {{ session('channel-message') }}
                                </div>
                            @endif
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="youtubeStreamId" class="block text-sm font-medium text-gray-700">YouTube
                                        Channel ID</label>
                                    <input type="text" wire:model.defer="youtubeChannelId" id="youtubeStreamId"
                                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="language_code" class="block text-sm font-medium text-gray-700">Language</label>
                                    <select wire:model.defer="languageCode" id="language_code"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @foreach(\App\Models\Language::all() as $country)
                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </form>
</div>

