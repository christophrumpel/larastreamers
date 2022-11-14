<div>
    <form wire:submit.prevent="importChannel">

        <input wire:model="channelName" placeholder="Twitch channel name...">
        <button type="submit">Submit</button>
    </form>
</div>
