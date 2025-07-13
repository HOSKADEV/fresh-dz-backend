{{-- update coupon modal --}}
<div class="modal fade" id="update-modal" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="fw-bold py-1 mb-1">{{ __('Edit coupon') }}</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                  enctype="multipart/form-data" id="update-form">
                  <div class="row">
                      <div class="col-md-6">

                        <input type="hidden" id="coupon_id" name="coupon_id" />

                          <div class="mb-3">
                              <label class="form-label" for="name">{{ __('Name') }}</label>
                              <input type="text" class="form-control" id="name" name="name" />
                          </div>

                          <div class="mb-3">
                              <label class="form-label" for="code">{{ __('Code') }}</label>
                              <input type="text" class="form-control" id="code" name="code" disabled/>
                          </div>

                          <div class="mb-3">
                              <label class="form-label" for="discount">{{ __('Discount amount') }}</label>
                              <input type="text" class="form-control" id="discount" name="discount" disabled/>
                          </div>
                      </div>
                      <div class="col-md-6">

                          <div class="mb-3">
                              <label class="form-label" for="start_date">{{ __('Start date') }}</label>
                              <input type="date" class="form-control" id="start_date" name="start_date" disabled/>
                          </div>

                          <div class="mb-3">
                              <label class="form-label" for="end_date">{{ __('End date') }}</label>
                              <input type="date" class="form-control" id="end_date" name="end_date" disabled/>
                          </div>

                          <div class="mb-3">
                              <label class="form-label" for="max_uses">{{ __('Max uses') }}</label>
                              <input type="text" class="form-control" id="max_uses" name="max_uses" disabled/>
                          </div>
                      </div>
                  </div>


                      <div class="mb-3" style="text-align: center">
                          <button type="submit" id="update-submit" name="submit"
                              class="btn btn-primary">{{ __('Send') }}</button>
                      </div>

              </form>
          </div>
      </div>
  </div>
</div>
