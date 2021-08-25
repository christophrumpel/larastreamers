<div class="flex justify-center py-12">
    <div class="w-full">
        <div class="mt-5 md:mt-0">

            <!-- Global message like success -->
            @if (session()->has('message'))
                <div class="mb-4 px-4 py-2 text-sm text-green-800 bg-green-200 text-center">
                    {{ session('message') }}
                </div>
        @endif
        <!-- Global message like success -->

            <!-- Form -->
            <form wire:submit.prevent="submit">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-12">
                        @if (session()->has('stream-message'))
                            <div class="mb-4 px-4 py-2 text-sm text-green-800 bg-green-200">
                                {{ session('stream-message') }}
                            </div>
                        @endif

                        <div class="grid grid-cols-6 gap-6">

                            <!-- YouTube ID field -->
                            <div class="col-span-6 sm:col-span-2">
                                <label for="youTubeIdOrUrl" class="block text-sm font-medium text-gray-700">YouTube
                                    stream ID or full URL</label>
                                <input id="youTubeIdOrUrl" type="text" wire:model="youTubeIdOrUrl" placeholder="https://www.youtube.com/watch?v=1234"
                                       class="mt-1 focus:ring-red-400 focus:border-red-400 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-600"/>
                                @error('youTubeIdOrUrl')
                                <x-input-error :message="$message"/>
                                @enderror
                            </div>
                            <!-- YouTube ID field -->

                            <!-- Language code field -->
                            <div class="col-span-6 sm:col-span-1">
                                <label for="languageCode"
                                       class="block text-sm font-medium text-gray-700">Language</label>
                                <select wire:model.defer="languageCode" id="languageCode"
                                        class="mt-1 focus:ring-red-400 focus:border-red-400 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-600">
                                    @foreach(\App\Models\Language::query()->orderBy('name')->get() as $country)
                                        <option value="{{ $country->code }}"
                                                @if($country->code === 'en') selected @endif> {{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('languageCode')
                                <x-input-error :message="$message"/>
                                @enderror
                            </div>
                            <!-- Language code field -->

                            <!-- E-mail field -->
                            <div class="col-span-6 sm:col-span-3">
                                <label for="submittedByEmail" class="block text-sm font-medium text-gray-700">Your
                                    E-Mail</label>
                                <input wire:model="submittedByEmail" type="text" id="submittedByEmail"
                                       class="mt-1 focus:ring-red-400 focus:border-red-400 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-600"
                                       placeholder="Your E-Mail address"/>
                                @error('submittedByEmail')
                                <x-input-error :message="$message"/>
                                @enderror
                            </div>
                            <!-- E-mail field -->

                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-12">
                        <button type="submit"
                                class="px-3 py-2 text-sm font-medium text-white transition bg-red-600 rounded-md shadow hover:bg-red-500 focus:bg-red-700 focus:outline-none">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
            <!-- Form -->

        </div>
    </div>
</div>
