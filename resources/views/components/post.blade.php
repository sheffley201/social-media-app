<div class=' flex justify-between mt-16 p-3 bg-gray-100 rounded-md border border-gray-300'>
    <div class='flex'>
        <img src='https://www.winhelponline.com/blog/wp-content/uploads/2017/12/user.png' class='w-12 h-12'>
        <div class='mx-5'>
            <p>{{ $user }}</p>
            <p class='max-w-3xl overflow-hidden'>{{ $body }}</p>
        </div>
    </div>
    <div class='flex justify-between items-center'>
        <p class='mx-3'>Likes: {{ $likes }}</p>
        {{ $likeOrUnlike}}
        {{ $delete ?? ''}}
    </div>
</div>