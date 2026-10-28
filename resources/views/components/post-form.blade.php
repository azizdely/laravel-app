@props(['post' => null])

<form
    method="POST"
    action="{{ $post ? route('posts.update', $post) : route('posts.store') }}"
    class="mt-8 max-w-2xl space-y-6"
>
    @csrf

    @if ($post)
        @method('PUT')
    @endif

    <div>
        <label for="title" class="block text-sm font-medium text-gray-900">Titre</label>
        <input
            id="title"
            name="title"
            type="text"
            value="{{ old('title', $post?->title) }}"
            required
            maxlength="255"
            @error('title') aria-invalid="true" aria-describedby="title-error" @enderror
            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600/30"
        >
        @error('title')
            <p id="title-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="block text-sm font-medium text-gray-900">Identifiant d'URL</label>
        <input
            id="slug"
            name="slug"
            type="text"
            value="{{ old('slug', $post?->slug) }}"
            required
            maxlength="255"
            pattern="[a-z0-9\-]+"
            aria-describedby="slug-help @error('slug') slug-error @enderror"
            @error('slug') aria-invalid="true" @enderror
            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600/30"
        >
        <p id="slug-help" class="mt-2 text-sm text-gray-500">Utilisez uniquement des lettres minuscules, des chiffres et des tirets.</p>
        @error('slug')
            <p id="slug-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="excerpt" class="block text-sm font-medium text-gray-900">Extrait (facultatif, 255 caractères au plus)</label>
        <input
            id="excerpt"
            name="excerpt"
            type="text"
            value="{{ old('excerpt', $post?->excerpt) }}"
            maxlength="255"
            @error('excerpt') aria-invalid="true" aria-describedby="excerpt-error" @enderror
            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600/30"
        >
        @error('excerpt')
            <p id="excerpt-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="body" class="block text-sm font-medium text-gray-900">Contenu</label>
        <textarea
            id="body"
            name="body"
            rows="8"
            required
            @error('body') aria-invalid="true" aria-describedby="body-error" @enderror
            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600/30"
        >{{ old('body', $post?->body) }}</textarea>
        @error('body')
            <p id="body-error" class="mt-2 text-sm text-red-700">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-start gap-3">
        <input
            id="is_published"
            name="is_published"
            type="checkbox"
            value="1"
            @checked(old('is_published', $post?->is_published))
            class="mt-1 size-4 rounded border-gray-300 text-red-600 focus:ring-red-600"
        >
        <label for="is_published" class="text-sm text-gray-700">Publier cet article immédiatement</label>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit" class="inline-flex rounded-lg bg-red-600 px-4 py-2 font-semibold text-white transition hover:bg-red-700">
            {{ $post ? 'Mettre à jour' : 'Enregistrer l’article' }}
        </button>
        <a href="{{ $post ? route('posts.show', $post) : route('posts.index') }}" class="text-sm font-medium text-gray-700 underline hover:text-gray-900">
            Annuler
        </a>
    </div>
</form>
