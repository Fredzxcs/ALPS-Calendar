(function () {
    const state = window.ALPS_ACCESS_COLOR_STATE || {};

    const hiddenInput = document.getElementById('color');
    const triggerButton = document.getElementById('openColorPicker');
    const triggerSwatch = document.getElementById('colorPreviewSwatch');
    const triggerHex = document.getElementById('colorPreviewHex');
    const modalElement = document.getElementById('accessColorPickerModal');
    const previewLarge = document.getElementById('accessColorPreviewLarge');
    const hexValue = document.getElementById('accessColorHexValue');
    const warningText = document.getElementById('accessColorWarningText');
    const suggestionsContainer = document.getElementById('accessColorSuggestions');
    const takenContainer = document.getElementById('accessTakenColors');
    const currentColorWrap = document.getElementById('accessCurrentColorWrap');
    const currentColorSwatch = document.getElementById('accessCurrentColorSwatch');
    const currentColorHex = document.getElementById('accessCurrentColorHex');
    const picker = document.getElementById('accessColorNativePicker');
    const confirmButton = document.getElementById('accessColorConfirmBtn');

    if (!hiddenInput || !triggerButton || !modalElement || !picker || !confirmButton) {
        return;
    }

    const bootstrapModal = window.bootstrap && window.bootstrap.Modal
        ? window.bootstrap.Modal.getOrCreateInstance(modalElement)
        : null;

    const takenColors = normalizeColorList(state.takenColors || []);
    const currentColor = normalizeHex(state.currentColor || '');
    const conflictColors = currentColor
        ? takenColors.filter((color) => color !== currentColor)
        : takenColors.slice();

    const suggestionColors = buildSuggestions(conflictColors, currentColor, 8);
    let committedColor = normalizeHex(hiddenInput.value) || currentColor || suggestionColors[0] || '#5D78FF';
    let pendingColor = committedColor;

    hiddenInput.value = committedColor;
    updateTriggerPreview(committedColor);
    updateModalPreview(committedColor);
    syncPicker(committedColor);

    if (currentColorWrap) {
        if (currentColor) {
            currentColorWrap.classList.remove('d-none');
            setSwatch(currentColorSwatch, currentColor);
            if (currentColorHex) {
                currentColorHex.textContent = currentColor;
            }
        } else {
            currentColorWrap.classList.add('d-none');
        }
    }

    renderSuggestions();
    renderTakenColors();

    document.addEventListener('alps-access-color:sync', function (event) {
        const nextColor = normalizeHex(event.detail && event.detail.color ? event.detail.color : hiddenInput.value);
        if (!nextColor) {
            return;
        }

        committedColor = nextColor;
        pendingColor = nextColor;
        hiddenInput.value = nextColor;
        updateTriggerPreview(nextColor);
        updateModalPreview(nextColor);
        syncPicker(nextColor);
        setWarningText(nextColor);
        renderSelectionState();
    });

    triggerButton.addEventListener('click', function () {
        pendingColor = committedColor;
        openModal();
        renderSelectionState();
        setWarningText(pendingColor);
    });

    picker.addEventListener('input', function (event) {
        setPendingColor(event.target.value);
    });

    confirmButton.addEventListener('click', function () {
        const normalized = normalizeHex(pendingColor) || committedColor;
        const conflict = getConflict(normalized);

        committedColor = normalized;
        hiddenInput.value = committedColor;
        updateTriggerPreview(committedColor);

        if (bootstrapModal) {
            bootstrapModal.hide();
        } else {
            closeModalFallback();
        }

        if (conflict && window.Swal) {
            window.Swal.fire({
                icon: 'warning',
                title: 'Color already in use',
                text: conflict.message,
                confirmButtonText: 'Keep color',
                customClass: {
                    popup: 'alps-swal-glass',
                },
            });
        }
    });

    modalElement.addEventListener('hidden.bs.modal', function () {
        pendingColor = committedColor;
        setWarningText(committedColor);
        syncPicker(committedColor);
        updateModalPreview(committedColor);
        renderSelectionState();
    });

    modalElement.addEventListener('click', function (event) {
        if (event.target === modalElement) {
            if (bootstrapModal) {
                bootstrapModal.hide();
            } else {
                closeModalFallback();
            }
        }
    });

    modalElement.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!bootstrapModal) {
                closeModalFallback();
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modalElement.classList.contains('show') && !bootstrapModal) {
            closeModalFallback();
        }
    });

    function openModal() {
        if (bootstrapModal) {
            bootstrapModal.show();
            return;
        }

        modalElement.classList.add('show');
        modalElement.style.display = 'block';
        modalElement.setAttribute('aria-hidden', 'false');

        if (!document.querySelector('.modal-backdrop[data-alps-color-backdrop="true"]')) {
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.setAttribute('data-alps-color-backdrop', 'true');
            backdrop.addEventListener('click', closeModalFallback);
            document.body.appendChild(backdrop);
        }
    }

    function closeModalFallback() {
        modalElement.classList.remove('show');
        modalElement.style.display = 'none';
        modalElement.setAttribute('aria-hidden', 'true');

        const backdrop = document.querySelector('.modal-backdrop[data-alps-color-backdrop="true"]');
        if (backdrop) {
            backdrop.removeEventListener('click', closeModalFallback);
            backdrop.remove();
        }
    }

    function setPendingColor(color) {
        const normalized = normalizeHex(color);
        if (!normalized) {
            return;
        }

        pendingColor = normalized;
        syncPicker(normalized);
        updateModalPreview(normalized);
        setWarningText(normalized);
        renderSelectionState();
    }

    function renderSuggestions() {
        if (!suggestionsContainer) {
            return;
        }

        suggestionsContainer.innerHTML = '';

        suggestionColors.forEach((color) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'alps-color-chip alps-color-chip-clickable';
            button.dataset.color = color;
            button.innerHTML = `
                <span class="alps-color-chip-swatch"></span>
                <span class="alps-color-chip-text">${color}</span>
            `;
            setSwatch(button.querySelector('.alps-color-chip-swatch'), color);
            button.addEventListener('click', function () {
                setPendingColor(color);
            });
            suggestionsContainer.appendChild(button);
        });
    }

    function renderTakenColors() {
        if (!takenContainer) {
            return;
        }

        takenContainer.innerHTML = '';

        if (!conflictColors.length) {
            const emptyState = document.createElement('div');
            emptyState.className = 'alps-color-empty-state';
            emptyState.textContent = 'No colors are currently assigned to other accounts.';
            takenContainer.appendChild(emptyState);
            return;
        }

        conflictColors.forEach((color) => {
            const chip = document.createElement('div');
            chip.className = 'alps-color-chip alps-color-chip-taken';
            chip.innerHTML = `
                <span class="alps-color-chip-swatch"></span>
                <span class="alps-color-chip-text">${color}</span>
                <i class="bi bi-check2-circle alps-color-chip-icon"></i>
            `;
            setSwatch(chip.querySelector('.alps-color-chip-swatch'), color);
            takenContainer.appendChild(chip);
        });
    }

    function renderSelectionState() {
        document.querySelectorAll('.alps-color-chip-clickable').forEach((element) => {
            const isActive = normalizeHex(element.dataset.color) === pendingColor;
            element.classList.toggle('is-selected', isActive);
        });
    }

    function updateTriggerPreview(color) {
        if (triggerSwatch) {
            setSwatch(triggerSwatch, color);
        }

        if (triggerHex) {
            triggerHex.textContent = color;
        }
    }

    function updateModalPreview(color) {
        if (previewLarge) {
            setSwatch(previewLarge, color);
        }

        if (hexValue) {
            hexValue.textContent = color;
        }
    }

    function syncPicker(color) {
        if (picker) {
            picker.value = color;
        }
    }

    function setWarningText(color) {
        if (!warningText) {
            return;
        }

        const conflict = getConflict(color);
        if (conflict) {
            warningText.textContent = conflict.message;
            warningText.classList.add('text-warning');
            warningText.classList.remove('text-muted');
            return;
        }

        warningText.textContent = 'This color is distinct from the current set of assigned colors.';
        warningText.classList.remove('text-warning');
        warningText.classList.add('text-muted');
    }

    function getConflict(color) {
        const normalized = normalizeHex(color);
        if (!normalized || !conflictColors.length) {
            return null;
        }

        let nearestDistance = Number.POSITIVE_INFINITY;

        conflictColors.forEach((takenColor) => {
            const distance = colorDistance(normalized, takenColor);
            if (distance < nearestDistance) {
                nearestDistance = distance;
            }
        });

        if (nearestDistance <= 0) {
            return {
                message: 'This exact color is already assigned to another account. You can still keep it if you want.',
            };
        }

        if (nearestDistance < 42) {
            return {
                message: 'This color is very close to an assigned color. You can still keep it, but it may be harder to distinguish.',
            };
        }

        return null;
    }

    function setSwatch(element, color) {
        if (!element) {
            return;
        }

        const normalized = normalizeHex(color) || '#FFFFFF';
        element.style.backgroundColor = normalized;
        element.style.boxShadow = `inset 0 0 0 1px rgba(255, 255, 255, 0.35), 0 0 0 1px ${normalized}`;
    }

    function normalizeColorList(colors) {
        const normalized = [];

        colors.forEach((color) => {
            const value = normalizeHex(typeof color === 'object' && color !== null ? color.color : color);
            if (value && !normalized.includes(value)) {
                normalized.push(value);
            }
        });

        return normalized;
    }

    function normalizeHex(color) {
        if (color === null || color === undefined || color === '') {
            return '';
        }

        let value = String(color).trim().toUpperCase();
        if (!value) {
            return '';
        }

        if (value[0] !== '#') {
            value = `#${value}`;
        }

        const shortHex = /^#([0-9A-F]{3})$/;
        const fullHex = /^#([0-9A-F]{6})$/;

        if (shortHex.test(value)) {
            const digits = value.slice(1).split('');
            return `#${digits.map((digit) => digit + digit).join('')}`;
        }

        if (!fullHex.test(value)) {
            return '';
        }

        return value;
    }

    function buildSuggestions(occupiedColors, currentValue, limit) {
        const occupied = occupiedColors.slice();
        if (currentValue && !occupied.includes(currentValue)) {
            occupied.push(currentValue);
        }

        const candidates = createCandidates().filter((color) => !occupied.includes(color));
        const selected = [];
        const targetCount = Math.max(limit || 8, 1);

        while (selected.length < targetCount && candidates.length) {
            let bestColor = candidates[0];
            let bestScore = Number.NEGATIVE_INFINITY;

            candidates.forEach((candidate) => {
                const score = scoreCandidate(candidate, occupied, selected);
                if (score > bestScore) {
                    bestScore = score;
                    bestColor = candidate;
                }
            });

            selected.push(bestColor);
            removeCandidate(candidates, bestColor);
        }

        if (!selected.length && currentValue) {
            selected.push(currentValue);
        }

        return selected;
    }

    function removeCandidate(candidates, color) {
        const index = candidates.indexOf(color);
        if (index >= 0) {
            candidates.splice(index, 1);
        }
    }

    function scoreCandidate(candidate, occupied, selected) {
        const comparisonSet = occupied.concat(selected);
        const minDistance = comparisonSet.length
            ? Math.min(...comparisonSet.map((color) => colorDistance(candidate, color)))
            : 999;
        const luminance = relativeLuminance(candidate);
        const brightnessScore = 1 - Math.abs(luminance - 0.64);
        return (minDistance * 1.4) + (brightnessScore * 20);
    }

    function createCandidates() {
        const candidates = [];
        const hues = [0, 12, 24, 36, 48, 60, 78, 96, 114, 132, 150, 168, 186, 204, 222, 240, 258, 276, 294, 312, 330, 348];
        const saturations = [58, 68, 78];
        const lightnesses = [56, 64, 72];

        hues.forEach((hue) => {
            saturations.forEach((saturation) => {
                lightnesses.forEach((lightness) => {
                    const color = hslToHex(hue, saturation, lightness);
                    if (!candidates.includes(color)) {
                        candidates.push(color);
                    }
                });
            });
        });

        return candidates;
    }

    function hslToHex(hue, saturation, lightness) {
        const sat = saturation / 100;
        const light = lightness / 100;
        const chroma = (1 - Math.abs((2 * light) - 1)) * sat;
        const segment = hue / 60;
        const secondary = chroma * (1 - Math.abs((segment % 2) - 1));
        let red = 0;
        let green = 0;
        let blue = 0;

        if (segment >= 0 && segment < 1) {
            red = chroma;
            green = secondary;
        } else if (segment >= 1 && segment < 2) {
            red = secondary;
            green = chroma;
        } else if (segment >= 2 && segment < 3) {
            green = chroma;
            blue = secondary;
        } else if (segment >= 3 && segment < 4) {
            green = secondary;
            blue = chroma;
        } else if (segment >= 4 && segment < 5) {
            red = secondary;
            blue = chroma;
        } else {
            red = chroma;
            blue = secondary;
        }

        const match = light - chroma / 2;
        const [r, g, b] = [red, green, blue].map((channel) => Math.round((channel + match) * 255));
        return rgbToHex(r, g, b);
    }

    function rgbToHex(red, green, blue) {
        return `#${[red, green, blue].map((channel) => channel.toString(16).padStart(2, '0')).join('').toUpperCase()}`;
    }

    function colorDistance(firstColor, secondColor) {
        const first = hexToRgb(firstColor);
        const second = hexToRgb(secondColor);

        if (!first || !second) {
            return 999;
        }

        const redDelta = first.red - second.red;
        const greenDelta = first.green - second.green;
        const blueDelta = first.blue - second.blue;

        return Math.sqrt((redDelta * redDelta) + (greenDelta * greenDelta) + (blueDelta * blueDelta));
    }

    function relativeLuminance(color) {
        const rgb = hexToRgb(color);
        if (!rgb) {
            return 0.5;
        }

        const red = toLinear(rgb.red / 255);
        const green = toLinear(rgb.green / 255);
        const blue = toLinear(rgb.blue / 255);

        return (0.2126 * red) + (0.7152 * green) + (0.0722 * blue);
    }

    function toLinear(channel) {
        return channel <= 0.03928
            ? channel / 12.92
            : Math.pow((channel + 0.055) / 1.055, 2.4);
    }

    function hexToRgb(color) {
        const normalized = normalizeHex(color);
        if (!normalized) {
            return null;
        }

        const match = normalized.match(/^#([0-9A-F]{6})$/);
        if (!match) {
            return null;
        }

        const value = match[1];
        return {
            red: parseInt(value.slice(0, 2), 16),
            green: parseInt(value.slice(2, 4), 16),
            blue: parseInt(value.slice(4, 6), 16),
        };
    }
})();