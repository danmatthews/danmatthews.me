const fonts = {
    body: [
        ['Geist (current)', ''],
        ['Family', '"Family", Georgia, serif'],
        ['Newsreader', '"Newsreader", Georgia, serif'],
        ['Source Serif 4', '"Source Serif 4", Georgia, serif'],
        ['DM Sans', '"DM Sans", Arial, sans-serif'],
        ['Outfit', '"Outfit", Arial, sans-serif'],
    ],
    heading: [
        ['Parkinsans (current)', ''],
        ['Instrument Serif', '"Instrument Serif", Georgia, serif'],
        ['Newsreader', '"Newsreader", Georgia, serif'],
        ['Source Serif 4', '"Source Serif 4", Georgia, serif'],
        ['DM Sans', '"DM Sans", Arial, sans-serif'],
        ['Outfit', '"Outfit", Arial, sans-serif'],
    ],
};

const storageKey = 'font-preview-v3';

export function mountFontPreview() {
    if (document.querySelector('[data-font-preview]')) return;

    const stylesheet = document.createElement('link');
    stylesheet.rel = 'stylesheet';
    stylesheet.href = 'https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Newsreader:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Source+Serif+4:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap';
    document.head.append(stylesheet);

    const styles = document.createElement('style');
    styles.textContent = `
        [data-font-preview] { position: fixed; bottom: 1rem; left: 1rem; z-index: 100; display: flex; flex-wrap: wrap; gap: .75rem; align-items: end; max-width: calc(100vw - 2rem); padding: .75rem 1rem; background: #1a1a1e; color: white; border-radius: .75rem; box-shadow: 0 8px 30px #0004; font: 13px/1.3 system-ui, sans-serif; }
        [data-font-preview] label { display: grid; gap: .25rem; }
        [data-font-preview] select { max-width: 10rem; padding: .35rem .5rem; color: #1a1a1e; background: white; border-radius: .3rem; }
        [data-font-preview] select:focus-visible { outline: 2px solid #ff634a; outline-offset: 2px; }
    `;
    document.head.append(styles);

    const controls = document.createElement('div');
    controls.dataset.fontPreview = '';
    const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');

    for (const [kind, choices] of Object.entries(fonts)) {
        const label = document.createElement('label');
        label.textContent = kind === 'body' ? 'Body font' : 'Heading font';
        const select = document.createElement('select');
        select.setAttribute('aria-label', label.textContent);
        for (const [name, value] of choices) {
            select.add(new Option(name, value));
        }
        select.value = choices.some(([, value]) => value === saved[kind]) ? saved[kind] : '';
        const apply = () => {
            const property = kind === 'body' ? '--font-body' : '--font-display';
            if (select.value) document.documentElement.style.setProperty(property, select.value);
            else document.documentElement.style.removeProperty(property);
            saved[kind] = select.value;
            localStorage.setItem(storageKey, JSON.stringify(saved));
        };
        select.addEventListener('change', apply);
        label.append(select);
        controls.append(label);
        apply();
    }

    document.body.append(controls);
}
