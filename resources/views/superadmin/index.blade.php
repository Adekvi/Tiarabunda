<x-dashboard.layout.app title="Dashboard" page-title="Dashboard">
    <div class="g">
        <div class="g g4">
            <div class="card stat">
                <div>
                    <small>Pendaftar PMB</small>
                    <b>1.284</b>
                    <span class="up">+12%</span>
                </div>
            </div>
            {{-- stat lain --}}
        </div>

        <div class="card">
            <h2>Pendaftar terbaru</h2>
            <div class="tw">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- nanti dari controller --}}
                        <tr>
                            <td>Aisyah Putri</td>
                            <td>aisyah@mail.example</td>
                            <td><span class="badge ok">Aktif</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard.layout.app>
