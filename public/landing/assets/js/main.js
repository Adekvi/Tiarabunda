import { mount } from './core/mount.js'

const root = document.getElementById('app')
const name = root.dataset.page || 'home'
const page = await import(`./pages/${name}.js`).catch((e) => {
    console.error(`Halaman "${name}" gagal dimuat:`, e)
    return import('./pages/home.js')
})
await mount(root, page.default)
