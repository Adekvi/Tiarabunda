export default {
    render: ({ rektor }) => `<section class="sec sam"><div class="wrap sam-in rv"><div class="ava">${rektor.nama[0]}</div>
      <div><p class="kick">Sambutan pimpinan</p><blockquote>“${rektor.kutipan}”</blockquote><p class="who"><b>${rektor.nama}</b><br>${rektor.jabatan}</p></div></div></section>`
}
