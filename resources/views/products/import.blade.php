
@extends('../layout')

@section('content')
<h2 class="display-6 text-center mb-4">Import Products</h2>
<div class="row justify-content-center">
      <div class="col-md-8">
          <div class="card">
              <div class="card-header text-center">
                  <h5>Upload File</h5>
              </div>

              <div class="card-body">
                  <div id="upload-container" class="text-center">
                      <button id="browseFile" class="btn btn-primary">Choose File</button>
                  </div>
                  <div  style="display: none" class="progress mt-3" style="height: 25px">
                      <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
    let browseFile = $('#browseFile');
    let resumable = new Resumable({
        target: window.location.href, // rout for upload
        query:{_token:'{{ csrf_token() }}'} ,// CSRF token
        fileType: ['xml'],
        chunkSize: 10*1024*1024,
        headers: {
            'Accept' : 'application/json'
        },
        testChunks: false,
        throttleProgressCallbacks: 1,
    });

    resumable.assignBrowse(browseFile[0]);

    resumable.on('fileAdded', function (file) { // trigger when file picked
        showProgress();
        // to start uploading.
        resumable.upload() 
    });

    // trigger when file progress update
    resumable.on('fileProgress', function (file) { 
        updateProgress(Math.floor(file.progress() * 100));
    });

    // trigger when file upload complete
    resumable.on('fileSuccess', function (file, response) { 
        response = JSON.parse(response)
        // to reset file value
        resumable.removeFile(file);
        hideProgress();
        alert('File uploaded successfully.');
    });

    // trigger when there is any error 
    resumable.on('fileError', function (file, response) { 
        // to reset file value
        resumable.removeFile(file);
        hideProgress();
        alert('Failed to upload the file.')
    });


    let progress = $('.progress');
    function showProgress() {
        progress.find('.progress-bar').css('width', '0%');
        progress.find('.progress-bar').html('0%');
        progress.find('.progress-bar').removeClass('bg-success');
        progress.show();
    }

    function updateProgress(value) {
        progress.find('.progress-bar').css('width', `${value}%`)
        progress.find('.progress-bar').html(`${value}%`)
    }

    function hideProgress() {
        progress.hide();
    }
</script>
@endsection