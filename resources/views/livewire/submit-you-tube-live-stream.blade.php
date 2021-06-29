<div>

    <form wire:submit.prevent="submit">

        @error('stream') <span class="mb-4 px-4 py-2 text-sm text-red-800 bg-red-200">{{ $message }}</span> @enderror

        <div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        @error('youTubeId') <span class="mb-4 px-4 py-2 text-sm text-red-800 bg-red-200">{{ $message }}</span> @enderror
        <label for="youTubeId">YouTube ID</label>
        <input id="youTubeId" wire:model="youTubeId" placeholder="YouTube Stream ID" />

        @error('submittedByEmail') <span class="mb-4 px-4 py-2 text-sm text-red-800 bg-red-200">{{ $message }}</span> @enderror
        <label for="submittedByEmail">YouTube ID</label>
        <input id="submittedByEmail" wire:model="submittedByEmail" placeholder="Your E-Mail address" />

        <button type="submit">Submit</button>


    </form>
</div>
