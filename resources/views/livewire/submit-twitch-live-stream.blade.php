<div>
    <form wire:submit.prevent="submit">
        <label for="twitch-channel"></label>
        <input wire:model="twitchChannel" id="twitch-channel" type="text" placeholder="Twitch channel"/>
        <label for="twitch-stream-name"></label>
        <input wire:model="twitchStreamName" id="twitch-stream-name" type="text" placeholder="Twitch stream name"/>
        <button type="submit">Submit</button>
    </form>

</div>
