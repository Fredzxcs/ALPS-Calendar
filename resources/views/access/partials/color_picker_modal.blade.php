<div class="modal fade" id="accessColorPickerModal" tabindex="-1" aria-labelledby="accessColorPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content alps-color-modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-boldest" id="accessColorPickerModalLabel">Choose a color</h5>
                    <p class="text-muted mb-0">Start with a distinct suggestion, then tune it with the custom picker.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-3">
                <div class="alps-color-preview-card">
                    <div class="alps-color-preview-large" id="accessColorPreviewLarge"></div>
                    <div class="flex-grow-1">
                        <div class="text-muted small mb-1">Selected color</div>
                        <div class="alps-color-hex-value" id="accessColorHexValue">#FFFFFF</div>
                        <div class="alps-color-note" id="accessColorWarningText">Pick a suggestion or customize your own color.</div>
                    </div>
                </div>

                <div class="mt-4 d-none" id="accessCurrentColorWrap">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0 fw-bold">Current color</h6>
                        <span class="text-muted small">Saved value for this account</span>
                    </div>
                    <div class="alps-color-current-card">
                        <span class="alps-color-chip-swatch" id="accessCurrentColorSwatch"></span>
                        <span class="fw-bold" id="accessCurrentColorHex">#FFFFFF</span>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0 fw-bold">Suggested colors</h6>
                        <span class="text-muted small">Distinct from colors already taken</span>
                    </div>
                    <div class="alps-color-grid" id="accessColorSuggestions"></div>
                </div>

                <div class="mt-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0 fw-bold">Taken colors</h6>
                        <span class="text-muted small">Already assigned to other accounts</span>
                    </div>
                    <div class="alps-color-grid alps-color-grid-tight" id="accessTakenColors"></div>
                </div>

                <div class="mt-4">
                    <label for="accessColorNativePicker" class="form-label fw-bold">Custom picker</label>
                    <input type="color" id="accessColorNativePicker" class="form-control form-control-solid alps-color-native-picker">
                    <div class="form-text">Click a suggestion to load it here, or fine-tune the color manually.</div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-tertiary fw-bold" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-primary btn-green fw-boldest" id="accessColorConfirmBtn">Confirm color</button>
            </div>
        </div>
    </div>
</div>