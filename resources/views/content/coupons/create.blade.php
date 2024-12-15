{{-- create coupon modal --}}
<div class="modal fade" id="create-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="fw-bold py-1 mb-1">{{ __('Add coupon') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
                    enctype="multipart/form-data" id="create-form">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label" for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="code">{{ __('Code') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="create-code" name="code" />
                                    <span id="refresh-code" class="input-group-text cursor-pointer"><i
                                            class="bx bx-revision"></i></span>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="discount">{{ __('Discount amount') }}</label>
                                <input type="text" class="form-control" name="discount" />
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label" for="start_date">{{ __('Start date') }}</label>
                                <input type="date" class="form-control" name="start_date" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="end_date">{{ __('End date') }}</label>
                                <input type="date" class="form-control" name="end_date" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="max_uses">{{ __('Max uses') }}</label>
                                <input type="text" class="form-control" name="max_uses" />
                            </div>
                        </div>


                        <div class="mb-3" style="text-align: center">
                            <button type="submit" id="create-submit" name="submit"
                                class="btn btn-primary">{{ __('Send') }}</button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>
