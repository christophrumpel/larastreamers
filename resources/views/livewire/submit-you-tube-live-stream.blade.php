<div>

    <form wire:submit.prevent="submit">
        <div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <label for="youTubeId">YouTube ID</label>
        <input id="youTubeId" wire:model="youTubeId" placeholder="YouTube Stream ID" />

        <label for="submittedByEmail">YouTube ID</label>
        <input id="submittedByEmail" wire:model="submittedByEmail" placeholder="Your E-Mail address" />

        <button type="submit">Submit</button>


    </form>
</div>
