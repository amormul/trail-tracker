<div
    class="modal fade"
    id="difficultyModal"
    tabindex="-1"
    aria-labelledby="difficultyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="difficultyModalLabel">
                    Add difficulty
                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= \app\core\Route::url('index', 'addDifficulty') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modalDifficultyName">Difficulty name:</label>
                        <input
                            type="text"
                            class="form-control"
                            id="modalDifficultyName"
                            name="name"
                            placeholder="Enter difficulty name..."
                            required />
                    </div>
                    <div class="form-group">
                        <label for="modalDifficultyDescription">Description:</label>
                        <textarea
                            class="form-control"
                            id="modalDifficultyDescription"
                            name="description"
                            rows="3"
                            placeholder="Enter description..."></textarea>
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
