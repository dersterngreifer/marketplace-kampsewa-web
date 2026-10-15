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
        if (str_starts_with($avatarPhoto, 'http://') || str_starts_with($avatarPhoto, 'https://') || str_starts_with($avatarPhoto, '//')) {
            $avatarUrl = $avatarPhoto;
        } elseif (str_starts_with($avatarPhoto, 'assets/') || str_starts_with($avatarPhoto, 'images/')) {
            $avatarUrl = asset($avatarPhoto);
        } else {
            $cleanPhoto = ltrim($avatarPhoto, '/');
            if (file_exists(public_path($avatarFolder . '/' . $cleanPhoto))) {
                $avatarUrl = asset($avatarFolder . '/' . $cleanPhoto);
            } elseif (file_exists(public_path('assets/image/customers/profile/' . $cleanPhoto))) {
                $avatarUrl = asset('assets/image/customers/profile/' . $cleanPhoto);
            } else {
                $avatarUrl = asset($avatarFolder . '/' . $cleanPhoto);
            }
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
        src="{{ $avatarUrl }}" alt="Foto {{ $avatarName !== '' ? $avatarName : 'Profil' }}" loading="lazy"
        onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22128%22%20height%3D%22128%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20128%20128%22%3E%3Ccircle%20cx%3D%2264%22%20cy%3D%2264%22%20r%3D%2264%22%20fill%3D%22%23e2e8f0%22%2F%3E%3Cpath%20d%3D%22M64%2064a24%2024%200%20100-48%2024%2024%200%20000%2048zm0%2012c-26.7%200-48%2016-48%2036v8h96v-8c0-20-21.3-36-48-36z%22%20fill%3D%22%2394a3b8%22%2F%3E%3C%2Fsvg%3E';">
@else
    <div class="{{ $avatarClass }} {{ $avatarRounded }} {{ $avatarExtra }} flex shrink-0 items-center justify-center bg-gradient-to-br {{ $avatarWarna['bg'] }} font-black uppercase leading-none {{ $avatarWarna['text'] }}"
        title="{{ $avatarName !== '' ? $avatarName : 'Pengguna' }}" aria-label="{{ $avatarName !== '' ? $avatarName : 'Pengguna' }}">

        {{ $initials }}
    </div>
@endif
