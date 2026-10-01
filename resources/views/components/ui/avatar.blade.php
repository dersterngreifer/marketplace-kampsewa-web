@php
    $avatarName = trim((string) ($nama ?? ''));
    $avatarPhoto = trim((string) ($foto ?? ''));
    $avatarType = (int) ($type ?? 0);

    $avatarClass = $class ?? 'h-12 w-12';
    $avatarRounded = $rounded ?? 'rounded-2xl';
    $avatarText = $text ?? 'text-sm';
    $avatarExtra = $extra ?? 'ring-2 ring-white shadow-md';

    $avatarFolder = $avatarType === 1
        ? 'assets/image/developers/profiles'
        : 'assets/image/customers/profile';

    $placeholderFoto = ['', '-', 'null', 'belum di isi', 'belum di isi.', 'belum diisi', 'belum diisi.', 'default/profile.png', 'man.png'];
    $fotoKosong = in_array(strtolower($avatarPhoto), $placeholderFoto, true);

    if (!$fotoKosong) {
        if (str_starts_with($avatarPhoto, 'http://') || str_starts_with($avatarPhoto, 'https://')) {
            $avatarUrl = $avatarPhoto;
        } elseif (str_starts_with($avatarPhoto, 'storage/')) {
            $avatarUrl = asset($avatarPhoto);
        } else {
            $avatarUrl = asset($avatarFolder . '/' . ltrim($avatarPhoto, '/'));
        }
    }

    $kata = preg_split('/\s+/', $avatarName, -1, PREG_SPLIT_NO_EMPTY) ?: [];

    if (count($kata) >= 2) {
        $initials = strtoupper(implode('', array_map(fn ($k) => mb_substr($k, 0, 1), array_slice($kata, 0, 3))));
    } else {
        $initials = strtoupper(mb_substr($avatarName !== '' ? $avatarName : '?', 0, 2));
    }

    if ($initials === '') {
        $initials = '?';
    }

    $paletteAvatar = [
        ['bg' => 'from-[#B381F4] to-[#5038ED]', 'text' => 'text-white'],
        ['bg' => 'from-[#F9A8D4] to-[#EC4899]', 'text' => 'text-white'],
        ['bg' => 'from-[#5EEAD4] to-[#0D9488]', 'text' => 'text-white'],
        ['bg' => 'from-[#FCD34D] to-[#F59E0B]', 'text' => 'text-[#78350F]'],
        ['bg' => 'from-[#93C5FD] to-[#2563EB]', 'text' => 'text-white'],
        ['bg' => 'from-[#FDBA74] to-[#EA580C]', 'text' => 'text-white'],
        ['bg' => 'from-[#A3E635] to-[#65A30D]', 'text' => 'text-[#1A2E05]'],
        ['bg' => 'from-[#67E8F9] to-[#0891B2]', 'text' => 'text-white'],
    ];

    $avatarWarna = $paletteAvatar[abs(crc32($avatarName !== '' ? $avatarName : 'user')) % count($paletteAvatar)];
@endphp

@if (!$fotoKosong)
    <img class="{{ $avatarClass }} {{ $avatarRounded }} {{ $avatarExtra }} object-cover"
        src="{{ $avatarUrl }}" alt="Foto {{ $avatarName !== '' ? $avatarName : 'Profil' }}" loading="lazy">
@else
    <div class="{{ $avatarClass }} {{ $avatarRounded }} {{ $avatarExtra }} flex shrink-0 items-center justify-center bg-gradient-to-br {{ $avatarWarna['bg'] }} font-black uppercase leading-none {{ $avatarWarna['text'] }}"
        title="{{ $avatarName !== '' ? $avatarName : 'Pengguna' }}" aria-label="{{ $avatarName !== '' ? $avatarName : 'Pengguna' }}">

        {{ $initials }}
    </div>
@endif
