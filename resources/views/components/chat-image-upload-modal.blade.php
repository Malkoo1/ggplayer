<div class="modal fade" id="chatImageUploadModal" tabindex="-1" role="dialog" aria-labelledby="chatImageUploadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatImageUploadModalLabel">Upload Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="chatImageUploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="conversation_id" id="conversation_id" value="">
                    <div class="form-group">
                        <label for="image">Select Image</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                    </div>
                    <div class="progress mt-3" style="display: none;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="uploadChatImageBtn">Upload</button>
            </div>
        </div>
    </div>
</div>
