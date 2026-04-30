(function () {
    const root = document.documentElement;

    const readHourOverride = () => {
        const fromQuery = new URLSearchParams(window.location.search).get('skyTime');
        const override = fromQuery ?? window.__ALPS_SKY_TIME__;

        if (override === null || override === undefined || override === '') {
            return null;
        }

        const parsed = Number(override);

        return Number.isFinite(parsed) ? parsed : null;
    };

    const skyStops = [
        {
            hour: 0,
            top: [40, 70, 120],
            mid: [60, 90, 140],
            bottom: [50, 80, 130],
            glow1: [80, 140, 200, 0.32],
            glow2: [90, 130, 190, 0.35],
            glow3: [100, 160, 220, 0.22],
            glow4: [80, 140, 200, 0.15],
        },
        {
            hour: 5,
            top: [230, 180, 160],
            mid: [240, 200, 180],
            bottom: [250, 220, 200],
            glow1: [255, 180, 140, 0.50],
            glow2: [255, 210, 160, 0.45],
            glow3: [255, 190, 100, 0.35],
            glow4: [255, 160, 80, 0.28],
        },
        {
            hour: 8,
            top: [150, 190, 230],
            mid: [180, 210, 240],
            bottom: [210, 240, 250],
            glow1: [255, 220, 160, 0.40],
            glow2: [180, 240, 250, 0.38],
            glow3: [140, 200, 255, 0.45],
            glow4: [120, 180, 255, 0.28],
        },
        {
            hour: 12,
            top: [150, 190, 240],
            mid: [180, 220, 250],
            bottom: [210, 245, 255],
            glow1: [255, 230, 170, 0.40],
            glow2: [200, 245, 255, 0.38],
            glow3: [140, 200, 255, 0.42],
            glow4: [120, 180, 255, 0.28],
        },
        {
            hour: 17,
            top: [240, 160, 140],
            mid: [230, 160, 200],
            bottom: [180, 160, 220],
            glow1: [255, 180, 140, 0.52],
            glow2: [255, 220, 160, 0.42],
            glow3: [255, 190, 100, 0.40],
            glow4: [255, 160, 80, 0.30],
        },
        {
            hour: 20,
            top: [70, 90, 150],
            mid: [90, 110, 170],
            bottom: [80, 100, 160],
            glow1: [200, 160, 120, 0.35],
            glow2: [100, 160, 220, 0.32],
            glow3: [100, 160, 220, 0.28],
            glow4: [80, 140, 200, 0.18],
        },
        {
            hour: 24,
            top: [40, 70, 120],
            mid: [60, 90, 140],
            bottom: [50, 80, 130],
            glow1: [80, 140, 200, 0.32],
            glow2: [90, 130, 190, 0.35],
            glow3: [100, 160, 220, 0.22],
            glow4: [80, 140, 200, 0.15],
        },
    ];

    const mix = (start, end, amount) => start + (end - start) * amount;

    const mixColor = (start, end, amount) => start.map((value, index) => {
        if (index === 3) {
            return +(mix(value, end[index], amount)).toFixed(2);
        }

        return Math.round(mix(value, end[index], amount));
    });

    const formatRgb = (value) => `rgb(${value[0]} ${value[1]} ${value[2]})`;
    const formatRgba = (value) => `rgba(${value[0]}, ${value[1]}, ${value[2]}, ${value[3]})`;

    const resolveTheme = (hourValue) => {
        for (let index = 0; index < skyStops.length - 1; index += 1) {
            const current = skyStops[index];
            const next = skyStops[index + 1];

            if (hourValue >= current.hour && hourValue < next.hour) {
                const span = next.hour - current.hour;
                const amount = span === 0 ? 0 : (hourValue - current.hour) / span;

                return {
                    top: formatRgb(mixColor(current.top, next.top, amount)),
                    mid: formatRgb(mixColor(current.mid, next.mid, amount)),
                    bottom: formatRgb(mixColor(current.bottom, next.bottom, amount)),
                    glow1: formatRgba(mixColor(current.glow1, next.glow1, amount)),
                    glow2: formatRgba(mixColor(current.glow2, next.glow2, amount)),
                    glow3: formatRgba(mixColor(current.glow3, next.glow3, amount)),
                    glow4: formatRgba(mixColor(current.glow4, next.glow4, amount)),
                };
            }
        }

        return resolveTheme(hourValue - 24);
    };

    const applyTheme = () => {
        const overrideHour = readHourOverride();
        const now = new Date();
        // Convert to Philippine Time (UTC+8)
        const phHours = (now.getUTCHours() + 8) % 24;
        const phMinutes = now.getUTCMinutes();
        const phSeconds = now.getUTCSeconds();
        const hourValue = overrideHour ?? (phHours + (phMinutes / 60) + (phSeconds / 3600));
        const theme = resolveTheme(hourValue);

        root.style.setProperty('--alps-sky-top', theme.top);
        root.style.setProperty('--alps-sky-mid', theme.mid);
        root.style.setProperty('--alps-sky-bottom', theme.bottom);
        root.style.setProperty('--alps-sky-glow-1', theme.glow1);
        root.style.setProperty('--alps-sky-glow-2', theme.glow2);
        root.style.setProperty('--alps-sky-glow-3', theme.glow3);
        root.style.setProperty('--alps-sky-glow-4', theme.glow4);
        // Smoothly interpolate overlay opacity and blur based on distance from noon
        const noon = 12;
        const peakWindow = 6; // hours from noon where sky is brightest
        const distance = Math.abs(hourValue - noon);
        const factor = Math.max(0, 1 - (distance / peakWindow)); // 1 at noon, 0 after +/- peakWindow

        const night = { top: 0.12, bottom: 0.18, blur: 0.62 };
        const day = { top: 0.04, bottom: 0.08, blur: 0.35 };

        const overlayTopAlpha = (night.top * (1 - factor)) + (day.top * factor);
        const overlayBottomAlpha = (night.bottom * (1 - factor)) + (day.bottom * factor);
        const blurOpacity = (night.blur * (1 - factor)) + (day.blur * factor);

        root.style.setProperty('--alps-sky-overlay-top-alpha', String(overlayTopAlpha));
        root.style.setProperty('--alps-sky-overlay-bottom-alpha', String(overlayBottomAlpha));
        root.style.setProperty('--alps-sky-blur-opacity', String(blurOpacity));
    };

    window.applyAlpsTimeSky = applyTheme;

    applyTheme();
    window.setInterval(applyTheme, 10 * 60 * 1000);
})();