<div class="modal fade" id="crud-modal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" class="mt-1" id="crudModalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="modalTitle"></h5>
                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ $modalBody }}
                </div>
                <div class="modal-footer">
                    {{ $modalFooter }}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
