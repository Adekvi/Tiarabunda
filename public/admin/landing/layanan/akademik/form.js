(function() {
                const jeneng = document.getElementById('jeneng');
                const tandha = document.getElementById('tandha');
                const kategori = document.getElementById('kategori');
                const katrangan = document.getElementById('katrangan');

                const prevJeneng = document.getElementById('prevJeneng');
                const prevSlug = document.getElementById('prevSlug');
                const prevKategori = document.getElementById('prevKategori');
                const prevKatrangan = document.getElementById('prevKatrangan');

                if (!jeneng || !tandha) return;

                let manualSlug = tandha.value.trim().length > 0;
                tandha.addEventListener('input', function() {
                    manualSlug = tandha.value.trim().length > 0;
                    prevSlug.textContent = '/layanan/akademik/unduh/' + (tandha.value.trim() || 'slug-dokumen');
                });

                jeneng.addEventListener('input', function() {
                    const val = jeneng.value.trim();
                    prevJeneng.textContent = val || 'Judul Dokumen Akademik';
                    if (!manualSlug) {
                        const generated = val.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        tandha.value = generated;
                        prevSlug.textContent = '/layanan/akademik/unduh/' + (generated || 'slug-dokumen');
                    }
                });

                if (kategori && prevKategori) {
                    kategori.addEventListener('input', function() {
                        prevKategori.textContent = kategori.value.trim() || 'Umum';
                    });
                }

                if (katrangan && prevKatrangan) {
                    katrangan.addEventListener('input', function() {
                        prevKatrangan.textContent = katrangan.value.trim() ||
                            'Keterangan singkat dokumen akan tampil di bagian ini.';
                    });
                }
            })();
