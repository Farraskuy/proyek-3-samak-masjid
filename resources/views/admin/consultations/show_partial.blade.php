<div class="card shadow mb-4 h-100">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            {{ $consultation->question_subject }}
            <span class="badge badge-{{ $consultation->status == 'active' ? 'success' : ($consultation->status == 'pending' ? 'warning' : 'secondary') }} ml-2">
                {{ ucfirst($consultation->status) }}
            </span>
        </h6>
        
        @if($consultation->status == 'pending')
            <div>
                <form action="{{ route('admin.consultations.accept', $consultation->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Terima</button>
                </form>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal">Tolak</button>
            </div>
        @elseif($consultation->status == 'active')
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#closeModal">Tutup Sesi</button>
        @endif
    </div>

    <div class="card-body d-flex flex-column" style="height: 600px;">
        <div class="flex-grow-1 overflow-auto mb-3" id="adminChatContainer">
             <!-- Initial Question -->
             <div class="d-flex justify-content-start mb-4">
                <div class="card border-0 shadow-sm bg-light" style="max-width: 75%;">
                    <div class="card-body p-3">
                        <small class="text-primary fw-bold mb-1 d-block">{{ $consultation->user->full_name ?? 'Hamba Allah' }}</small>
                        <p class="mb-0">{{ $consultation->question_text }}</p>
                        <small class="text-muted d-block text-right mt-1">{{ $consultation->created_at->format('H:i') }}</small>
                    </div>
                </div>
            </div>

            @foreach($messages as $msg)
                <div class="d-flex justify-content-{{ $msg->user_id == Auth::id() ? 'end' : 'start' }} mb-3">
                    <div class="card border-0 shadow-sm {{ $msg->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
                        <div class="card-body p-3">
                            <p class="mb-0">{{ $msg->message }}</p>
                            <small class="{{ $msg->user_id == Auth::id() ? 'text-white-50' : 'text-muted' }} d-block text-right mt-1">
                                {{ $msg->created_at->format('H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($consultation->status == 'active')
            <form action="{{ route('client.consultations.send-message', $consultation->id) }}" method="POST" class="mt-auto">
                @csrf
                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Tulis balasan..." required>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Kirim</button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.consultations.reject', $consultation->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Konsultasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan</label>
                        <textarea name="reason" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Close Modal -->
<div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.consultations.close', $consultation->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tutup Sesi Konsultasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kesimpulan / Catatan Akhir</label>
                        <textarea name="conclusion" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan & Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var chatDiv = document.getElementById("adminChatContainer");
    if(chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
</script>
