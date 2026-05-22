<!-- Training Confirmation Modal -->
<div id="training-confirmation-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);">
            <!-- Header -->
            <div style="background: linear-gradient(90deg, #5BA247 0%, #7FC241 52%, #5CA548 100%); padding: 1.5rem; border-radius: 1rem 1rem 0 0; color: white; text-align: center;">
                <h5 style="font-weight: 700; font-size: 1.25rem; margin: 0;">Confirm Training</h5>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 2rem;">
                <p style="font-size: 1rem; color: #1e293b; margin-bottom: 1rem;">Are you sure you want to schedule this training?</p>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border-left: 4px solid #5BA247;">
                    <p style="margin: 0; font-size: 0.95rem; color: #475569;"><strong>Training Details:</strong></p>
                    <p id="modal-training-summary" style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #64748b;"></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <button type="button" class="training-btn training-btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                <button type="button" class="training-btn training-btn-primary" id="confirm-training-btn">YES, SCHEDULE IT</button>
            </div>
        </div>
    </div>
</div>

<!-- Discard Progress Modal (Warning) -->
<div id="discard-progress-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);">
            <!-- Header -->
            <div style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 52%, #f87171 100%); padding: 1.5rem; border-radius: 1rem 1rem 0 0; color: white; text-align: center;">
                <h5 style="font-weight: 700; font-size: 1.25rem; margin: 0;">⚠️ Unsaved Changes</h5>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 2rem;">
                <p style="font-size: 1rem; color: #1e293b; margin-bottom: 0;">Are you sure? Your progress will be lost and cannot be recovered.</p>
            </div>

            <!-- Footer -->
            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <button type="button" class="training-btn training-btn-secondary-blue" data-bs-dismiss="modal">KEEP EDITING</button>
                <button type="button" class="training-btn" id="confirm-discard-btn" style="background: #dc2626; color: white; padding: 0.75rem 1.75rem; border-radius: 2rem; font-weight: 700; border: none; cursor: pointer;">DISCARD</button>
            </div>
        </div>
    </div>
</div>

<!-- Availability Warning Modal -->
<div id="availability-warning-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);">
            <!-- Header -->
            <div style="background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 52%, #f59e0b 100%); padding: 1.5rem; border-radius: 1rem 1rem 0 0; color: white; text-align: center;">
                <h5 style="font-weight: 700; font-size: 1.25rem; margin: 0;">⚠️ Availability Conflict</h5>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 2rem;">
                <p style="font-size: 1rem; color: #1e293b; margin-bottom: 1rem;">The selected facilitator may not be available during this date range.</p>
                <div style="background: #fffbeb; padding: 1rem; border-radius: 0.75rem; border-left: 4px solid #f59e0b;">
                    <p id="availability-conflict-message" style="margin: 0; font-size: 0.95rem; color: #92400e;"></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <button type="button" class="training-btn training-btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                <button type="button" class="training-btn training-btn-primary" id="proceed-despite-warning">PROCEED ANYWAY</button>
            </div>
        </div>
    </div>
</div>

<!-- Unavailability Confirmation Modal -->
<div id="unavailability-confirmation-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);">
            <!-- Header -->
            <div style="background: linear-gradient(90deg, #5BA247 0%, #7FC241 52%, #5CA548 100%); padding: 1.5rem; border-radius: 1rem 1rem 0 0; color: white; text-align: center;">
                <h5 style="font-weight: 700; font-size: 1.25rem; margin: 0;">Confirm Unavailability</h5>
            </div>

            <!-- Body -->
            <div class="modal-body" style="padding: 2rem;">
                <p style="font-size: 1rem; color: #1e293b; margin-bottom: 1rem;">Are you sure you want to schedule this unavailability?</p>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border-left: 4px solid #5BA247;">
                    <p id="modal-unavailability-summary" style="margin: 0; font-size: 0.95rem; color: #64748b;"></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <button type="button" class="training-btn training-btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                <button type="button" class="training-btn training-btn-primary" id="confirm-unavailability-btn">YES, CONFIRM</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Button Hover States */
    .training-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .training-btn:active {
        transform: translateY(0);
    }

    /* Modal Backdrop Animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }
</style>
<?php /**PATH C:\Users\Lagman\Desktop\Codes\ALPs Calendar\ALPS-Calendar\resources\views\components\training-modals.blade.php ENDPATH**/ ?>