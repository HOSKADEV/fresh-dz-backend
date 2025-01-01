<div class="modal fade" id="regionModal" tabindex="-1" aria-labelledby="regionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="regionModalLabel">{{__('Region info')}}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label for="regionName" class="form-label">{{__('Region name')}}</label>
                  <input type="text" class="form-control" id="regionName">
                  <input type="hidden" id="region_id">
              </div>
              <div id="map" style="height: 400px;"></div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" onclick="clearPoints()">{{__('Clear Points')}}</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
              <button type="button" class="btn btn-primary" id="submit_region">{{__('Send')}}</button>
          </div>
      </div>
  </div>
</div>
