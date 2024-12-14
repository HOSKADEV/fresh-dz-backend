var e = `<div class="dz-preview dz-file-preview">
  <div class="dz-details">
    <div class="dz-thumbnail">
      <img data-dz-thumbnail>
      <span class="dz-nopreview">No preview</span>
      <div class="dz-success-mark"></div>
      <div class="dz-error-mark"></div>
      <div class="dz-error-message"><span data-dz-errormessage></span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
      </div>
    </div>
    <div class="dz-filename" data-dz-name></div>
    <div class="dz-size" data-dz-size></div>
  </div>
  </div>`;

        Dropzone.options.videoForm = { // camelized version of the `id`
            previewTemplate: e,
            addRemoveLinks: true,
            uploadMultiple: false,
            paramName: "video", // The name that will be used to transfer the file
            maxFilesize: 2048, // MB
            maxFiles: 1,
            parallelUploads: 5,
            autoProcessQueue: false,
            chunking: true,
            //chunkSize: 5,
            parallelChunkUploads: true,
            init: function() {
                var myDropzone = this;

                document.getElementById("video_submit_btn").addEventListener("click", function(e) {
                  $('#video_submit_btn').attr('disabled', true);
                $('#video_close_btn').attr('disabled', true);
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                });

                this.on("complete", function (file) {
                  if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    $('#modal').modal('hide');
                  myDropzone.removeAllFiles();
                  $('#video_submit_btn').attr('disabled', false);
                  $('#video_close_btn').attr('disabled', false);
                  $('#laravel_datatable').DataTable().ajax.reload();
                  }
                });
            }
        };

        Dropzone.options.imagesForm = { // camelized version of the `id`
          previewTemplate: e,
          addRemoveLinks: true,
          uploadMultiple: true,
          paramName: "images", // The name that will be used to transfer the file
          maxFilesize: 2048, // MB
          maxFiles: 15,
          parallelUploads: 5,
          autoProcessQueue: false,
          //chunking: true,
          //chunkSize: 5,
          //parallelChunkUploads: true,
          init: function() {
              var myDropzone = this;

              document.getElementById("images_submit_btn").addEventListener("click", function(e) {
                $('#images_submit_btn').attr('disabled', true);
                $('#images_close_btn').attr('disabled', true);
                  e.preventDefault();
                  e.stopPropagation();
                  myDropzone.processQueue();
              });

              this.on("complete", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                  $('#modal').modal('hide');
                  myDropzone.removeAllFiles();
                  $('#images_submit_btn').attr('disabled', false);
                  $('#images_close_btn').attr('disabled', false);
                  $('#laravel_datatable').DataTable().ajax.reload();
                }
              });

          }
      };
