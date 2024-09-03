import './bootstrap';
import Alpine from 'alpinejs'

window.Alpine = Alpine

import Typewriter from '@marcreichel/alpine-typewriter';

Alpine.plugin(Typewriter);

Alpine.data('site', () => ({
    mobileMenuOpen: false
}));

Alpine.start()
