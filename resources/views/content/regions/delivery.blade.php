<div class="modal fade" id="deliveryPointModal" tabindex="-1" role="dialog" aria-labelledby="deliveryPointModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deliveryPointModalLabel">{{__('Select Delivery Point')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="deliveryMap" style="height: 400px;"></div>
                <form id="deliveryForm">
                    <input type="hidden" id="delivery_region_id" name="region_id">
                    <input type="hidden" id="delivery_latitude" name="latitude">
                    <input type="hidden" id="delivery_longitude" name="longitude">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{'Close'}}</button>
                <button type="button" class="btn btn-primary" id="submit_delivery_point">{{__('Send')}}</button>
            </div>
        </div>
    </div>
</div>
