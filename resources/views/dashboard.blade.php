<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class='text-lg my-2'>New Post</h3>
                    <form method='post' action='{{ route('create-post') }}'>
                        <div class="flex items-center justify-between">
                            @csrf
                            <input type='hidden' name='user_id' value='{{ Auth::user()->id }}'>
                            <x-text-box name='body' placeholder="Write post here..."></x-text-box>
                            <x-button class='text-lg'>Publish</x-button>
                        </div>
                    </form>
                    @foreach ($posts as $post)
                        <x-post>
                            <x-slot name='user'>
                                {{ $post->name }}
                            </x-slot>
                            <x-slot name='body'>
                                {{ $post->body }}
                            </x-slot>
                            <x-slot name='likes'>
                                {{ $post->likes }}
                            </x-slot>
                            <x-slot name='likeOrUnlike'>
                                @if (in_array($post->id, $likeArr))
                                    <form method='post' action='{{ route('unlike') }}'>
                                        @csrf
                                        <input type='hidden' name='post_id' value='{{ $post->id }}'>
                                        <x-button class='text-red-500 mx-3'>Unlike</x-button>
                                    </form>
                                @else
                                <form method='post' action='{{ route('like-post') }}'>
                                    @csrf
                                    <input type='hidden' name='post_id' value='{{ $post->id }}' />
                                    <x-button class='mx-3'>Like</x-button>
                                </form>
                                @endif
                            </x-slot>
                            @if ($post->user_id == Auth::user()->id) {
                                <x-slot name='delete'>
                                    <form method='post' action='{{ route('delete-post') }}'>
                                        @csrf
                                        <input type='hidden' name='post_id' value='{{ $post->id }}' />
                                        <x-button>Delete</x-button>
                                    </form>
                                </x-slot>
                            }
                            @endif
                        </x-post>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
