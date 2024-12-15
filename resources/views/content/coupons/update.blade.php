{{-- note modal --}}
<div class="modal fade" id="note_modal"  aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{__('Order note')}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" onsubmit="event.preventDefault()" action="#"
          enctype="multipart/form-data" id="note_form">


            <input type="text" id="note_order_id" name="order_id" hidden />

            <div class="mb-3">
              <label class="form-label" for="driver_id">{{__('Note')}}</label>
              <textarea id="note" name="note" class="form-control" rows="5" style="height: 125;" dir="rtl" ></textarea>
            </div>
          <div class="mb-3" style="text-align: center">
            <button type="submit" id="submit_note" name="submit_note" class="btn btn-primary">{{__('Send')}}</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
