<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex max-md:flex-col items-start">
            <div class="rounded-lg w-80 max-w-80 bg-zinc-400/5 dark:bg-zinc-900">
                <div class="px-4 py-4 flex justify-between items-start">
                    <div>
                        <flux:heading>Feeds</flux:heading>
                    </div>
                </div>
                <div class="flex flex-col gap-2 px-2">
                    @foreach ($feeds as $feed)
                        <a href="{{ route('feeds.show', $feed) }}">
                            <div @class([
                            'rounded-lg shadow-xs border border-zinc-200 dark:border-white/10 p-3 space-y-2 hover:bg-zinc-800/5 dark:hover:bg-white/10',
                            'bg-white dark:bg-zinc-800' => !isset($currentFeed) || $currentFeed->isNot($feed),
                            'bg-zinc-800/5 dark:bg-white/10' => isset($currentFeed) && $currentFeed->is($feed)
                        ])>
                                <flux:heading>{{ $feed->name }}</flux:heading>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="px-2 py-2">
                    <flux:modal.trigger name="create-feed">
                        <flux:button variant="subtle" icon="plus" size="sm" class="w-full justify-start!">New Feed</flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="create-feed" class="md:w-96">
                        <form action="{{ route('feeds.store')  }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Create new Feed</flux:heading>
                                </div>

                                <flux:input name="name" label="Name" />

                                <div class="flex">
                                    <flux:spacer />

                                    <flux:button type="submit" variant="primary">Create</flux:button>
                                </div>
                            </div>
                        </form>
                    </flux:modal>
                </div>
            </div>
            <flux:separator class="md:hidden"/>
            <div class="px-6 flex-1 max-md:pt-6 self-stretch">
                @if(!isset($currentFeed))
                    <p>Select a feed or create a new one.</p>
                @else
                    <div class="w-full mb-3">
                        <flux:heading size="lg">{{ $currentFeed->name }}</flux:heading>
                        <flux:subheading>{{ $currentFeed->export_url }}</flux:subheading>
                    </div>
                    <div class="w-full">
                        <flux:modal.trigger name="add-event">
                            <flux:button variant="primary" icon="calendar-plus">Add Event</flux:button>
                        </flux:modal.trigger>

                        <flux:modal name="add-event" class="md:w-96">
                            <form action="{{ route('events.store', $currentFeed)  }}" method="POST">
                                @csrf
                                <div class="space-y-6">
                                    <div>
                                        <flux:heading size="lg">Add Event</flux:heading>
                                    </div>

                                    <flux:input name="title" label="Title" required/>

                                    <flux:input type="date" name="start_date" max="2999-12-31" label="Date" />

                                    <flux:input type="date" name="end_date" max="2999-12-31" label="Date" />

                                    <div class="flex">
                                        <flux:spacer />

                                        <flux:button type="submit" variant="primary">Create</flux:button>
                                    </div>
                                </div>
                            </form>
                        </flux:modal>
                    </div>

                    <flux:table class="w-full table-auto border-collapse">
                        <thead>
                        <tr>
                            <flux:table.column>Start</flux:table.column>
                            <flux:table.column>End</flux:table.column>
                            <flux:table.column>Title</flux:table.column>
                            <flux:table.column></flux:table.column>
                        </tr>
                        </thead>
                        <flux:table.rows>
                            @foreach($currentFeed->events as $event)
                                <tr>
                                    <flux:table.cell>{{ $event->start_at->toDateTimeString() }}</flux:table.cell>
                                    <flux:table.cell>{{ $event->end_at->toDateTimeString() }}</flux:table.cell>
                                    <flux:table.cell>{{ $event->title }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:dropdown position="bottom" align="end">
                                            <flux:button variant="ghost" size="sm" icon="ellipsis" inset="top bottom" />
                                            <flux:navmenu>
                                                <form method="POST" action="{{ route('events.destroy', ['feed' => $currentFeed, 'event' => $event]) }}" class="w-full">
                                                    @csrf
                                                    @method('DELETE')
                                                    <flux:navmenu.item as="button" type="submit" variant="danger" icon="trash-2" class="w-full cursor-pointer">
                                                        {{ __('Delete') }}
                                                    </flux:navmenu.item>
                                                </form>
                                            </flux:navmenu>
                                        </flux:dropdown>
                                    </flux:table.cell>
                                </tr>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @endif
            </div>
        </div>
</x-layouts.app>
