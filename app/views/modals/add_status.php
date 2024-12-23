<div
    class="modal fade"
    id="statusModal"
    tabindex="-1"
    aria-labelledby="statusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">
                    Add Status
                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= \app\core\Route::url('index', 'addStatus') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modalStatusName">Status name:</label>
                        <input
                            type="text"
                            class="form-control"
                            id="modalStatusName"
                            name="name"
                            placeholder="Enter status name..."
                            required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
