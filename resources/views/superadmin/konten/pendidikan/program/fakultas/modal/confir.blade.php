{{-- partials/confirm-modal.blade.php --}}
<div id="confirmModal" class="cmodal" hidden>
    <div class="cmodal__backdrop" data-modal-close></div>
    <div class="cmodal__panel" role="dialog" aria-modal="true" aria-labelledby="cmTitle" aria-describedby="cmDesc">
        <div class="cmodal__icon" id="cmIcon" data-icon aria-hidden="true"></div>
        <div class="cmodal__body">
            <h3 class="cmodal__title" id="cmTitle">Konfirmasi</h3>
            <p class="cmodal__message" id="cmDesc">Apakah Anda yakin?</p>
        </div>
        <form method="POST" id="cmForm" class="cmodal__actions">
            @csrf
            <input type="hidden" name="_method" id="cmMethod" value="POST">
            <button type="button" class="btn gh" data-modal-close>Batal</button>
            <button type="submit" class="btn" id="cmConfirm">Ya, lanjutkan</button>
        </form>
    </div>
</div>

@push('css')
    <style>
        /* ===== Confirm modal — transisi ===== */
        .cmodal {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            /* state tertutup */
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition:
                opacity 0.22s ease,
                visibility 0.22s ease;
        }

        .cmodal.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Jangan andalkan [hidden] saja saat animasi — JS akan men-toggle class */
        .cmodal[hidden] {
            display: flex !important;
            /* tetap di layout agar transition jalan */
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .cmodal__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(10, 18, 32, 0.55);
            opacity: 0;
            transition: opacity 0.22s ease;
        }

        .cmodal.is-open .cmodal__backdrop {
            opacity: 1;
        }

        .cmodal__panel {
            position: relative;
            z-index: 1;
            width: min(420px, 100%);
            padding: 24px;
            border-radius: 14px;
            background: var(--card);
            border: 1px solid var(--line);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            transform: translateY(10px) scale(0.96);
            opacity: 0;
            transition:
                transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                opacity 0.22s ease;
        }

        .cmodal.is-open .cmodal__panel {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .cmodal__actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        .cmodal[data-type='danger'] #cmConfirm {
            background: var(--err);
            border-color: var(--err);
            color: #fff;
        }

        .cmodal[data-type='restore'] #cmConfirm {
            background: var(--ok);
            border-color: var(--ok);
            color: #fff;
        }

        /* Hormati reduced motion */
        @media (prefers-reduced-motion: reduce) {

            .cmodal,
            .cmodal__backdrop,
            .cmodal__panel {
                transition: none;
            }
        }
    </style>
@endpush
