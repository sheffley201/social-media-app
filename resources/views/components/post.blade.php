<div class=' flex justify-between my-5 p-3 bg-gray-100 rounded-md border border-gray-300'>
    <div class='flex'>
        <img src='https://www.winhelponline.com/blog/wp-content/uploads/2017/12/user.png' class='w-12'>
        <div class='mx-5'>
            <p>{{ $user }}</p>
            <p>{{ $body }}</p>
        </div>
    </div>
    <div>
        {{ $delete ?? ''}}
    </div>
</div>