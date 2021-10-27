<!-- This example requires Tailwind CSS v2.0+ -->
<div
    x-show="showSubmissionModal"
    class="fixed z-30 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="fixed inset-0 backdrop-filter backdrop-blur-lg backdrop-brightness-75 transition" aria-hidden="true"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Content -->
        <div class="inline-block align-bottom bg-white rounded-lg p-6 sm:p-12 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             @click.away="showSubmissionModal = false"
             @keyup.escape.window="showSubmissionModal = false"
             x-show="showSubmissionModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"

        >
            <div class="block absolute top-0 right-0 pt-4 pr-4">
                <button @click="showSubmissionModal = false" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Close</span>
                   <x-icons.close class="w-6 h-6 text-gray-light" />
                </button>
            </div>
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="mb-6 text-lg leading-6 font-bold text-gray-darkest" id="modal-title">
                        Submit A Stream
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-dark leading-5 mb-8">
                            Have you planned a new livestream? Or do you know of one related to Laravel that you like to get added to Larastreamers? Perfect! Just submit it here. It just needs to be relevant to the Laravel community.
                        </p>
                    </div>
                </div>
            </div>

            <livewire:submit-you-tube-live-stream />

        </div>
        <!-- Modal Content -->


    </div>
</div>
