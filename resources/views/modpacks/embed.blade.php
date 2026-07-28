<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $modPack->title_en }} - NBO ModPacks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-4">
    <div class="max-w-md mx-auto border border-gray-700 rounded-lg overflow-hidden shadow-lg bg-gray-800">
        @if($modPack->mods->first() && ($modPack->mods->first()->local_image_path || $modPack->mods->first()->image_url))
            <img src="{{ $modPack->mods->first()->local_image_path ?: $modPack->mods->first()->image_url }}" alt="{{ $modPack->title_en }}" class="w-full h-48 object-cover">
        @endif
        <div class="p-4">
            <h2 class="text-xl font-bold mb-2">{{ app()->getLocale() == 'ar' && $modPack->title_ar ? $modPack->title_ar : $modPack->title_en }}</h2>
            <p class="text-gray-400 text-sm mb-4 line-clamp-3">
                {{ app()->getLocale() == 'ar' && $modPack->description_ar ? $modPack->description_ar : $modPack->description_en }}
            </p>
            <a href="{{ route('packs.show', $modPack->id) }}" target="_blank" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                {{ __('View ModPack') }}
            </a>
        </div>
    </div>
</body>
</html>
