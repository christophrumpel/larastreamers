<div>
    <form wire:submit.prevent="importChannel">
        @error('channel') <span class="error">{{ $message }}</span> @enderror

        <label for="youtubeChannelId">Youtube Channel ID</label>
        <input id="youtubeChannelId" type="text" wire:model.defer="youtubeChannelId" />
        <button type="submit">Import</button>
    </form>
</div>
