@props([
    'name',
    'id' => null,
    'label' => '',
    'value' => '',
    'rows' => 8,
    'placeholder' => 'Tulis di sini…',
    'required' => false,
    'hint' => null,
])
@php
    $id = $id ?: 'rte_' . $name;
    $taId = $id . '_input';
@endphp

<div class="f" data-rte-field="{{ $name }}">
    <label for="{{ $taId }}">
        {{ $label }}
        @if ($required)
            <span style="color:var(--err)">*</span>
        @endif
    </label>

    {{-- Rich text editor (muncul setelah JS aktif) --}}
    <div class="rte" data-rte hidden>
        <div class="rte-bar" role="toolbar" aria-label="Format teks {{ $label }}">
            <button type="button" class="rb" data-cmd="formatBlock" data-val="p" title="Paragraf">P</button>
            <button type="button" class="rb" data-cmd="formatBlock" data-val="h3" title="Subjudul">H3</button>
            <span class="rte-sep"></span>
            <button type="button" class="rb" data-cmd="bold" title="Tebal"><b>B</b></button>
            <button type="button" class="rb" data-cmd="italic" title="Miring"><i
                    style="font-family:serif;">I</i></button>
            <button type="button" class="rb" data-cmd="underline" title="Garis bawah"><u>U</u></button>
            <span class="rte-sep"></span>
            <button type="button" class="rb" data-cmd="insertUnorderedList" title="Daftar poin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" />
                </svg>
            </button>
            <button type="button" class="rb" data-cmd="insertOrderedList" title="Daftar nomor">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M10 6h10M10 12h10M10 18h10M4 5l1-1v4M3.5 13h2l-2 3h2.2" />
                </svg>
            </button>
            <span class="rte-sep"></span>
            <button type="button" class="rb" data-cmd="createLink" title="Sisipkan tautan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path
                        d="M10 14a4 4 0 005.7 0l3-3a4 4 0 00-5.7-5.7l-1 1M14 10a4 4 0 00-5.7 0l-3 3a4 4 0 005.7 5.7l1-1" />
                </svg>
            </button>
            <span class="rte-sep"></span>
            <button type="button" class="rb" data-cmd="removeFormat" title="Bersihkan format">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M4 7V4h16v3M9 20h6M12 4v16M4 20l16-16" />
                </svg>
            </button>
        </div>

        <div class="rte-area" data-rte-area contenteditable="true" role="textbox" aria-multiline="true"
            aria-label="{{ $label }}" data-placeholder="{{ $placeholder }}"></div>

        <div class="rte-foot">
            <span data-rte-count>0 kata</span>
            <span data-rte-hint>{{ $hint }}</span>
        </div>
    </div>

    {{-- Fallback + sumber data yang dikirim ke server --}}
    <textarea id="{{ $taId }}" name="{{ $name }}" rows="{{ $rows }}"
        placeholder="{{ $placeholder }}" @if ($required) required @endif>{{ $value }}</textarea>

    @error($name)
        <small style="color:var(--err);">{{ $message }}</small>
    @enderror
</div>
