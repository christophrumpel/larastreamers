<div>
    <form wire:submit.prevent="importStream">
        @error('stream') <span class="error">{{ $message }}</span> @enderror

        <label for="youtubeId">Youtube ID</label>
        <input id="youtubeId" type="text" wire:model.defer="youtubeId" />
        <button type="submit">Import</button>
    </form>
</div>
