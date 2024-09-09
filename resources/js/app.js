import './bootstrap';
import Alpine from 'alpinejs'

window.Alpine = Alpine

import Typewriter from '@marcreichel/alpine-typewriter';

Alpine.plugin(Typewriter);

Alpine.data('site', () => ({
    mobileMenuOpen: false
}));

Alpine.data('pixels', () => ({
        blocksize: 2,
        rows: 0,
        cols: 0,
        interval: undefined,
        height: 0,
        visibility: [],
        decay: 3,
        decayLimit: 20,
        decayDirection: 'up',
        pixelate() {
            this.height = this.height || this.$el.getBoundingClientRect().height;
            let width = this.$el.getBoundingClientRect().width;
            this.rows = Array(Math.ceil(this.height / this.blocksize)).fill({})
            this.cols = Array(Math.ceil(width / this.blocksize)).fill({})
            this.visibility = this.randomVisibility();
            const c = this.pixelate;
            if (this.interval === undefined) {
                setInterval(() => {
                            if (this.decay > this.decayLimit) {
                                this.decayDirection = 'down'
                            }
                            if (this.decay < 3) {
                                this.decayDirection = 'up';
                            }
                                if (this.decayDirection == 'up') {
                                    this.decay++;
                                } else {
                                    this.decay--;
                                }


                }, 1000);
                this.interval = setInterval(c.bind(this), 600)
            }
        },
        randomVisibility() {
            let vis = [];
            for (let i in this.rows) {
                let row = [];
                this.cols.forEach(r => row.push(Math.floor(Math.random() * this.decay)));
                vis.push(row);
            }
            return vis;
        }
}))

Alpine.start()
